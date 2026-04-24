<?php

namespace App\Services\Mail;

use App\Models\ApplicationSetting;

/**
 * Custom HTML / subject overrides for site emails (configured under Admin → Settings).
 *
 * Placeholders in stored templates use [[KEY]] syntax; values are HTML-escaped when substituted.
 */
final class SiteEmailTemplateService
{
    public const KEY_HOST_APPLICATION_SUBMITTED_ADMIN = 'host_application_submitted_admin';

    public const KEY_HOST_APPLICATION_APPROVED_APPLICANT = 'host_application_approved_applicant';

    public const KEY_HOST_APPLICATION_REJECTED_APPLICANT = 'host_application_rejected_applicant';

    public const KEY_GYM_LISTING_APPROVED_HOST = 'gym_listing_approved_host';

    public const KEY_GYM_LISTING_REJECTED_HOST = 'gym_listing_rejected_host';

    public const KEY_GYM_BOOKING_ADMIN = 'gym_booking_admin';

    public const KEY_GYM_BOOKING_HOST = 'gym_booking_host';

    public const KEY_GYM_BOOKING_GUEST = 'gym_booking_guest';

    /**
     * @return list<string>
     */
    public static function allTemplateKeys(): array
    {
        return [
            self::KEY_HOST_APPLICATION_SUBMITTED_ADMIN,
            self::KEY_HOST_APPLICATION_APPROVED_APPLICANT,
            self::KEY_HOST_APPLICATION_REJECTED_APPLICANT,
            self::KEY_GYM_LISTING_APPROVED_HOST,
            self::KEY_GYM_LISTING_REJECTED_HOST,
            self::KEY_GYM_BOOKING_ADMIN,
            self::KEY_GYM_BOOKING_HOST,
            self::KEY_GYM_BOOKING_GUEST,
        ];
    }

    /**
     * Labels and help text for Admin → Settings (subject / HTML overrides).
     *
     * @return array{title: string, when: string, placeholders: string}
     */
    public static function adminUiMeta(string $key): array
    {
        return match ($key) {
            self::KEY_HOST_APPLICATION_SUBMITTED_ADMIN => [
                'title' => __('Administrators: new host application'),
                'when' => __('Sent to every administrator when a new host application is submitted.'),
                'placeholders' => '[[APPLICANT_NAME]] [[APPLICANT_EMAIL]] [[APPLICANT_PHONE]] [[APPLICATION_ID]] [[ADMIN_APPLICATION_URL]] [[APP_NAME]]',
            ],
            self::KEY_HOST_APPLICATION_APPROVED_APPLICANT => [
                'title' => __('Applicant: host application approved'),
                'when' => __('Sent to the new host when an administrator approves their application (includes login details).'),
                'placeholders' => '[[HOST_NAME]] [[HOST_EMAIL]] [[TEMP_PASSWORD]] [[ADMIN_NAME]] [[APPLICATION_ID]] [[LOGIN_URL]] [[APP_NAME]]',
            ],
            self::KEY_HOST_APPLICATION_REJECTED_APPLICANT => [
                'title' => __('Applicant: host application not approved'),
                'when' => __('Sent to the applicant when an administrator rejects their host application.'),
                'placeholders' => '[[APPLICANT_NAME]] [[APPLICANT_EMAIL]] [[APPLICATION_ID]] [[ADMIN_NAME]] [[REJECTION_MESSAGE]] [[APP_NAME]]',
            ],
            self::KEY_GYM_LISTING_APPROVED_HOST => [
                'title' => __('Host: gym listing approved'),
                'when' => __('Sent to the listing owner when an administrator approves and publishes their gym.'),
                'placeholders' => '[[RECIPIENT_NAME]] [[GYM_NAME]] [[GYM_CITY]] [[HOST_LISTING_URL]] [[APP_NAME]]',
            ],
            self::KEY_GYM_LISTING_REJECTED_HOST => [
                'title' => __('Host: gym listing not approved'),
                'when' => __('Sent to the listing owner when an administrator does not approve their gym listing.'),
                'placeholders' => '[[RECIPIENT_NAME]] [[GYM_NAME]] [[GYM_CITY]] [[REJECTION_MESSAGE]] [[HOST_EDIT_LISTING_URL]] [[APP_NAME]]',
            ],
            self::KEY_GYM_BOOKING_ADMIN => [
                'title' => __('Administrators: new gym booking'),
                'when' => __('Sent to every administrator when a guest booking is successfully created (after payment or free checkout).'),
                'placeholders' => '[[APP_NAME]] [[GYM_NAME]] [[GUEST_NAME]] [[GUEST_EMAIL]] [[GUEST_PHONE]] [[BOOKING_DATE]] [[START_TIME]] [[END_TIME]] [[PERSONS]] [[CONFIRMATION_CODE]] [[TOTAL_PRICE]] [[BOOKING_ID]] [[LISTING_ID]] [[NOTES]] [[TRAINER_SUMMARY]] [[PUBLIC_GYM_URL]] [[ADMIN_BOOKINGS_URL]] [[CREATED_ACCOUNT_NOTE]]',
            ],
            self::KEY_GYM_BOOKING_HOST => [
                'title' => __('Host: new booking for your gym'),
                'when' => __('Sent to the gym host (or listing contact email) when a booking is successfully created.'),
                'placeholders' => '[[APP_NAME]] [[GYM_NAME]] [[GUEST_NAME]] [[GUEST_EMAIL]] [[GUEST_PHONE]] [[BOOKING_DATE]] [[START_TIME]] [[END_TIME]] [[PERSONS]] [[CONFIRMATION_CODE]] [[TOTAL_PRICE]] [[BOOKING_ID]] [[LISTING_ID]] [[NOTES]] [[TRAINER_SUMMARY]] [[PUBLIC_GYM_URL]] [[ADMIN_BOOKINGS_URL]] [[CREATED_ACCOUNT_NOTE]]',
            ],
            self::KEY_GYM_BOOKING_GUEST => [
                'title' => __('Guest: booking confirmation'),
                'when' => __('Sent to the email the guest used for the booking. If that email was new to the site, a Subscriber account is created and login details are included.'),
                'placeholders' => '[[APP_NAME]] [[GYM_NAME]] [[GUEST_NAME]] [[GUEST_EMAIL]] [[GUEST_USERNAME]] [[GUEST_TEMP_PASSWORD]] [[NEW_SUBSCRIBER_LOGIN_NOTE]] [[LOGIN_URL]] [[GUEST_PHONE]] [[BOOKING_DATE]] [[START_TIME]] [[END_TIME]] [[PERSONS]] [[CONFIRMATION_CODE]] [[TOTAL_PRICE]] [[BOOKING_ID]] [[LISTING_ID]] [[NOTES]] [[TRAINER_SUMMARY]] [[PUBLIC_GYM_URL]] [[ADMIN_BOOKINGS_URL]] [[CREATED_ACCOUNT_NOTE]] [[CANCEL_BOOKING_URL]] [[CANCELLATION_POLICY_URL]]',
            ],
            default => [
                'title' => $key,
                'when' => '',
                'placeholders' => '[[APP_NAME]]',
            ],
        };
    }

