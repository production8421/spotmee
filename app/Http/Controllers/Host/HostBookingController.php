<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Services\Admin\GymBookingProfitMarginService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostBookingController extends Controller
{
    public function index(Request $request, GymBookingProfitMarginService $profitService): View
    {
        $hostId = (int) $request->user()->id;

        $bookings = GymBooking::query()
            ->whereHas('gymListing', fn ($query) => $query->where('user_id', $hostId))
            ->with([
                'user:id,name,email',
                'gymListing:id,name,user_id,city,state',
                'coupon:id,code',
            ])
            ->orderByDesc('booking_date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $earnings = $bookings->getCollection()->mapWithKeys(
            fn (GymBooking $booking): array => [$booking->id => $profitService->forBooking($booking)]
        );

        return view('host.bookings.index', [
            'bookings' => $bookings,
            'earnings' => $earnings,
            'shareEarningsWithHost' => ApplicationSetting::instance()->hostPayoutSplitEnabled(),
        ]);
    }
}
