<?php

namespace App\Mail;

use App\Models\GymBooking;
use App\Models\GymListing;
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
            subject: __('New gym booking: :gym', ['gym' => $this->gymListing->name]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.gym-booking-created-admin',
        );
    }
}
