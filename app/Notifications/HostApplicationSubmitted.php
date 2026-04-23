<?php

namespace App\Notifications;

use App\Models\HostApplication;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostApplicationSubmitted extends Notification
{
    public function __construct(public HostApplication $application) {}

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
            'title' => __('New host application'),
            'body' => __(':name — :email', [
                'name' => $this->application->full_name,
                'email' => $this->application->email,
            ]),
            'border' => 'primary',
            'application_id' => $this->application->id,
            'url' => route('admin.host-applications.show', $this->application),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $application = $this->application->fresh() ?? $this->application;

        $vars = [
            'APPLICANT_NAME' => $application->full_name,
            'APPLICANT_EMAIL' => $application->email,
            'APPLICANT_PHONE' => $application->phone,
            'APPLICATION_ID' => (string) $application->id,
            'ADMIN_APPLICATION_URL' => url(route('admin.host-applications.show', $application, false)),
        ];

        $subject = SiteEmailTemplateService::resolvedSubject(
            SiteEmailTemplateService::KEY_HOST_APPLICATION_SUBMITTED_ADMIN,
            $vars,
            __('[:app] New host application: :name', [
                'app' => config('app.name'),
                'name' => $application->full_name,
            ])
        );

        $html = SiteEmailTemplateService::resolvedHtmlOrNull(
            SiteEmailTemplateService::KEY_HOST_APPLICATION_SUBMITTED_ADMIN,
            $vars
        );

        if ($html !== null) {
            return (new MailMessage)
                ->subject($subject)
                ->view('mail.raw-custom-html', ['html' => $html]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->view('mail.host-application-submitted', ['application' => $application]);
    }
}
