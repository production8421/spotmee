<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Services\GymBookings\GymBookingCancellationService;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class PublicGymBookingCancellationController extends Controller
{
    public function __invoke(GymBooking $booking, GymBookingCancellationService $cancellationService): RedirectResponse
    {
        $target = ApplicationSetting::instance()->resolvedBookingCancelResultUrl();

        try {
            if (! $booking->isCancellable()) {
                return $this->redirectWithQuery($target, ['booking_cancel' => 'not_allowed']);
            }

            $cancellationService->cancelConfirmed($booking);
        } catch (RuntimeException) {
            return $this->redirectWithQuery($target, ['booking_cancel' => 'error']);
        } catch (\Throwable $e) {
            report($e);

            return $this->redirectWithQuery($target, ['booking_cancel' => 'error']);
        }

        return $this->redirectWithQuery($target, ['booking_cancel' => 'ok']);
    }

    private function redirectWithQuery(string $url, array $query): RedirectResponse
    {
        $sep = str_contains($url, '?') ? '&' : '?';

        return redirect()->to($url.$sep.http_build_query($query));
    }
}
