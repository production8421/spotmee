<?php

namespace App\Services\Payments;

use App\Enums\HostPayoutStatus;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Services\Admin\GymBookingProfitMarginService;
use Illuminate\Support\Facades\Schema;

final class HostPayoutScheduler
{
    public function __construct(
        private readonly GymBookingProfitMarginService $profitMarginService,
    ) {}

    public function scheduleForBooking(GymBooking $booking): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        $booking->loadMissing('gymListing.user');

        if (($booking->status ?? '') !== 'confirmed') {
            return;
        }

        if (! $booking->isHostPayoutSplitEnabled()) {
            $this->markSkipped($booking, 'booking_split_disabled');

            return;
        }

        if (! filled($booking->stripe_payment_intent_id) || (float) ($booking->total_price ?? 0) <= 0) {
            $this->markSkipped($booking, 'no_stripe_payment');

            return;
        }

        $breakdown = $this->profitMarginService->forBooking($booking);
        $hostAmount = round((float) ($breakdown['host_total_payout'] ?? 0), 2);

        if ($hostAmount <= 0) {
            $this->markSkipped($booking, 'zero_host_payout');

            return;
        }

        $delayHours = ApplicationSetting::instance()->hostPayoutDelayHours();

        $booking->forceFill([
            'host_payout_amount' => $hostAmount,
            'host_payout_scheduled_at' => $booking->bookingStartAt()->addHours($delayHours),
            'host_payout_status' => HostPayoutStatus::Pending->value,
            'host_payout_processed_at' => null,
            'stripe_transfer_id' => null,
            'host_payout_skip_reason' => null,
            'host_payout_failure_reason' => null,
        ])->save();
    }

    public function scheduleIfMissing(GymBooking $booking): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        if (filled($booking->host_payout_status)) {
            return;
        }

        $this->scheduleForBooking($booking);
    }

    public function applyBookingSplitToggle(GymBooking $booking, bool $enabled): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        $booking->forceFill(['host_payout_split_enabled' => $enabled])->save();

        if (! $enabled) {
            $this->markSkipped($booking, 'booking_split_disabled');

            return;
        }

        $this->scheduleForBooking($booking->fresh());
    }

    public function rescheduleGloballyDisabledBookings(): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        if (! ApplicationSetting::instance()->hostPayoutSplitEnabled()) {
            return;
        }

        GymBooking::query()
            ->where('status', 'confirmed')
            ->where('host_payout_split_enabled', true)
            ->whereNotNull('stripe_payment_intent_id')
            ->where('host_payout_status', HostPayoutStatus::Skipped->value)
            ->where('host_payout_skip_reason', 'split_disabled')
            ->orderBy('id')
            ->chunkById(100, function ($bookings): void {
                foreach ($bookings as $booking) {
                    $this->scheduleForBooking($booking);
                }
            });
    }

    public function reschedulePendingBookings(int $delayHours): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        GymBooking::query()
            ->where('host_payout_status', HostPayoutStatus::Pending->value)
            ->where('host_payout_split_enabled', true)
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use ($delayHours): void {
                foreach ($bookings as $booking) {
                    $booking->forceFill([
                        'host_payout_scheduled_at' => $booking->bookingStartAt()->addHours($delayHours),
                    ])->save();
                }
            });
    }

    public function markSkippedForCancellation(GymBooking $booking): void
    {
        if (! $this->supportsHostPayouts()) {
            return;
        }

        $status = HostPayoutStatus::tryFrom((string) ($booking->host_payout_status ?? ''));
        if ($status === HostPayoutStatus::Paid || $status === HostPayoutStatus::Processing) {
            return;
        }

        $this->markSkipped($booking, 'booking_cancelled');
    }

    private function markSkipped(GymBooking $booking, string $reason): void
    {
        $booking->forceFill([
            'host_payout_amount' => $booking->host_payout_amount ?? 0,
            'host_payout_scheduled_at' => null,
            'host_payout_status' => HostPayoutStatus::Skipped->value,
            'host_payout_processed_at' => now(),
            'host_payout_skip_reason' => $reason,
            'host_payout_failure_reason' => null,
        ])->save();
    }

    private function supportsHostPayouts(): bool
    {
        return Schema::hasColumn((new GymBooking)->getTable(), 'host_payout_status');
    }
}
