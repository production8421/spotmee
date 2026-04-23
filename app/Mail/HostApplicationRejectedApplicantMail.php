<?php

namespace App\Mail;

use App\Models\HostApplication;
use App\Models\User;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HostApplicationRejectedApplicantMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HostApplication $application,
        public User $rejectedBy,
        public ?string $rejectionMessage,
    ) {}

    public function envelope(): Envelope
    {
        $vars = self::vars($this->application, $this->rejectedBy, $this->rejectionMessage);

        return new Envelope(
            subject: SiteEmailTemplateService::resolvedSubject(
                SiteEmailTemplateService::KEY_HOST_APPLICATION_REJECTED_APPLICANT,
                $vars,
                __('Update on your host application — :app', ['app' => config('app.name')])
            ),
        );
    }

    public function content(): Content
    {
        $vars = self::vars($this->application, $this->rejectedBy, $this->rejectionMessage);
        $html = SiteEmailTemplateService::resolvedHtmlOrNull(
            SiteEmailTemplateService::KEY_HOST_APPLICATION_REJECTED_APPLICANT,
            $vars
        );
        if ($html !== null) {
            return new Content(htmlString: $html);
        }

        return new Content(
            view: 'mail.host-application-rejected-applicant',
        );
    }

    /**
     * @return array<string, string>
     */
    private static function vars(HostApplication $application, User $rejectedBy, ?string $message): array
    {
        return [
            'APPLICANT_NAME' => $application->full_name,
            'APPLICANT_EMAIL' => $application->email,
            'APPLICATION_ID' => (string) $application->id,
            'ADMIN_NAME' => $rejectedBy->name,
            'REJECTION_MESSAGE' => $message ?? '—',
        ];
    }
}
