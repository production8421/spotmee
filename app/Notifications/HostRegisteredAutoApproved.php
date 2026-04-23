<?php

namespace App\Notifications;

use App\Models\HostApplication;
use Illuminate\Notifications\Notification;

/**
 * Database notification for administrators when a host is auto-approved at registration.
 */
class HostRegisteredAutoApproved extends Notification
{
    public function __construct(public HostApplication $application) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $application = $this->application->fresh() ?? $this->application;

        return [
            'title' => __('Host registered (automatic approval)'),
            'body' => __(':name — :email', [
                'name' => $application->full_name,
                'email' => $application->email,
            ]),
            'border' => 'success',
            'application_id' => $application->id,
            'url' => route('admin.host-applications.show', $application),
        ];
    }
}
