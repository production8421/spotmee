<?php

namespace App\Services\Mail;

use App\Models\GymBooking;
use App\Models\GymListing;
use Illuminate\Support\Carbon;
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
        $booking->loadMissing('coupon');

        $date = $booking->booking_date instanceof \DateTimeInterface
            ? $booking->booking_date->format('Y-m-d')
            : (string) $booking->booking_date;
        $start = Str::substr((string) $booking->start_time, 0, 5);
        $end = Str::substr((string) $booking->end_time, 0, 5);
        $timeRange = $start.' – '.$end;
        $dateLong = $date !== ''
            ? Carbon::parse($date)->timezone(config('app.timezone'))->translatedFormat('l, F j, Y')
            : '—';
        $durationHours = $booking->duration_hours !== null
            ? (string) round((float) $booking->duration_hours, 2).' '.__('hours')
            : '—';
        $currency = strtoupper(trim((string) ($booking->currency ?? 'USD'))) ?: 'USD';
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

        $addressLine = trim((string) ($listing->address ?? ''));
        $cityStateZip = trim(collect([
            $listing->city,
            strtoupper((string) ($listing->state ?? '')),
            $listing->postal_code,
        ])->filter()->implode(', '));
        $gymAddressBlock = trim($addressLine."\n".$cityStateZip);
        if ($gymAddressBlock === '') {
            $gymAddressBlock = '—';
        }
        $listingPhone = trim((string) ($listing->phone ?? ''));
        $listingEmail = trim((string) ($listing->email ?? ''));

        $couponLine = '—';
        if ($booking->coupon_id !== null && $booking->coupon !== null) {
            $code = (string) $booking->coupon->code;
            $couponLine = $code;
            if ($booking->coupon_discount !== null && (float) $booking->coupon_discount > 0) {
                $couponLine .= ' — '.__('Discount').': $'.number_format((float) $booking->coupon_discount, 2);
            }
            if ($booking->coupon_applied_slots !== null && (int) $booking->coupon_applied_slots > 0) {
                $couponLine .= ' ('.trans_choice(
                    '{1} :count free slot applied|[2,*] :count free slots applied',
                    (int) $booking->coupon_applied_slots,
                    ['count' => (int) $booking->coupon_applied_slots]
                ).')';
            }
        }

        $ptFreeTrialLine = $booking->pt_free_trial
            ? (string) __('Yes').($booking->pt_free_trial_slot ? ' ('.$booking->pt_free_trial_slot.')' : '')
            : (string) __('No');

        $trainerSlotsDetail = '—';
        if (is_array($booking->trainer_per_slot) && $booking->trainer_per_slot !== []) {
            $parts = [];
            foreach ($booking->trainer_per_slot as $slotKey => $val) {
                $parts[] = (string) $slotKey.': '.(filter_var($val, FILTER_VALIDATE_BOOLEAN) ? __('Yes') : __('No'));
            }
            $trainerSlotsDetail = implode('; ', $parts);
        }

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
            'APP_BRAND' => 'SPOTMEE',
            'GYM_NAME' => $listing->name,
            'GUEST_NAME' => (string) $booking->guest_name,
            'GUEST_EMAIL' => (string) $booking->guest_email,
            'GUEST_USERNAME' => (string) $booking->guest_email,
            'GUEST_PHONE' => $guestPhone !== '' ? $guestPhone : '—',
            'BOOKING_DATE' => $date,
            'BOOKING_DATE_LONG' => $dateLong,
            'TIME_RANGE' => $timeRange,
            'START_TIME' => $start,
            'END_TIME' => $end,
            'DURATION_HOURS' => $durationHours,
            'BOOKING_STATUS' => (string) ($booking->status ?? '—'),
            'CURRENCY' => $currency,
            'PERSONS' => (string) $booking->number_of_persons,
            'CONFIRMATION_CODE' => (string) $booking->confirmation_code,
            'TOTAL_PRICE' => $total,
            'BOOKING_ID' => (string) $booking->id,
            'LISTING_ID' => (string) $listing->id,
            'NOTES' => $notes !== '' ? $notes : '—',
            'TRAINER_SUMMARY' => $trainer,
            'PT_FREE_TRIAL' => $ptFreeTrialLine,
            'TRAINER_PER_SLOT_DETAIL' => $trainerSlotsDetail,
            'GYM_ADDRESS_BLOCK' => $gymAddressBlock,
            'LISTING_PHONE' => $listingPhone !== '' ? $listingPhone : '—',
            'LISTING_EMAIL' => $listingEmail !== '' ? $listingEmail : '—',
            'COUPON_LINE' => $couponLine,
            'CREATED_ACCOUNT_NOTE' => $createdNote,
            'GUEST_TEMP_PASSWORD' => $guestTempPassword,
            'NEW_SUBSCRIBER_LOGIN_NOTE' => $subscriberNote,
            'LOGIN_URL' => url(route('login', [], false)),
            'PUBLIC_GYM_URL' => url(route('gym.show', ['slug' => $listing->slug], false)),
            'ADMIN_BOOKINGS_URL' => url(route('admin.gym-bookings.index', [], false)),
            'AUDIENCE' => $audience,
            'CANCEL_BOOKING_URL' => $cancelUrl,
            'CANCELLATION_POLICY_URL' => url(route('legal.cancellation-policy', [], false)),
        ];
    }
}
