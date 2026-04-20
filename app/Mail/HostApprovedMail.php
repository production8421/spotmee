<?php

namespace App\Mail;

use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HostApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $hostUser,
        public HostApplication $application,
        public string $plainPassword,
        public User $approvedBy,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Your host application was approved — :app', ['app' => config('app.name')]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.host-approved',
        );
    }
}
