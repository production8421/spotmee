<?php

namespace App\Notifications;

use App\Models\GymListing;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymListingPendingApproval extends Notification
{
    public function __construct(public GymListing $listing) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
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

    public function toMail(object $notifiable): MailMessage
    {
        $listing = $this->listing->fresh() ?? $this->listing;
        $hostName = $listing->user?->name ?? __('Host');

        return (new MailMessage)
            ->subject(__('[:app] Gym listing pending approval: :name', [
                'app' => config('app.name'),
                'name' => $listing->name,
            ]))
            ->line(__('A host (:host) submitted a new gym listing for review.', ['host' => $hostName]))
            ->line(__('Listing: :name — :city', ['name' => $listing->name, 'city' => $listing->city]))
            ->action(__('Review listing'), route('admin.gym-listings.show', $listing));
    }
}
