<?php

namespace App\Services\Admin;

use App\Models\GymBooking;
use Illuminate\Database\Eloquent\Builder;

final class GymBookingDetailsSummary
{
    public function __construct(
        private readonly GymBookingProfitMarginService $profitMarginService,
    ) {}

    /**
     * @param  Builder<GymBooking>  $query
     * @return array{
     *     booking_count: int,
     *     customer_paid: float,
     *     host_payout: float,
     *     platform_profit: float,
     *     profit_margin_pct: float,
     *     currency: string
     * }
     */
    public function forQuery(Builder $query): array
    {
        $totals = [
            'booking_count' => 0,
            'customer_paid' => 0.0,
            'host_payout' => 0.0,
            'platform_profit' => 0.0,
            'profit_margin_pct' => 0.0,
            'currency' => 'USD',
        ];

        (clone $query)
            ->with(['gymListing', 'coupon'])
            ->reorder('id')
            ->chunkById(100, function ($bookings) use (&$totals): void {
                foreach ($bookings as $booking) {
                    if (! $booking instanceof GymBooking) {
                        continue;
                    }

                    $profit = $this->profitMarginService->forBooking($booking);
                    $totals['booking_count']++;
                    $totals['customer_paid'] += (float) ($profit['customer_paid'] ?? 0);
                    $totals['host_payout'] += (float) ($profit['host_total_payout'] ?? 0);
                    $totals['platform_profit'] += (float) ($profit['platform_profit'] ?? 0);

                    if (! empty($profit['currency'])) {
                        $totals['currency'] = strtoupper((string) $profit['currency']);
                    }
                }
            });

        $totals['customer_paid'] = round($totals['customer_paid'], 2);
        $totals['host_payout'] = round($totals['host_payout'], 2);
        $totals['platform_profit'] = round($totals['platform_profit'], 2);
        $totals['profit_margin_pct'] = $totals['customer_paid'] > 0
            ? round(($totals['platform_profit'] / $totals['customer_paid']) * 100, 2)
            : 0.0;

        return $totals;
    }
}
