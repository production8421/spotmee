<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\GymBooking;
use App\Services\GymBookings\GymBookingCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class SubscriberGymBookingController extends Controller
{
    /**
     * Cancel the subscriber's own gym booking (same rules as the signed email link).
     */
    public function cancel(Request $request, GymBooking $booking, GymBookingCancellationService $cancellationService): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null && $user->hasRole(UserRole::Subscriber->value), 403);
        abort_unless($booking->user_id !== null && (int) $booking->user_id === (int) $user->id, 403);

        try {
            $cancellationService->cancelConfirmed($booking);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('dashboard')
                ->withFragment('subscriber-gym-bookings')
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('dashboard')
                ->withFragment('subscriber-gym-bookings')
                ->with('error', __('Something went wrong. Please try again.'));
        }

        return redirect()
            ->route('dashboard')
            ->withFragment('subscriber-gym-bookings')
            ->with('status', __('Your booking has been cancelled.'));
    }
}
