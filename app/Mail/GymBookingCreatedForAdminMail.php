<?php

namespace App\Mail;

use App\Models\GymBooking;
use App\Models\GymListing;
use App\Services\Mail\GymBookingNotificationTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymBookingCreatedForAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymListing $gymListing,
        public GymBooking $booking,
        public bool $createdGuestAccount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: GymBookingNotificationTemplateService::resolvedSubject(
                'admin',
                $this->gymListing,
                $this->booking,
                $this->createdGuestAccount
            ),
        );
    }

    public function content(): Content
    {
        $html = GymBookingNotificationTemplateService::resolvedHtmlOrNull(
            'admin',
            $this->gymListing,
            $this->booking,
            $this->createdGuestAccount
        );
        if ($html !== null) {
            return new Content(htmlString: $html);
        }

        return new Content(
            view: 'mail.gym-booking-created-admin',
        );
    }
}
