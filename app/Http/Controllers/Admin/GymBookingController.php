<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymBooking;
use Illuminate\View\View;

class GymBookingController extends Controller
{
    public function index(): View
    {
        $bookings = GymBooking::query()
            ->with(['gymListing', 'user'])
            ->orderByDesc('booking_date')
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.gym-bookings.index', [
            'bookings' => $bookings,
        ]);
    }
}
