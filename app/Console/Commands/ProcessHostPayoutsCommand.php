<?php

namespace App\Console\Commands;

use App\Enums\HostPayoutStatus;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Services\Payments\HostPayoutScheduler;
use App\Services\Payments\StripeHostPayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ProcessHostPayoutsCommand extends Command
{
    protected $signature = 'host-payouts:process {--limit=50 : Maximum bookings to process per run}';

    protected $description = 'Process due host Stripe payout splits using admin payout settings (global toggle, delay hours, per-booking split).';

    public function handle(
        HostPayoutScheduler $scheduler,
        StripeHostPayoutService $payoutService,
    ): int {
        if (! Schema::hasColumn((new GymBooking)->getTable(), 'host_payout_status')) {
            $this->warn('Host payout columns are missing. Run migrations first.');

            return self::SUCCESS;
        }

        $settings = ApplicationSetting::instance();
        $delayHours = $settings->hostPayoutDelayHours();
        $globalEnabled = $settings->hostPayoutSplitEnabled();

        $this->line(__('Global host payout split: :state', [
            'state' => $globalEnabled ? __('enabled') : __('disabled'),
        ]));
        $this->line(__('Split delay: :hours hours after booking start', ['hours' => $delayHours]));

        $limit = max(1, (int) $this->option('limit'));

        $scheduled = 0;
        GymBooking::query()
            ->where('status', 'confirmed')
            ->whereNotNull('stripe_payment_intent_id')
            ->whereNull('host_payout_status')
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->each(function (GymBooking $booking) use ($scheduler, &$scheduled): void {
                $scheduler->scheduleIfMissing($booking);
                $scheduled++;
            });

        if ($scheduled > 0) {
            $this->line(__('Scheduled :count new booking payout(s).', ['count' => $scheduled]));
        }

        if (! $globalEnabled) {
            $this->warn(__('Global payout split is off — due bookings will be marked skipped until you enable it in Payment Management.'));

            return self::SUCCESS;
        }

        $due = GymBooking::query()
            ->where('host_payout_status', HostPayoutStatus::Pending->value)
            ->where('host_payout_split_enabled', true)
            ->whereNotNull('host_payout_scheduled_at')
            ->where('host_payout_scheduled_at', '<=', now())
            ->orderBy('host_payout_scheduled_at')
            ->limit($limit)
            ->get();

        foreach ($due as $booking) {
            $payoutService->processDueBooking($booking);
        }

        GymBooking::query()
            ->where('host_payout_status', HostPayoutStatus::AwaitingConnect->value)
            ->where('host_payout_split_enabled', true)
            ->whereNotNull('host_payout_scheduled_at')
            ->where('host_payout_scheduled_at', '<=', now())
            ->orderBy('host_payout_scheduled_at')
            ->limit($limit)
            ->get()
            ->each(fn (GymBooking $booking) => $payoutService->processDueBooking($booking));

        $this->info(__('Processed :count due host payout(s).', ['count' => $due->count()]));

        return self::SUCCESS;
    }
}
