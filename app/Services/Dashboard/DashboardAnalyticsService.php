<?php

namespace App\Services\Dashboard;

use App\Models\GymBooking;
use App\Models\GymListing;
use App\Services\Admin\GymBookingProfitMarginService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class DashboardAnalyticsService
{
    public function __construct(
        private readonly GymBookingProfitMarginService $profitMarginService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function adminCharts(): array
    {
        return [
            'bookings_trend' => $this->bookingsTrend(6),
            'booking_status' => $this->bookingStatusBreakdown(),
            'gym_listings' => $this->gymListingsBreakdown(),
            'payout_status' => $this->payoutStatusBreakdown(),
            'totals' => $this->confirmedBookingTotals(),
        ];
    }

    /**
     * @return array{labels: list<string>, counts: list<int>, revenue: list<float>, platform_profit: list<float>, host_payout: list<float>}
     */
    private function bookingsTrend(int $months): array
    {
        $labels = [];
        $counts = [];
        $revenue = [];
        $platformProfit = [];
        $hostPayout = [];

        $start = now()->subMonths($months - 1)->startOfMonth();

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $labels[] = $month->format('M Y');

            if (! Schema::hasTable('gym_bookings')) {
                $counts[] = 0;
                $revenue[] = 0.0;
                $platformProfit[] = 0.0;
                $hostPayout[] = 0.0;

                continue;
            }

            $monthBookings = GymBooking::query()
                ->whereYear('booking_date', $month->year)
                ->whereMonth('booking_date', $month->month)
                ->with(['gymListing', 'coupon'])
                ->get();

            $counts[] = $monthBookings->count();

            $confirmed = $monthBookings->filter(
                fn (GymBooking $booking): bool => ($booking->status ?? '') === 'confirmed'
            );

            $monthRevenue = 0.0;
            $monthPlatformProfit = 0.0;
            $monthHostPayout = 0.0;

            foreach ($confirmed as $booking) {
                $profit = $this->profitMarginService->forBooking($booking);
                $monthRevenue += (float) ($profit['customer_paid'] ?? 0);
                $monthPlatformProfit += (float) ($profit['platform_profit'] ?? 0);
                $monthHostPayout += (float) ($profit['host_total_payout'] ?? 0);
            }

            $revenue[] = round($monthRevenue, 2);
            $platformProfit[] = round($monthPlatformProfit, 2);
            $hostPayout[] = round($monthHostPayout, 2);
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'revenue' => $revenue,
            'platform_profit' => $platformProfit,
            'host_payout' => $hostPayout,
        ];
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    private function bookingStatusBreakdown(): array
    {
        if (! Schema::hasTable('gym_bookings')) {
            return ['labels' => [], 'values' => []];
        }

        $rows = GymBooking::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $status = (string) ($row->status ?? 'unknown');
            $labels[] = ucfirst($status);
            $values[] = (int) $row->total;
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    private function gymListingsBreakdown(): array
    {
        if (! Schema::hasTable('gym_listings')) {
            return ['labels' => [], 'values' => []];
        }

        $published = 0;
        $pending = 0;
        $declined = 0;
        $other = GymListing::query()->count();

        if (Schema::hasColumn('gym_listings', 'is_published') && Schema::hasColumn('gym_listings', 'approved_at')) {
            $published = GymListing::query()
                ->where('is_published', true)
                ->whereNotNull('approved_at')
                ->count();
        }

        if (Schema::hasColumn('gym_listings', 'approved_at') && Schema::hasColumn('gym_listings', 'rejected_at')) {
            $pending = GymListing::query()
                ->whereNotNull('user_id')
                ->whereNull('approved_at')
                ->whereNull('rejected_at')
                ->count();

            $declined = GymListing::query()
                ->whereNotNull('rejected_at')
                ->whereNull('approved_at')
                ->count();
        }

        $other = max(0, $other - $published - $pending - $declined);

        $labels = [__('Published'), __('Pending approval'), __('Declined')];
        $values = [$published, $pending, $declined];

        if ($other > 0) {
            $labels[] = __('Other');
            $values[] = $other;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    private function payoutStatusBreakdown(): array
    {
        if (! Schema::hasTable('gym_bookings') || ! Schema::hasColumn('gym_bookings', 'host_payout_status')) {
            return ['labels' => [], 'values' => []];
        }

        $rows = GymBooking::query()
            ->whereNotNull('host_payout_status')
            ->select('host_payout_status', DB::raw('COUNT(*) as total'))
            ->groupBy('host_payout_status')
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $status = (string) ($row->host_payout_status ?? '');
            $booking = new GymBooking(['host_payout_status' => $status]);
            $labels[] = $booking->hostPayoutStatusLabel();
            $values[] = (int) $row->total;
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{
     *     revenue: float,
     *     platform_profit: float,
     *     host_payout: float,
     *     profit_margin_pct: float,
     *     currency: string
     * }
     */
    private function confirmedBookingTotals(): array
    {
        $totals = [
            'revenue' => 0.0,
            'platform_profit' => 0.0,
            'host_payout' => 0.0,
            'profit_margin_pct' => 0.0,
            'currency' => 'USD',
        ];

        if (! Schema::hasTable('gym_bookings')) {
            return $totals;
        }

        GymBooking::query()
            ->where('status', 'confirmed')
            ->with(['gymListing', 'coupon'])
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use (&$totals): void {
                foreach ($bookings as $booking) {
                    if (! $booking instanceof GymBooking) {
                        continue;
                    }

                    $profit = $this->profitMarginService->forBooking($booking);
                    $totals['revenue'] += (float) ($profit['customer_paid'] ?? 0);
                    $totals['platform_profit'] += (float) ($profit['platform_profit'] ?? 0);
                    $totals['host_payout'] += (float) ($profit['host_total_payout'] ?? 0);

                    if (! empty($profit['currency'])) {
                        $totals['currency'] = strtoupper((string) $profit['currency']);
                    }
                }
            });

        $totals['revenue'] = round($totals['revenue'], 2);
        $totals['platform_profit'] = round($totals['platform_profit'], 2);
        $totals['host_payout'] = round($totals['host_payout'], 2);
        $totals['profit_margin_pct'] = $totals['revenue'] > 0
            ? round(($totals['platform_profit'] / $totals['revenue']) * 100, 2)
            : 0.0;

        return $totals;
    }
}
