<?php

namespace App\Services\GymListing;

use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingWebhookDispatcher
{
    public function dispatchBookingCompleted(array $payload): void
    {
        $settings = ApplicationSetting::instance();
        $url = $settings->webhook_booking_completed_url;
        if (! filled($url)) {
            return;
        }
        $this->postJson($url, $payload, $settings->webhook_booking_completed_secret);
    }

    public function dispatchBookingCancelled(array $payload): void
    {
        $settings = ApplicationSetting::instance();
        $url = $settings->webhook_booking_cancelled_url;
        if (! filled($url)) {
            return;
        }
        $this->postJson($url, $payload, $settings->webhook_booking_cancelled_secret);
    }

    private function postJson(string $url, array $payload, ?string $secret): void
    {
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
        if (filled($secret)) {
            $headers['X-RYJ-Signature'] = hash_hmac('sha256', $body, $secret);
        }

        try {
            Http::timeout(15)
                ->withHeaders($headers)
                ->withBody($body, 'application/json')
                ->post($url);
        } catch (\Throwable $e) {
            Log::warning('booking_webhook_request_failed', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
