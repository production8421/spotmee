<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\GymListing;
use App\Models\GymReview;
use App\Models\User;
use App\Support\RyjGymSchedule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PublicGymController extends Controller
{
    /**
     * Public gym detail page (WP: ryj_gym_main_page + ?gymID=).
     */
    public function show(string $slug): View
    {
        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $settings = ApplicationSetting::instance();
        $slotOffers = RyjGymSchedule::gymScheduleOfferSlotLengths(
            is_array($listing->availability_schedule) ? $listing->availability_schedule : null
        );
        $tier = $listing->hostTierKey();
        $pricing = $settings->publicGuestTierRates($tier);

        $photos = [];
        if ($listing->mainImageUrl()) {
            $photos[] = $listing->mainImageUrl();
        }
        foreach ($listing->galleryUrls() as $url) {
            if ($url !== '' && ! in_array($url, $photos, true)) {
                $photos[] = $url;
            }
        }

        $ptSlotPrice = $settings->publicPtSlotCustomerPrice($listing->ptPricingTierKey());
        $stripeReady = $settings->isStripeConfiguredForPayments();
        $bookingBootstrap = [
            'gymTitle' => $listing->name,
            'blockedUrl' => route('gym.bookings.blocked', ['slug' => $listing->slug]),
            'quoteUrl' => route('gym.bookings.quote', ['slug' => $listing->slug]),
            'storeUrl' => route('gym.bookings.store', ['slug' => $listing->slug]),
            'paymentIntentUrl' => $stripeReady ? route('gym.bookings.payment-intent', ['slug' => $listing->slug]) : '',
            'confirmPaymentUrl' => $stripeReady ? route('gym.bookings.confirm-payment', ['slug' => $listing->slug]) : '',
            'stripePublishableKey' => $stripeReady ? (string) ($settings->stripePublishableKey() ?? '') : '',
            'csrf' => csrf_token(),
            'availability' => is_array($listing->availability_schedule) ? $listing->availability_schedule : [],
            'personalTrainingAvailable' => (bool) $listing->personal_training_available,
            'personalTrainingAvailability' => is_array($listing->personal_training_availability) ? $listing->personal_training_availability : [],
            'rate1hr' => $pricing['rate_1hr'] ?? 0,
            'ptSlotPrice' => $ptSlotPrice,
            'ptAddonEnabled' => (bool) $listing->personal_training_available && $ptSlotPrice > 0,
            'offers1hr' => $slotOffers['offers_1hr'],
            'termsUrl' => (string) ($settings->legal_booking_terms_url ?? ''),
            'privacyUrl' => (string) ($settings->legal_booking_privacy_url ?? ''),
            'listingPersonLimit' => $listing->person_limit !== null ? (int) $listing->person_limit : null,
            'ptIconUrl' => asset('images/ryj/personal-training.svg'),
            'isSubscriber' => Auth::user() instanceof User && Auth::user()->hasRole(UserRole::Subscriber->value),
            'userEmail' => Auth::user() instanceof User ? (string) Auth::user()->email : '',
        ];

        /** @var User|null $authUser */
        $authUser = Auth::user();

        // Review aggregates + list (table may not exist yet in dev if migrations
        // haven't run — guard so the page keeps working either way).
        $reviewsTableExists = Schema::hasTable('gym_reviews');
        $reviewsQuery = $reviewsTableExists
            ? GymReview::query()
                ->with('user:id,name')
                ->where('gym_listing_id', $listing->id)
                ->whereNotNull('approved_at')
                ->latest()
            : null;

        $reviews = $reviewsQuery ? $reviewsQuery->get() : collect();
        $reviewsCount = $reviews->count();
        $reviewsAvg = $reviewsCount > 0 ? round((float) $reviews->avg('rating'), 1) : 0.0;

        $isSubscriber = $authUser instanceof User && $authUser->hasRole(UserRole::Subscriber->value);
        $hasBookedHere = $listing->hasBookingByUser($authUser);
        $canReview = $authUser instanceof User && $isSubscriber && $hasBookedHere;

        $userExistingReview = null;
        if ($reviewsTableExists && $authUser instanceof User) {
            $userExistingReview = GymReview::query()
                ->where('gym_listing_id', $listing->id)
                ->where('user_id', $authUser->id)
                ->first();
        }

        return view('web.find-a-gym.gym-main-page', [
            'listing' => $listing,
            'photos' => $photos,
            'slotOffers' => $slotOffers,
            'pricing' => $pricing,
            'stateLabel' => config('gym_listing.states.'.strtoupper((string) $listing->state), (string) $listing->state),
            'bookingBootstrap' => $bookingBootstrap,
            'reviews' => $reviews,
            'reviewsCount' => $reviewsCount,
            'reviewsAvg' => $reviewsAvg,
            'isLoggedIn' => $authUser instanceof User,
            'isSubscriber' => $isSubscriber,
            'hasBookedHere' => $hasBookedHere,
            'canReview' => $canReview,
            'userExistingReview' => $userExistingReview,
        ]);
    }
}
