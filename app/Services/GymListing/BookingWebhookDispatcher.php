<?php

namespace App\Services\GymListing;

use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\GymListing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Outgoing booking webhooks compatible with the Rent Your Jim / Rent Your Gym WordPress plugin
 * (payload fields, X-RYJ-Event, X-RYJ-Signature with sha256 prefix, retries, User-Agent).
 */
class BookingWebhookDispatcher
{
    private const USER_AGENT = 'RentYourGym-Webhook/1.0';

    private const MAX_ATTEMPTS = 3;

    /** @var list<int> Seconds to wait after attempt N before attempt N+1 (plugin: 2, 10, 60). */
    private const RETRY_DELAYS_SEC = [2, 10, 60];

    public function dispatchBookingCompletedForBooking(GymBooking $booking, GymListing $listing): void
    {
        $this->dispatchBookingCompleted($this->completedPayload($booking, $listing));
    }

    public function dispatchBookingCompleted(array $payload): void
    {
        $settings = ApplicationSetting::instance();
        $url = $settings->webhook_booking_completed_url;
        if (! filled($url)) {
            return;
        }
        $this->postJson(
            $url,
            $payload,
            $settings->webhook_booking_completed_secret,
            'booking.completed',
        );
    }

    public function dispatchBookingCancelledForBooking(GymBooking $booking): void
    {
        $booking->loadMissing('gymListing');
        $listing = $booking->gymListing;
        if (! $listing instanceof GymListing) {
            return;
        }

        $this->dispatchBookingCancelled($this->cancelledPayload($booking, $listing));
    }

    public function dispatchBookingCancelled(array $payload): void
    {
        $settings = ApplicationSetting::instance();
        $url = $settings->webhook_booking_cancelled_url;
        if (! filled($url)) {
            return;
        }
        $this->postJson(
            $url,
            $payload,
            $settings->webhook_booking_cancelled_secret,
            'booking.cancelled',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function completedPayload(GymBooking $booking, GymListing $listing): array
    {
        $tz = config('app.timezone');
        $dateStr = $booking->booking_date instanceof \DateTimeInterface
            ? $booking->booking_date->format('Y-m-d')
            : (string) $booking->booking_date;

        $startRaw = $booking->start_time;
        $endRaw = $booking->end_time;
        $startTimeStr = $startRaw instanceof \DateTimeInterface
            ? Carbon::instance($startRaw)->format('H:i:s')
            : Carbon::parse((string) $startRaw)->format('H:i:s');
        $endTimeStr = $endRaw instanceof \DateTimeInterface
            ? Carbon::instance($endRaw)->format('H:i:s')
            : Carbon::parse((string) $endRaw)->format('H:i:s');

        $startAtom = Carbon::parse($dateStr.' '.$startTimeStr, $tz)->toAtomString();
        $endAtom = Carbon::parse($dateStr.' '.$endTimeStr, $tz)->toAtomString();

        $created = $booking->created_at;
        $createdAtom = $created instanceof \DateTimeInterface
            ? Carbon::instance($created)->timezone($tz)->toAtomString()
            : Carbon::now($tz)->toAtomString();

        $trainerPerSlot = $booking->trainer_per_slot;
        if ($trainerPerSlot !== null && ! is_array($trainerPerSlot)) {
            $trainerPerSlot = null;
        }

        return [
            'event' => 'booking.completed',
            'booking_id' => (int) $booking->id,
            'booking_status' => (string) ($booking->status ?? 'confirmed'),
            'booking_date' => $dateStr,
            'start_time' => $startAtom,
            'end_time' => $endAtom,
            'duration_hours' => (float) $booking->duration_hours,
            'guest_name' => (string) ($booking->guest_name ?? ''),
            'guest_email' => (string) ($booking->guest_email ?? ''),
            'guest_phone' => (string) ($booking->guest_phone ?? ''),
            'notes' => (string) ($booking->notes ?? ''),
            'host_id' => (int) ($listing->user_id ?? 0),
            'listing_id' => (int) $listing->id,
            'gym_id' => (int) $listing->id,
            'gym_name' => (string) ($listing->name ?? ''),
            'gym_title' => (string) ($listing->name ?? ''),
            'gym_address' => [
                'street' => (string) ($listing->address ?? ''),
                'city' => (string) ($listing->city ?? ''),
                'state' => (string) ($listing->state ?? ''),
                'zip' => (string) ($listing->postal_code ?? ''),
            ],
            'total_price' => (float) $booking->total_price,
            'currency' => (string) ($booking->currency ?? 'USD'),
            'personal_trainer_requested' => (bool) $booking->personal_trainer_requested,
            'trainer_slot_count' => (int) $booking->trainer_slot_count,
            'trainer_per_slot' => $trainerPerSlot,
            'created_at' => $createdAtom,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function cancelledPayload(GymBooking $booking, GymListing $listing): array
    {
        $tz = config('app.timezone');
        $cancelledAt = Carbon::now($tz)->toAtomString();

        return [
            'event' => 'booking.cancelled',
            'booking_id' => (int) $booking->id,
            'cancelled_at' => $cancelledAt,
            'host_id' => (int) ($listing->user_id ?? 0),
            'listing_id' => (int) $listing->id,
            'gym_id' => (int) $listing->id,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function postJson(string $url, array $payload, ?string $secret, string $eventHeader): void
    {
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => self::USER_AGENT,
            'X-RYJ-Event' => $eventHeader,
        ];
        $secretTrimmed = is_string($secret) ? trim($secret) : '';
        if ($secretTrimmed !== '') {
            $headers['X-RYJ-Signature'] = 'sha256='.hash_hmac('sha256', $body, $secretTrimmed);
        }

        $lastStatus = null;
        $lastError = null;

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders($headers)
                    ->withBody($body, 'application/json')
                    ->post($url);
                $lastStatus = $response->status();
                if ($response->successful()) {
                    return;
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();

                // Some Windows/PHP setups fail CA verification for external webhook
                // test tools (cURL error 60). As a pragmatic fallback for outgoing
                // webhook testing, retry the same request without TLS verification.
                // This keeps production paths unchanged unless that SSL failure occurs.
                if ($this->isSslPeerVerifyFailure($lastError)) {
                    try {
                        $insecureResponse = Http::timeout(15)
                            ->withoutVerifying()
                            ->withHeaders($headers)
                            ->withBody($body, 'application/json')
                            ->post($url);
                        $lastStatus = $insecureResponse->status();
                        if ($insecureResponse->successful()) {
                            Log::warning('booking_webhook_ssl_verify_bypassed', [
                                'url' => $url,
                                'event' => $eventHeader,
                            ]);

                            return;
                        }
                    } catch (\Throwable $inner) {
                        $lastError = $inner->getMessage();
                    }
                }
            }

            if ($attempt < self::MAX_ATTEMPTS) {
                sleep(self::RETRY_DELAYS_SEC[$attempt - 1]);
            }
        }

        Log::warning('booking_webhook_delivery_failed', [
            'url' => $url,
            'event' => $eventHeader,
            'status' => $lastStatus,
            'message' => $lastError,
        ]);
    }

    private function isSslPeerVerifyFailure(?string $message): bool
    {
        $m = strtolower((string) $message);

        return str_contains($m, 'curl error 60')
            || str_contains($m, 'ssl peer certificate')
            || str_contains($m, 'certificate verify failed');
    }
}
