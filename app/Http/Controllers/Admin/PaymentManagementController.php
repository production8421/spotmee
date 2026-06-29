<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingPayoutSplitRequest;
use App\Http\Requests\Admin\UpdateHostPayoutSettingsRequest;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\HostBankingDetail;
use App\Models\User;
use App\Services\Admin\GymBookingDetailsFilter;
use App\Services\Admin\GymBookingDetailsSummary;
use App\Services\Admin\GymBookingProfitMarginService;
use App\Services\Payments\HostPayoutScheduler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PaymentManagementController extends Controller
{
    public function hostBanking(): View
    {
        if (! Schema::hasTable((new HostBankingDetail)->getTable())) {
            $page = max(1, (int) request()->query('page', 1));

            return view('admin.payment-management.host-banking', [
                'bankingDetails' => new LengthAwarePaginator([], 0, 25, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]),
                'pageTitle' => __('Host banking details'),
                'breadcrumbActive' => __('Host banking details'),
                'tableMissing' => true,
            ]);
        }

        $bankingDetails = HostBankingDetail::query()
            ->with(['user:id,name,email', 'hostApplication:id,full_name,email'])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.payment-management.host-banking', [
            'bankingDetails' => $bankingDetails,
            'pageTitle' => __('Host banking details'),
            'breadcrumbActive' => __('Host banking details'),
            'tableMissing' => false,
        ]);
    }

    public function userPayments(): View
    {
        $payments = GymBooking::query()
            ->with([
                'user:id,name,email',
                'gymListing:id,name,user_id,city,state',
                'gymListing.user:id,name,email',
            ])
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.payment-management.user-payments', [
            'payments' => $payments,
            'pageTitle' => __('User payments'),
            'breadcrumbActive' => __('User payments'),
        ]);
    }

    public function bookingDetails(
        Request $request,
        GymBookingProfitMarginService $profitService,
        GymBookingDetailsSummary $summaryService,
    ): View {
        $settings = ApplicationSetting::instance();
        $filters = GymBookingDetailsFilter::filtersFromRequest($request);

        $baseQuery = GymBooking::query();
        GymBookingDetailsFilter::apply($baseQuery, $filters);

        $bookings = (clone $baseQuery)
            ->with([
                'user:id,name,email',
                'gymListing:id,name,user_id,city,state',
                'gymListing.user:id,name,email',
                'coupon:id,code',
            ])
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $profitMargins = $bookings->getCollection()->mapWithKeys(
            fn (GymBooking $booking): array => [$booking->id => $profitService->forBooking($booking)]
        );

        $filterHosts = User::role(UserRole::Host->value)
            ->whereHas('gymListings.bookings')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.payment-management.booking-details', [
            'bookings' => $bookings,
            'profitMargins' => $profitMargins,
            'profitSummary' => $summaryService->forQuery($baseQuery),
            'filters' => $filters,
            'filterQueryParams' => GymBookingDetailsFilter::queryParams($filters),
            'filterHosts' => $filterHosts,
            'pageTitle' => __('Booking details & profit'),
            'breadcrumbActive' => __('Booking details & profit'),
            'shareHostBookingEarnings' => $settings->hostPayoutSplitEnabled(),
            'hostPayoutDelayHours' => $settings->hostPayoutDelayHours(),
            'platformCommissionPct' => $settings->platformCommissionPct(),
        ]);
    }

    public function updateHostPayoutSettings(
        UpdateHostPayoutSettingsRequest $request,
        HostPayoutScheduler $scheduler,
    ): RedirectResponse {
        $settings = ApplicationSetting::instance();
        $previousDelay = $settings->hostPayoutDelayHours();

        $settings->share_host_booking_earnings = $request->boolean('share_host_booking_earnings');
        $settings->host_payout_delay_hours = (int) $request->input('host_payout_delay_hours');
        $settings->platform_commission_pct = round((float) $request->input('platform_commission_pct'), 2);
        $settings->save();

        $newDelay = $settings->hostPayoutDelayHours();
        if ($newDelay !== $previousDelay) {
            $scheduler->reschedulePendingBookings($newDelay);
        }

        if ($request->boolean('share_host_booking_earnings')) {
            $scheduler->rescheduleGloballyDisabledBookings();
        }

        return redirect()
            ->route('admin.payment-management.booking-details.index', $this->bookingDetailsRedirectParams($request))
            ->with('status', $request->boolean('share_host_booking_earnings')
                ? __('Host payout split is enabled. Transfers run :hours hours after each booking start.', ['hours' => $newDelay])
                : __('Host payout split is disabled. No Stripe transfers will be sent to hosts.'));
    }

    public function updateBookingPayoutSplit(
        UpdateBookingPayoutSplitRequest $request,
        GymBooking $booking,
        HostPayoutScheduler $scheduler,
    ): RedirectResponse {
        $enabled = $request->boolean('host_payout_split_enabled');
        $scheduler->applyBookingSplitToggle($booking, $enabled);

        return redirect()
            ->route('admin.payment-management.booking-details.index', $this->bookingDetailsRedirectParams($request))
            ->with('status', $enabled
                ? __('Host payout split enabled for booking #:id.', ['id' => $booking->id])
                : __('Host payout split disabled for booking #:id.', ['id' => $booking->id]));
    }

    /**
     * @return array<string, mixed>
     */
    private function bookingDetailsRedirectParams(Request $request): array
    {
        $params = GymBookingDetailsFilter::queryParams(
            GymBookingDetailsFilter::filtersFromRequest($request)
        );

        $page = $request->input('page', $request->query('page'));
        if (filled($page)) {
            $params['page'] = $page;
        }

        return $params;
    }
}
