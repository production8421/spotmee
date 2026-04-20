<?php

namespace App\Notifications;

use App\Models\GymListing;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymListingApproved extends Notification
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
            'title' => __('Gym listing approved'),
            'body' => __('Your listing ":name" has been approved and published.', ['name' => $listing->name]),
            'border' => 'success',
            'gym_listing_id' => $listing->id,
            'url' => route('host.gym-listings.show', $listing),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $listing = $this->listing->fresh() ?? $this->listing;

        return (new MailMessage)
            ->subject(__('[:app] Your gym listing was approved: :name', [
                'app' => config('app.name'),
                'name' => $listing->name,
            ]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('Great news — an administrator has approved and published your gym listing on :app.', [
                'app' => config('app.name'),
            ]))
            ->line(__('Listing: :name — :city', ['name' => $listing->name, 'city' => $listing->city]))
            ->action(__('View your listing'), route('host.gym-listings.show', $listing))
            ->line(__('Thanks for using :app.', ['app' => config('app.name')]));
    }
}
