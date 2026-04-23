<?php

namespace App\Services\Mail;

use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

final class SmtpConfigFromApplicationSettings
{
    /**
     * When application settings enable custom SMTP, override Laravel mail config for this request/process.
     * Otherwise keep values from config/mail.php and the environment.
     */
    public static function apply(): void
    {
        if (! Schema::hasTable('application_settings')) {
            return;
        }

        $row = ApplicationSetting::query()->first();
        if ($row === null || ! $row->smtp_enabled) {
            return;
        }

        $host = trim((string) ($row->smtp_host ?? ''));
        if ($host === '') {
            return;
        }

        $port = (int) ($row->smtp_port ?? 0);
        if ($port < 1 || $port > 65535) {
            return;
        }

        $encryption = strtolower((string) ($row->smtp_encryption ?? 'tls'));
        if (! in_array($encryption, ['tls', 'ssl', 'none'], true)) {
            $encryption = 'tls';
        }

        $username = trim((string) ($row->smtp_username ?? ''));
        $password = $row->smtp_password;
        $passwordStr = is_string($password) ? $password : '';

        $fromAddress = trim((string) ($row->smtp_from_address ?? ''));
        $fromName = trim((string) ($row->smtp_from_name ?? ''));

        Config::set('mail.default', 'smtp');

        $smtp = array_merge(Config::get('mail.mailers.smtp', []), [
            'transport' => 'smtp',
            'host' => $host,
            'port' => $port,
            'username' => $username !== '' ? $username : null,
            'password' => $passwordStr !== '' ? $passwordStr : null,
        ]);

        if ($encryption === 'ssl') {
            $smtp['scheme'] = 'smtps';
            unset($smtp['auto_tls']);
        } elseif ($encryption === 'none') {
            $smtp['scheme'] = 'smtp';
            $smtp['auto_tls'] = false;
        } else {
            $smtp['scheme'] = null;
            unset($smtp['auto_tls']);
        }

        Config::set('mail.mailers.smtp', $smtp);

        if ($fromAddress !== '' && filter_var($fromAddress, FILTER_VALIDATE_EMAIL) !== false) {
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName !== '' ? $fromName : config('app.name', 'Laravel'));
        }
    }
}
