<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageSubmitted extends Notification
{
    /**
     * @param  array{name: string, email: string, phone?: ?string, company?: ?string, message: string}  $payload
     */
    public function __construct(private readonly array $payload) {}

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
        return [
            'title' => __('New contact form message'),
            'body' => __(':name — :email', [
                'name' => $this->payload['name'],
                'email' => $this->payload['email'],
            ]),
            'border' => 'primary',
            'url' => route('admin.users.index'),
            'contact_name' => $this->payload['name'],
            'contact_email' => $this->payload['email'],
            'contact_phone' => $this->payload['phone'] ?? null,
            'contact_company' => $this->payload['company'] ?? null,
            'contact_message' => $this->payload['message'],
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('[:app] New contact message from :name', [
                'app' => config('app.name'),
                'name' => $this->payload['name'],
            ]))
            ->greeting(__('New contact form submission'))
            ->line(__('Name: :name', ['name' => $this->payload['name']]))
            ->line(__('Email: :email', ['email' => $this->payload['email']]))
            ->line(__('Phone: :phone', ['phone' => $this->payload['phone'] ?: '—']))
            ->line(__('Company: :company', ['company' => $this->payload['company'] ?: '—']))
            ->line(__('Message:'))
            ->line((string) $this->payload['message']);
    }
}
