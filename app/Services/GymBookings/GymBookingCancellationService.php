<?php

namespace App\Services\GymBookings;

use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Services\GymListing\BookingWebhookDispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Refund;
use Stripe\Stripe;

final class GymBookingCancellationService
{
    public function __construct(
        private readonly BookingWebhookDispatcher $webhooks,
    ) {}

    /**
     * Cancel a confirmed booking (refund Stripe when applicable), release capacity, optional webhook.
     *
     * @throws RuntimeException When refund fails or booking cannot be cancelled.
     */
    public function cancelConfirmed(GymBooking $booking): void
    {
        if (! $booking->isCancellable()) {
            throw new RuntimeException(__('This booking cannot be cancelled.'));
        }

        DB::transaction(function () use ($booking): void {
            $locked = GymBooking::query()->whereKey($booking->id)->lockForUpdate()->first();
            if ($locked === null || ! $locked->isCancellable()) {
                throw new RuntimeException(__('This booking cannot be cancelled.'));
            }

            $this->refundStripeIfNeeded($locked);

            $locked->forceFill(['status' => 'cancelled'])->save();
        });

        $booking->refresh();

        $this->webhooks->dispatchBookingCancelledForBooking($booking);
    }

    private function refundStripeIfNeeded(GymBooking $booking): void
    {
        $piId = $booking->stripe_payment_intent_id;
        if ($piId === null || $piId === '') {
            return;
        }

        $settings = ApplicationSetting::instance();
        $secret = $settings->stripeSecretKey();
        if ($secret === null || $secret === '') {
            throw new RuntimeException(__('Online payment is not configured; refund could not be processed.'));
        }

        Stripe::setApiKey($secret);

        try {
            Refund::create([
                'payment_intent' => $piId,
            ]);
        } catch (InvalidRequestException $e) {
            if ($this->isAlreadyRefundedMessage($e->getMessage())) {
                return;
            }
            Log::error('stripe_refund_failed', [
                'booking_id' => $booking->id,
                'payment_intent' => $piId,
                'message' => $e->getMessage(),
            ]);
            throw new RuntimeException(__('The payment refund could not be completed. Please contact support.'));
        } catch (\Throwable $e) {
            Log::error('stripe_refund_failed', [
                'booking_id' => $booking->id,
                'payment_intent' => $piId,
                'message' => $e->getMessage(),
            ]);
            throw new RuntimeException(__('The payment refund could not be completed. Please contact support.'));
        }
    }

    private function isAlreadyRefundedMessage(string $message): bool
    {
        $lower = strtolower($message);

        return str_contains($lower, 'already been refunded')
            || str_contains($lower, 'already refunded');
    }
}