    public static function defaultSubject(string $key): string
    {
        return match ($key) {
            self::KEY_HOST_APPLICATION_SUBMITTED_ADMIN => __('[:app] New host application', ['app' => config('app.name')]),
            self::KEY_HOST_APPLICATION_APPROVED_APPLICANT => __('Your host application was approved — :app', ['app' => config('app.name')]),
            self::KEY_HOST_APPLICATION_REJECTED_APPLICANT => __('Update on your host application — :app', ['app' => config('app.name')]),
            self::KEY_GYM_LISTING_APPROVED_HOST => __('[:app] Your gym listing was approved', ['app' => config('app.name')]),
            self::KEY_GYM_LISTING_REJECTED_HOST => __('[:app] Gym listing not approved', ['app' => config('app.name')]),
            self::KEY_GYM_BOOKING_ADMIN => __('New gym booking: :app', ['app' => config('app.name')]),
            self::KEY_GYM_BOOKING_HOST => __('New booking — :app', ['app' => config('app.name')]),
            self::KEY_GYM_BOOKING_GUEST => __('Your booking is confirmed — :app', ['app' => config('app.name')]),
            default => __('Notification from :app', ['app' => config('app.name')]),
        };
    }

    /**
     * @param  array<string, string>  $vars  Keys without brackets; replaced as [[KEY]] uppercased in template text.
     */
    public static function resolvedSubject(string $templateKey, array $vars, ?string $fallbackSubject = null): string
    {
        $slot = self::slot($templateKey);
        $raw = trim((string) ($slot['subject'] ?? ''));
        $base = $raw !== '' ? $raw : ($fallbackSubject ?? self::defaultSubject($templateKey));

        return self::replacePlaceholders($base, $vars);
    }

    /**
     * @param  array<string, string>  $vars
     */
    public static function resolvedHtmlOrNull(string $templateKey, array $vars): ?string
    {
        $slot = self::slot($templateKey);
        $body = trim((string) ($slot['body_html'] ?? ''));
        if ($body === '') {
            return null;
        }

        return self::sanitizeBasicHtml(self::replacePlaceholders($body, $vars));
    }

    /**
     * @return array{subject: string, body_html: string}
     */
    public static function slot(string $templateKey): array
    {
        $all = ApplicationSetting::instance()->notificationEmailTemplatesNormalized();

        return $all[$templateKey] ?? ['subject' => '', 'body_html' => ''];
    }

    /**
     * @param  array<string, string>  $vars
     */
    public static function replacePlaceholders(string $text, array $vars): string
    {
        $search = [];
        $replace = [];
        foreach ($vars as $k => $v) {
            $search[] = '[['.strtoupper((string) $k).']]';
            $replace[] = e((string) $v);
        }
        $search[] = '[[APP_NAME]]';
        $replace[] = e((string) config('app.name'));

        return str_replace($search, $replace, $text);
    }

    private static function sanitizeBasicHtml(string $html): string
    {
        $out = preg_replace('#<\s*script\b[^>]*>.*?</\s*script\s*>#is', '', $html);

        return is_string($out) ? $out : $html;
    }
}
