<?php

namespace App\Services\Mail;

use App\Models\GymBooking;
use App\Models\GymListing;
use Illuminate\Support\Str;

final class GymBookingNotificationTemplateService
{
    /**
     * @param  'admin'|'host'|'guest'  $audience
     */
    public static function resolvedSubject(string $audience, GymListing $listing, GymBooking $booking, bool $createdGuestAccount = false, ?string $guestNewAccountPlainPassword = null): string
    {
        $key = self::keyForAudience($audience);
        $fallback = match ($audience) {
            'admin' => __('New gym booking: :gym', ['gym' => $listing->name]),
            'host' => __('New booking — :gym', ['gym' => $listing->name]),
            'guest' => __('Your booking is confirmed — :gym', ['gym' => $listing->name]),
            default => __('Booking notification'),
        };

        return SiteEmailTemplateService::resolvedSubject($key, self::bookingVarMap($listing, $booking, $createdGuestAccount, $audience, $guestNewAccountPlainPassword), $fallback);
    }

    /**
     * @param  'admin'|'host'|'guest'  $audience
     */
    public static function resolvedHtmlOrNull(string $audience, GymListing $listing, GymBooking $booking, bool $createdGuestAccount = false, ?string $guestNewAccountPlainPassword = null): ?string
    {
        return SiteEmailTemplateService::resolvedHtmlOrNull(
            self::keyForAudience($audience),
            self::bookingVarMap($listing, $booking, $createdGuestAccount, $audience, $guestNewAccountPlainPassword)
        );
    }

    /**
     * @param  'admin'|'host'|'guest'  $audience
     */
    private static function keyForAudience(string $audience): string
    {
        return match ($audience) {
            'admin' => SiteEmailTemplateService::KEY_GYM_BOOKING_ADMIN,
            'host' => SiteEmailTemplateService::KEY_GYM_BOOKING_HOST,
            'guest' => SiteEmailTemplateService::KEY_GYM_BOOKING_GUEST,
            default => SiteEmailTemplateService::KEY_GYM_BOOKING_GUEST,
        };
    }

    /**
     * @param  'admin'|'host'|'guest'  $audience
     * @param  ?string  $guestNewAccountPlainPassword  Only for guest audience: one-time password when a new Subscriber was created.
     * @return array<string, string>
     */
    private static function bookingVarMap(GymListing $listing, GymBooking $booking, bool $createdGuestAccount, string $audience, ?string $guestNewAccountPlainPassword = null): array
    {
        $date = $booking->booking_date instanceof \DateTimeInterface
            ? $booking->booking_date->format('Y-m-d')
            : (string) $booking->booking_date;
        $start = Str::substr((string) $booking->start_time, 0, 5);
        $end = Str::substr((string) $booking->end_time, 0, 5);
        $total = $booking->total_price !== null
            ? '$'.number_format((float) $booking->total_price, 2)
            : '—';
        $trainer = $booking->personal_trainer_requested
            ? __('Yes').' ('.$booking->trainer_slot_count.' '.__('slot(s)').')'
            : __('No');
        $notes = trim((string) ($booking->notes ?? ''));
        $guestPhone = trim((string) ($booking->guest_phone ?? ''));
        $createdNote = $createdGuestAccount
            ? (string) __('A new subscriber account was created for this guest email.')
            : '';

        $cancelUrl = '';
        if ($audience === 'guest' && $booking->status === 'confirmed' && $booking->isCancellable()) {
            $cancelUrl = $booking->signedCancelUrl();
        }

        $showNewSubscriberCreds = $audience === 'guest'
            && $guestNewAccountPlainPassword !== null
            && $guestNewAccountPlainPassword !== '';

        $subscriberNote = '';
        $guestTempPassword = '';
        if ($showNewSubscriberCreds) {
            $guestTempPassword = $guestNewAccountPlainPassword;
            $subscriberNote = (string) __('We created a new subscriber account for you. Sign in with your login email and temporary password below, then change your password from your profile.');
        }

        return [
            'GYM_NAME' => $listing->name,
            'GUEST_NAME' => (string) $booking->guest_name,
            'GUEST_EMAIL' => (string) $booking->guest_email,
            'GUEST_USERNAME' => (string) $booking->guest_email,
            'GUEST_PHONE' => $guestPhone !== '' ? $guestPhone : '—',
            'BOOKING_DATE' => $date,
            'START_TIME' => $start,
            'END_TIME' => $end,
            'PERSONS' => (string) $booking->number_of_persons,
            'CONFIRMATION_CODE' => (string) $booking->confirmation_code,
            'TOTAL_PRICE' => $total,
            'BOOKING_ID' => (string) $booking->id,
            'LISTING_ID' => (string) $listing->id,
            'NOTES' => $notes !== '' ? $notes : '—',
            'TRAINER_SUMMARY' => $trainer,
            'CREATED_ACCOUNT_NOTE' => $createdNote,
            'GUEST_TEMP_PASSWORD' => $guestTempPassword,
            'NEW_SUBSCRIBER_LOGIN_NOTE' => $subscriberNote,
            'LOGIN_URL' => url(route('login', [], false)),
            'PUBLIC_GYM_URL' => url(route('gym.show', ['slug' => $listing->slug], false)),
            'ADMIN_BOOKINGS_URL' => url(route('admin.gym-bookings.index', [], false)),
            'AUDIENCE' => $audience,
            'CANCEL_BOOKING_URL' => $cancelUrl,
        ];
    }
}
