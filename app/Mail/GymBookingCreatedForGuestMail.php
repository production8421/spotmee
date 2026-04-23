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

class GymBookingCreatedForGuestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymListing $gymListing,
        public GymBooking $booking,
        public ?string $guestNewAccountPlainPassword = null,
    ) {}

    public function envelope(): Envelope
    {
        $createdAccount = $this->guestNewAccountPlainPassword !== null && $this->guestNewAccountPlainPassword !== '';

        return new Envelope(
            subject: GymBookingNotificationTemplateService::resolvedSubject(
                'guest',
                $this->gymListing,
                $this->booking,
                $createdAccount,
                $this->guestNewAccountPlainPassword,
            ),
        );
    }

    public function content(): Content
    {
        $createdAccount = $this->guestNewAccountPlainPassword !== null && $this->guestNewAccountPlainPassword !== '';

        $html = GymBookingNotificationTemplateService::resolvedHtmlOrNull(
            'guest',
            $this->gymListing,
            $this->booking,
            $createdAccount,
            $this->guestNewAccountPlainPassword,
        );
        if ($html !== null) {
            return new Content(htmlString: $html);
        }

        return new Content(
            view: 'mail.gym-booking-created-guest',
        );
    }
}
