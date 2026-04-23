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

class HostApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $hostUser,
        public HostApplication $application,
        public string $plainPassword,
        public ?User $approvedBy,
    ) {}

    public function envelope(): Envelope
    {
        $vars = self::vars($this->hostUser, $this->application, $this->approvedBy, $this->plainPassword);

        return new Envelope(
            subject: SiteEmailTemplateService::resolvedSubject(
                SiteEmailTemplateService::KEY_HOST_APPLICATION_APPROVED_APPLICANT,
                $vars,
                __('Your host application was approved — :app', ['app' => config('app.name')])
            ),
        );
    }

    public function content(): Content
    {
        $vars = self::vars($this->hostUser, $this->application, $this->approvedBy, $this->plainPassword);
        $html = SiteEmailTemplateService::resolvedHtmlOrNull(
            SiteEmailTemplateService::KEY_HOST_APPLICATION_APPROVED_APPLICANT,
            $vars
        );
        if ($html !== null) {
            return new Content(htmlString: $html);
        }

        return new Content(
            markdown: 'mail.host-approved',
        );
    }

    /**
     * @return array<string, string>
     */
    private static function vars(User $hostUser, HostApplication $application, ?User $approvedBy, string $plainPassword): array
    {
        $adminLabel = $approvedBy !== null
            ? $approvedBy->name
            : (string) __('Automatic approval (site settings)');

        return [
            'HOST_NAME' => $hostUser->name,
            'HOST_EMAIL' => $hostUser->email,
            'TEMP_PASSWORD' => $plainPassword,
            'ADMIN_NAME' => $adminLabel,
            'APPLICATION_ID' => (string) $application->id,
            'LOGIN_URL' => url(route('login', [], false)),
        ];
    }
}
