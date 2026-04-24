<?php

namespace App\Notifications;

use App\Models\GymListing;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymListingUnapproved extends Notification
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
            'title' => __('Gym listing approval revoked'),
            'body' => __('Your listing ":name" is unpublished and needs administrator approval again.', ['name' => $listing->name]),
            'border' => 'warning',
            'gym_listing_id' => $listing->id,
            'url' => route('host.gym-listings.show', $listing),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $listing = $this->listing->fresh() ?? $this->listing;

        return (new MailMessage)
            ->subject(__('[:app] Gym listing approval revoked: :name', [
                'app' => config('app.name'),
                'name' => $listing->name,
            ]))
            ->view('mail.notifications.gym-listing-unapproved', [
                'recipientName' => $notifiable->name,
                'listing' => $listing,
                'listingUrl' => route('host.gym-listings.show', $listing, true),
            ]);
    }
}
