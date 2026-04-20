<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmPublicGymBookingPaymentRequest;
use App\Http\Requests\StorePublicGymBookingRequest;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\GymListing;
use App\Services\GymBookings\GymBookingCreationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PublicGymBookingController extends Controller
{
    public function blockedTimes(string $slug, GymBookingCreationService $service): JsonResponse
    {
        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $from = Carbon::now()->startOfDay();
        $to = Carbon::now()->addDays(60)->endOfDay();

        return response()->json($service->blockedIntervalsForListing($listing, $from, $to));
    }

    public function store(
        StorePublicGymBookingRequest $request,
        string $slug,
        GymBookingCreationService $service
    ): JsonResponse {
        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        try {
            $result = $service->createFromPublicRequest($listing, $request->validated());
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        $booking = $result['booking'];

        return response()->json([
            'success' => true,
            'message' => __('Your booking is confirmed.'),
            'confirmation_code' => $booking->confirmation_code,
            'booking_id' => $booking->id,
        ]);
    }

    public function createPaymentIntent(
        StorePublicGymBookingRequest $request,
        string $slug,
        GymBookingCreationService $service
    ): JsonResponse {
        $settings = ApplicationSetting::instance();
        if (! $settings->isStripeConfiguredForPayments()) {
            return response()->json([
                'success' => false,
                'message' => __('Online payment is not configured.'),
            ], 503);
        }

        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validated();

        try {
            $quote = $service->resolvePublicBookingQuote($listing, $validated);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        $amountCents = (int) round(((float) $quote['total_price']) * 100);
        if ($amountCents < 1) {
            return response()->json([
                'success' => false,
                'zero_amount' => true,
                'message' => __('No card payment is required for this booking.'),
            ], 422);
        }

        $secret = $settings->stripeSecretKey();
        if ($secret === null || $secret === '') {
            return response()->json([
                'success' => false,
                'message' => __('Online payment is not configured.'),
            ], 503);
        }

        Stripe::setApiKey($secret);

        try {
            $intent = PaymentIntent::create([
                'amount' => $amountCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'gym_listing_id' => (string) $listing->id,
                    'gym_slug' => $listing->slug,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('Could not start payment. Please try again.'),
            ], 502);
        }

        Cache::put(
            'gym_booking_pi:'.$intent->id,
            [
                'gym_listing_id' => $listing->id,
                'validated' => $validated,
            ],
            now()->addMinutes(30)
        );

        return response()->json([
            'success' => true,
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
        ]);
    }

    public function confirmPayment(
        ConfirmPublicGymBookingPaymentRequest $request,
        string $slug,
        GymBookingCreationService $service
    ): JsonResponse {
        $settings = ApplicationSetting::instance();
        if (! $settings->isStripeConfiguredForPayments()) {
            return response()->json([
                'success' => false,
                'message' => __('Online payment is not configured.'),
            ], 503);
        }

        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $piId = $request->validated()['payment_intent_id'];

        $existing = GymBooking::query()
            ->where('stripe_payment_intent_id', $piId)
            ->first();
        if ($existing !== null) {
            return response()->json([
                'success' => true,
                'message' => __('Your booking is confirmed.'),
                'confirmation_code' => $existing->confirmation_code,
                'booking_id' => $existing->id,
            ]);
        }

        $cacheKey = 'gym_booking_pi:'.$piId;
        $cached = Cache::get($cacheKey);
        if (! is_array($cached)
            || ! isset($cached['gym_listing_id'], $cached['validated'])
            || (int) $cached['gym_listing_id'] !== (int) $listing->id) {
            return response()->json([
                'success' => false,
                'message' => __('Payment session expired or is invalid. Please start again.'),
            ], 422);
        }

        $secret = $settings->stripeSecretKey();
        if ($secret === null || $secret === '') {
            return response()->json([
                'success' => false,
                'message' => __('Online payment is not configured.'),
            ], 503);
        }

        Stripe::setApiKey($secret);

        try {
            $intent = PaymentIntent::retrieve($piId);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('Could not verify payment.'),
            ], 502);
        }

        if ($intent->status !== 'succeeded') {
            return response()->json([
                'success' => false,
                'message' => __('Payment is not complete yet.'),
            ], 422);
        }

        /** @var array<string, mixed> $validated */
        $validated = $cached['validated'];

        try {
            $quote = $service->resolvePublicBookingQuote($listing, $validated);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        $expectedCents = (int) round(((float) $quote['total_price']) * 100);
        if ((int) $intent->amount !== $expectedCents) {
            return response()->json([
                'success' => false,
                'message' => __('Payment amount does not match the booking.'),
            ], 422);
        }

        try {
            $result = $service->createFromPublicRequest($listing, $validated, $piId);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        Cache::forget($cacheKey);

        $booking = $result['booking'];

        return response()->json([
            'success' => true,
            'message' => __('Your booking is confirmed.'),
            'confirmation_code' => $booking->confirmation_code,
            'booking_id' => $booking->id,
        ]);
    }
}
