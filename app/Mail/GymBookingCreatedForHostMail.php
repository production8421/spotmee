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

class GymBookingCreatedForHostMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymListing $gymListing,
        public GymBooking $booking,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: GymBookingNotificationTemplateService::resolvedSubject(
                'host',
                $this->gymListing,
                $this->booking,
                false
            ),
        );
    }

    public function content(): Content
    {
        $html = GymBookingNotificationTemplateService::resolvedHtmlOrNull(
            'host',
            $this->gymListing,
            $this->booking,
            false
        );
        if ($html !== null) {
            return new Content(htmlString: $html);
        }

        return new Content(
            view: 'mail.gym-booking-created-host',
        );
    }
}
