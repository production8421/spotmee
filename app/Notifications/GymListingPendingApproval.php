<?php

namespace App\Notifications;

use App\Models\GymListing;
use Illuminate\Notifications\Notification;

/**
 * In-app notice for administrators (plugin has no email when a gym is submitted for review).
 */
class GymListingPendingApproval extends Notification
{
    public function __construct(public GymListing $listing) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $listing = $this->listing->fresh() ?? $this->listing;

        return [
            'title' => __('Gym listing pending approval'),
            'body' => __(':name — :city', [
                'name' => $listing->name,
                'city' => $listing->city,
            ]),
            'border' => 'warning',
            'gym_listing_id' => $listing->id,
            'url' => route('admin.gym-listings.show', $listing),
        ];
    }
}
