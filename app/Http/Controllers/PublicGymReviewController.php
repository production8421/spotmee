<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Web\StoreGymReviewRequest;
use App\Models\GymListing;
use App\Models\GymReview;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PublicGymReviewController extends Controller
{
    /**
     * Store (or update) a subscriber's review for a gym listing.
     * Access is gated: logged-in Subscriber who has a confirmed/completed booking at this gym.
     */
    public function store(StoreGymReviewRequest $request, string $slug): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user instanceof User) {
            throw ValidationException::withMessages([
                'comment' => __('Please sign in to post a review.'),
            ]);
        }

        if (! $user->hasRole(UserRole::Subscriber->value)) {
            throw ValidationException::withMessages([
                'comment' => __('Only subscribers can post reviews.'),
            ]);
        }

        $listing = GymListing::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $listing->hasBookingByUser($user)) {
            throw ValidationException::withMessages([
                'comment' => __('You can only review a gym after you have booked it.'),
            ]);
        }

        GymReview::query()->updateOrCreate(
            [
                'gym_listing_id' => $listing->id,
                'user_id' => $user->id,
            ],
            [
                'rating' => (int) $request->validated('rating'),
                'comment' => trim((string) $request->validated('comment')),
                'approved_at' => now(),
            ]
        );

        return redirect()
            ->route('gym.show', ['slug' => $listing->slug])
            ->withFragment('gym-reviews')
            ->with('status', __('Thanks! Your review has been posted.'));
    }
}
