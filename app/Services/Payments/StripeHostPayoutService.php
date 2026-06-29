<?php

namespace App\Services\Payments;

use App\Enums\HostPayoutStatus;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Transfer;

final class StripeHostPayoutService
{
    public function __construct(
        private readonly StripeHostConnectProvisioner $connectProvisioner,
    ) {}

    public function processDueBooking(GymBooking $booking): void
    {
        if (! Schema::hasColumn($booking->getTable(), 'host_payout_status')) {
            return;
        }

        if (! ApplicationSetting::instance()->hostPayoutSplitEnabled()) {
            $this->markSkipped($booking, 'split_disabled');

            return;
        }

        if (! $booking->isHostPayoutSplitEnabled()) {
            $this->markSkipped($booking, 'booking_split_disabled');

            return;
        }

        if (($booking->status ?? '') !== 'confirmed') {
            $this->markSkipped($booking, 'booking_not_confirmed');

            return;
        }

        $amount = round((float) ($booking->host_payout_amount ?? 0), 2);
        if ($amount <= 0) {
            $this->markSkipped($booking, 'zero_host_payout');

            return;
        }

        $booking->loadMissing('gymListing.user.hostBankingDetail');
        $host = $booking->gymListing?->user;
        $connectAccountId = $this->connectProvisioner->ensureForHostUser($host);

        if ($connectAccountId === null || $connectAccountId === '') {
            $failureReason = $host?->hostBankingDetail
                ? __('Host bank account could not be linked for automatic payout. Check banking details or contact support.')
                : __('Host has not submitted banking details for payout.');

            $booking->forceFill([
                'host_payout_status' => HostPayoutStatus::AwaitingConnect->value,
                'host_payout_failure_reason' => $failureReason,
            ])->save();

            return;
        }

        $settings = ApplicationSetting::instance();
        $secret = $settings->stripeSecretKey();
        if ($secret === null || $secret === '') {
            $this->markFailed($booking, __('Stripe is not configured.'));

            return;
        }

        Stripe::setApiKey($secret);

        DB::transaction(function () use ($booking): void {
            $locked = GymBooking::query()->whereKey($booking->id)->lockForUpdate()->first();
            $status = HostPayoutStatus::tryFrom((string) ($locked?->host_payout_status ?? ''));
            if ($locked === null || ! in_array($status, [HostPayoutStatus::Pending, HostPayoutStatus::AwaitingConnect], true)) {
                return;
            }

            $locked->forceFill([
                'host_payout_status' => HostPayoutStatus::Processing->value,
            ])->save();
        });

        $booking->refresh();

        try {
            $paymentIntent = PaymentIntent::retrieve((string) $booking->stripePaymentIntentIdForStripe());
            $chargeId = is_string($paymentIntent->latest_charge ?? null)
                ? $paymentIntent->latest_charge
                : null;

            if ($chargeId === null || $chargeId === '') {
                $this->markFailed($booking, __('Stripe charge was not found for this booking.'));

                return;
            }

            $amountCents = (int) round($amount * 100);
            $transfer = Transfer::create([
                'amount' => $amountCents,
                'currency' => strtolower((string) ($booking->currency ?: 'usd')),
                'destination' => $connectAccountId,
                'source_transaction' => $chargeId,
                'transfer_group' => 'booking_'.$booking->id,
                'metadata' => [
                    'gym_booking_id' => (string) $booking->id,
                    'confirmation_code' => (string) $booking->confirmation_code,
                ],
            ]);

            $booking->forceFill([
                'host_payout_status' => HostPayoutStatus::Paid->value,
                'host_payout_processed_at' => now(),
                'stripe_transfer_id' => $transfer->id,
                'host_payout_skip_reason' => null,
                'host_payout_failure_reason' => null,
            ])->save();
        } catch (ApiErrorException $e) {
            Log::error('stripe_host_payout_failed', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);
            $this->markFailed($booking, $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('stripe_host_payout_failed', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);
            $this->markFailed($booking, __('Host payout could not be completed.'));
        }
    }

    private function markSkipped(GymBooking $booking, string $reason): void
    {
        $booking->forceFill([
            'host_payout_status' => HostPayoutStatus::Skipped->value,
            'host_payout_processed_at' => now(),
            'host_payout_skip_reason' => $reason,
            'host_payout_failure_reason' => null,
        ])->save();
    }

    private function markFailed(GymBooking $booking, string $reason): void
    {
        $booking->forceFill([
            'host_payout_status' => HostPayoutStatus::Failed->value,
            'host_payout_processed_at' => now(),
            'host_payout_failure_reason' => $reason,
        ])->save();
    }
}
