<?php

namespace App\Notifications;

use App\Models\GymListing;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymListingRejected extends Notification
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
        $msg = is_string($listing->rejection_message) ? trim($listing->rejection_message) : '';
        $msg = $msg !== '' ? $msg : null;

        $body = $msg !== null
            ? __('Your listing ":name" was not approved. Message: :message', [
                'name' => $listing->name,
                'message' => $msg,
            ])
            : __('Your listing ":name" was not approved. You can update it and save again to resubmit for review.', [
                'name' => $listing->name,
            ]);

        return [
            'title' => __('Gym listing not approved'),
            'body' => $body,
            'border' => 'danger',
            'gym_listing_id' => $listing->id,
            'rejection_message' => $msg,
            'url' => route('host.gym-listings.edit', $listing),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $listing = $this->listing->fresh() ?? $this->listing;
        $msg = is_string($listing->rejection_message) ? trim($listing->rejection_message) : '';
        $msg = $msg !== '' ? $msg : null;

        $vars = [
            'RECIPIENT_NAME' => $notifiable->name,
            'GYM_NAME' => $listing->name,
            'GYM_CITY' => (string) $listing->city,
            'REJECTION_MESSAGE' => $msg ?? '—',
            'HOST_EDIT_LISTING_URL' => url(route('host.gym-listings.edit', $listing, false)),
        ];

        $subject = SiteEmailTemplateService::resolvedSubject(
            SiteEmailTemplateService::KEY_GYM_LISTING_REJECTED_HOST,
            $vars,
            __('[:app] Gym listing not approved: :name', [
                'app' => config('app.name'),
                'name' => $listing->name,
            ])
        );

        $html = SiteEmailTemplateService::resolvedHtmlOrNull(
            SiteEmailTemplateService::KEY_GYM_LISTING_REJECTED_HOST,
            $vars
        );

        if ($html !== null) {
            return (new MailMessage)
                ->subject($subject)
                ->view('mail.raw-custom-html', ['html' => $html]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->view('mail.notifications.gym-listing-rejected', [
                'recipientName' => $notifiable->name,
                'listing' => $listing,
                'rejectionMessage' => $msg,
                'editListingUrl' => route('host.gym-listings.edit', $listing, true),
            ]);
    }
}
