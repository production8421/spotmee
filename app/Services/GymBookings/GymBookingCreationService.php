<?php

namespace App\Services\GymBookings;

use App\Enums\UserRole;
use App\Mail\GymBookingCreatedForAdminMail;
use App\Mail\GymBookingCreatedForGuestMail;
use App\Mail\GymBookingCreatedForHostMail;
use App\Models\ApplicationSetting;
use App\Models\Coupon;
use App\Models\GymBooking;
use App\Models\GymListing;
use App\Models\User;
use App\Services\GymListing\BookingWebhookDispatcher;
use App\Support\RyjGymSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class GymBookingCreationService
{
    public function __construct(
        private readonly BookingWebhookDispatcher $bookingWebhooks,
    ) {}

    /**
     * Validate booking payload and compute price (no database writes).
     *
     * @param  array<string, mixed>  $validated  Same shape as {@see StorePublicGymBookingRequest}
     * @return array{
     *   date: Carbon,
     *   slot_duration: int,
     *   time_slots: list<string>,
     *   number_of_persons: int,
     *   trainer_per_slot_filtered: array<string, bool>,
     *   trainer_slot_count: int,
     *   pt_free_trial: bool,
     *   pt_free_trial_slot: ?string,
     *   total_price: float,
     *   start_time: string,
     *   end_time: string,
     *   guest_email: string,
     *   guest_name: string,
     *   base_price: float,
     *   trainer_fee: float,
     *   full_gym_base_before_coupon: float,
     *   coupon_id: ?int,
     *   coupon_code: ?string,
     *   coupon_discount: float,
     *   coupon_applied_slots: int,
     * }
     */
    public function resolvePublicBookingQuote(GymListing $listing, array $validated): array
    {
        $listing->loadMissing('user');

        $date = Carbon::parse($validated['booking_date'])->startOfDay();
        $slotDuration = (int) $validated['slot_duration_minutes'];
        if (! in_array($slotDuration, [40, 60], true)) {
            throw ValidationException::withMessages(['slot_duration_minutes' => __('Invalid slot length.')]);
        }

        $timeSlots = array_values(array_unique(array_map(
            fn (mixed $s) => $this->normalizeSlotValue((string) $s),
            $validated['time_slots']
        )));
        $this->assertSlotsValidForListing($listing, $date, $slotDuration, $timeSlots);

        $numberOfPersons = max(1, (int) $validated['number_of_persons']);
        $this->assertCapacityAvailable($listing, $date, $timeSlots, $numberOfPersons);

        $guestEmail = $this->effectiveGuestEmailForBooking($validated);
        $guestName = trim((string) ($validated['guest_name'] ?? ''));

        $trainerPerSlot = [];
        if (is_array($validated['trainer_per_slot'] ?? null)) {
            foreach ($validated['trainer_per_slot'] as $k => $v) {
                $trainerPerSlot[$this->normalizeSlotValue((string) $k)] = $v;
            }
        }
        $ptAddon = (string) ($validated['pt_addon'] ?? 'none');
        $ptFreeTrialSlot = isset($validated['pt_free_trial_slot']) ? (string) $validated['pt_free_trial_slot'] : null;

        $ptFreeTrial = $ptAddon === 'free_trial' && $ptFreeTrialSlot !== '' && $ptFreeTrialSlot !== null;
        if ($ptFreeTrial) {
            if ($guestEmail === '') {
                throw ValidationException::withMessages([
                    'guest_email' => __('Please enter your email to use a free personal training trial.'),
                ]);
            }
            $this->assertPtFreeTrialAllowed($listing, $guestEmail, $ptFreeTrialSlot, $timeSlots);
        }

        $trainerPerSlotFiltered = $this->filterTrainerSelections(
            $listing,
            $date,
            $trainerPerSlot,
            $timeSlots,
            $ptFreeTrial,
            $ptFreeTrialSlot
        );

        $trainerSlotCount = count(array_filter($trainerPerSlotFiltered, static fn ($v) => $v === true || $v === 1 || $v === '1'));

        [$startTime, $endTime] = $this->boundsFromSlots($timeSlots);

        if ($ptFreeTrial && $trainerSlotCount !== 1) {
            throw ValidationException::withMessages(['pt_free_trial_slot' => __('Free trial applies to exactly one personal training slot.')]);
        }

        $tier = $listing->hostTierKey();
        $settings = ApplicationSetting::instance();
        $rates = $settings->publicGuestTierRates($tier);
        $ptPrice = $settings->publicPtSlotCustomerPrice($listing->ptPricingTierKey());

        $slotCount = count($timeSlots);
        $fullBreakdown = $this->computePriceBreakdown(
            $slotCount,
            $slotDuration,
            $numberOfPersons,
            (float) ($rates['rate_40min'] ?? 0),
            (float) ($rates['rate_1hr'] ?? 0),
            $trainerSlotCount,
            $ptPrice,
            $ptFreeTrial && $trainerSlotCount > 0
        );
        /** Gym slot revenue for all selected slots × persons (before any coupon). */
        $fullGymBaseBeforeCoupon = $fullBreakdown['base'];
        $base = $fullBreakdown['base'];
        $trainerFee = $fullBreakdown['trainer_fee'];

        $couponId = null;
        $couponCodeApplied = null;
        $couponDiscount = 0.0;
        $couponAppliedSlots = 0;
        $slotFreeSessionsCoupon = false;
        $couponCodeRaw = trim((string) ($validated['coupon_code'] ?? ''));
        if ($couponCodeRaw !== '') {
            if ($guestEmail === '' && ! $this->isAuthenticatedSubscriber()) {
                throw ValidationException::withMessages([
                    'guest_email' => __('Enter your email address before applying this coupon.'),
                ]);
            }
            $codeNorm = Coupon::normalizeCode($couponCodeRaw);
            $coupon = Coupon::query()->where('code', $codeNorm)->first();
            if ($coupon === null) {
                throw ValidationException::withMessages(['coupon_code' => __('Invalid coupon code.')]);
            }
            $this->assertCouponValidForBooking(
                $listing,
                $coupon,
                $fullBreakdown['base'],
                $trainerFee
            );

            if ($coupon->percent_discount_enabled) {
                $subtotal = round($fullBreakdown['base'] + $trainerFee, 2);
                $pct = (float) ($coupon->percent_discount ?? 0);
                $couponDiscount = round(min($subtotal, $subtotal * ($pct / 100)), 2);
                $base = $fullBreakdown['base'];
                $couponAppliedSlots = 0;
                $couponId = $coupon->id;
                $couponCodeApplied = $coupon->code;
            } else {
                $slotFreeSessionsCoupon = true;
                $usedSlots = $this->sumCouponAppliedSlotsForIdentity(
                    $coupon->id,
                    $guestEmail,
                    $this->authenticatedSubscriberUserId()
                );
                $remaining = (int) $coupon->valid_sessions - $usedSlots;
                if ($remaining <= 0) {
                    throw ValidationException::withMessages([
                        'coupon_code' => __('This coupon has no remaining free slots for you.'),
                    ]);
                }

                $freeSlotsThisBooking = min($remaining, $slotCount);
                $paidSlots = $slotCount - $freeSlotsThisBooking;
                $paidBreakdown = $this->computePriceBreakdown(
                    $paidSlots,
                    $slotDuration,
                    $numberOfPersons,
                    (float) ($rates['rate_40min'] ?? 0),
                    (float) ($rates['rate_1hr'] ?? 0),
                    $trainerSlotCount,
                    $ptPrice,
                    $ptFreeTrial && $trainerSlotCount > 0
                );
                $base = $paidBreakdown['base'];
                $couponDiscount = round(max(0.0, $fullBreakdown['base'] - $paidBreakdown['base']), 2);
                $couponAppliedSlots = $freeSlotsThisBooking;
                $couponId = $coupon->id;
                $couponCodeApplied = $coupon->code;
            }
        }

        // Free-slot coupons: base_price is already the post-coupon gym charge; do not subtract coupon_discount again.
        $totalPrice = $slotFreeSessionsCoupon
            ? round(max(0.0, $base + $trainerFee), 2)
            : round(max(0.0, $base + $trainerFee - $couponDiscount), 2);

        return [
            'date' => $date,
            'slot_duration' => $slotDuration,
            'time_slots' => $timeSlots,
            'number_of_persons' => $numberOfPersons,
            'trainer_per_slot_filtered' => $trainerPerSlotFiltered,
            'trainer_slot_count' => $trainerSlotCount,
            'pt_free_trial' => $ptFreeTrial,
            'pt_free_trial_slot' => $ptFreeTrial ? $ptFreeTrialSlot : null,
            'base_price' => $base,
            'trainer_fee' => $trainerFee,
            'full_gym_base_before_coupon' => $fullGymBaseBeforeCoupon,
            'coupon_id' => $couponId,
            'coupon_code' => $couponCodeApplied,
            'coupon_discount' => $couponDiscount,
            'coupon_applied_slots' => $couponAppliedSlots,
            'total_price' => $totalPrice,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'guest_email' => $guestEmail,
            'guest_name' => $guestName,
        ];
    }

    /**
     * @param  array<string, mixed>  $validated  From StorePublicGymBookingRequest
     * @return array{booking: GymBooking, user: User, temporary_password: ?string}
     */
    public function createFromPublicRequest(GymListing $listing, array $validated, ?string $stripePaymentIntentId = null): array
    {
        $q = $this->resolvePublicBookingQuote($listing, $validated);

        $result = DB::transaction(function () use ($listing, $validated, $q, $stripePaymentIntentId) {
            $guestEmail = $q['guest_email'];
            $guestName = $q['guest_name'];
            $date = $q['date'];
            $startTime = $q['start_time'];
            $endTime = $q['end_time'];
            $numberOfPersons = $q['number_of_persons'];
            $trainerPerSlotFiltered = $q['trainer_per_slot_filtered'];
            $trainerSlotCount = $q['trainer_slot_count'];
            $ptFreeTrial = $q['pt_free_trial'];
            $ptFreeTrialSlot = $q['pt_free_trial_slot'];
            $totalPrice = $q['total_price'];
            $couponId = $q['coupon_id'] ?? null;
            $couponDiscount = isset($q['coupon_discount']) ? (float) $q['coupon_discount'] : 0.0;
            $couponAppliedSlots = (int) ($q['coupon_applied_slots'] ?? 0);

            if ($couponId !== null) {
                $couponRow = Coupon::query()->whereKey($couponId)->lockForUpdate()->first();
                if ($couponRow === null || ! $couponRow->is_active) {
                    throw ValidationException::withMessages([
                        'coupon_code' => __('This coupon is no longer valid. Complete payment without it or try another code.'),
                    ]);
                }
                if (! $couponRow->percent_discount_enabled && $couponAppliedSlots > 0) {
                    $usedNow = $this->sumCouponAppliedSlotsForIdentity(
                        (int) $couponRow->id,
                        strtolower(trim($guestEmail)),
                        $this->authenticatedSubscriberUserId()
                    );
                    $remainingNow = (int) $couponRow->valid_sessions - $usedNow;
                    if ($couponAppliedSlots > $remainingNow) {
                        throw ValidationException::withMessages([
                            'coupon_code' => __('This coupon is no longer available for that many free slots. Refresh and try again.'),
                        ]);
                    }
                }
            }

            [$user, $temporaryPassword] = $this->resolveOrCreateGuestUser($guestEmail, $guestName);

            $slotDurationMinutes = (int) $validated['slot_duration_minutes'];
            $slotCount = count($q['time_slots']);
            $durationHours = round($slotCount * ($slotDurationMinutes / 60), 2);

            $booking = GymBooking::query()->create([
                'gym_listing_id' => $listing->id,
                'user_id' => $user->id,
                'booking_date' => $date->format('Y-m-d'),
                'start_time' => Carbon::parse($startTime)->format('H:i:s'),
                'end_time' => Carbon::parse($endTime)->format('H:i:s'),
                'time_slots' => $q['time_slots'],
                'duration_hours' => $durationHours,
                'number_of_persons' => $numberOfPersons,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_phone' => filled($validated['guest_phone'] ?? null) ? trim((string) $validated['guest_phone']) : null,
                'notes' => filled($validated['notes'] ?? null) ? trim((string) $validated['notes']) : null,
                'personal_trainer_requested' => $trainerSlotCount > 0,
                'trainer_per_slot' => $trainerPerSlotFiltered === [] ? null : $trainerPerSlotFiltered,
                'trainer_slot_count' => $trainerSlotCount,
                'pt_free_trial' => $ptFreeTrial,
                'pt_free_trial_slot' => $ptFreeTrial ? $ptFreeTrialSlot : null,
                'total_price' => $totalPrice,
                'currency' => 'USD',
                'status' => 'confirmed',
                'confirmation_code' => GymBooking::generateConfirmationCode(),
                'stripe_payment_intent_id' => $stripePaymentIntentId,
                'coupon_id' => $couponId,
                'coupon_discount' => $couponDiscount > 0 ? round($couponDiscount, 2) : null,
                'coupon_applied_slots' => $couponId !== null && $couponAppliedSlots > 0 ? $couponAppliedSlots : null,
            ]);

            $this->sendNotifications($listing, $booking, $temporaryPassword);

            return [
                'booking' => $booking,
                'user' => $user,
                'temporary_password' => $temporaryPassword,
            ];
        });

        $booking = $result['booking'];
        $booking->loadMissing('gymListing');
        $listingForWebhook = $booking->gymListing ?? $listing;
        if ($listingForWebhook instanceof GymListing) {
            try {
                $this->bookingWebhooks->dispatchBookingCompletedForBooking(
                    $booking,
                    $listingForWebhook,
                );
            } catch (\Throwable $e) {
                Log::warning('booking_completed_webhook_failed', [
                    'booking_id' => $booking->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $result;
    }

    /**
     * @return list{array{date: string, start: string, end: string, number_of_persons: int}}
     */
    public function blockedIntervalsForListing(GymListing $listing, Carbon $from, Carbon $to): array
    {
        $rows = GymBooking::query()
            ->where('gym_listing_id', $listing->id)
            ->whereBetween('booking_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->where('status', 'confirmed')
            ->get(['booking_date', 'start_time', 'end_time', 'time_slots', 'number_of_persons']);

        $out = [];
        foreach ($rows as $row) {
            $dateStr = $row->booking_date instanceof \DateTimeInterface
                ? $row->booking_date->format('Y-m-d')
                : (string) $row->booking_date;
            foreach ($this->bookingOccupiedIntervalsForModel($row) as [$s, $e]) {
                $out[] = [
                    'date' => $dateStr,
                    'start' => $s,
                    'end' => $e,
                    'number_of_persons' => (int) $row->number_of_persons,
                ];
            }
        }

        return $out;
    }

    private function formatTimeForApi(mixed $t): string
    {
        if ($t instanceof \DateTimeInterface) {
            return Carbon::instance($t)->format('H:i');
        }

        return Carbon::parse((string) $t)->format('H:i');
    }

    /**
     * @param  list<string>  $timeSlots
     */
    private function assertSlotsValidForListing(GymListing $listing, Carbon $date, int $slotDuration, array $timeSlots): void
    {
        $dayKey = strtolower($date->format('l'));
        $daySchedule = $this->dayAvailabilityRow($listing, $dayKey);
        if ($daySchedule === null || filter_var($daySchedule['isClosed'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            throw ValidationException::withMessages(['booking_date' => __('This gym is closed on the selected day.')]);
        }

        $allowedDurations = $this->allowedSlotDurationsForDay($daySchedule);
        if (! in_array($slotDuration, $allowedDurations, true)) {
            throw ValidationException::withMessages(['slot_duration_minutes' => __('This slot length is not offered on the selected day.')]);
        }

        $validSet = $this->buildValidSlotSet($daySchedule, $slotDuration);
        $normalizedSelected = array_map($this->normalizeSlotValue(...), $timeSlots);
        foreach ($normalizedSelected as $slot) {
            if (! isset($validSet[$slot])) {
                throw ValidationException::withMessages(['time_slots' => __('One or more time slots are invalid for this date.')]);
            }
            $parts = explode('|', $slot, 2);
            if (count($parts) === 2) {
                $durMin = $this->minutesFromMidnight($parts[1]) - $this->minutesFromMidnight($parts[0]);
                if ($durMin !== $slotDuration) {
                    throw ValidationException::withMessages(['time_slots' => __('Each slot must match the selected session length.')]);
                }
            }
        }

    }

    /**
     * @param  list<string>  $timeSlots  normalized HH:mm|HH:mm
     */
    private function assertCapacityAvailable(GymListing $listing, Carbon $date, array $timeSlots, int $requestedPersons): void
    {
        $dayKey = strtolower($date->format('l'));
        $daySchedule = $this->dayAvailabilityRow($listing, $dayKey);
        $personLimit = $listing->effectivePersonLimit($daySchedule);
        $dateStr = $date->format('Y-m-d');

        foreach ($timeSlots as $slot) {
            $parts = explode('|', $slot, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $startStr = $this->formatTimeForApi($parts[0]);
            $endStr = $this->formatTimeForApi($parts[1]);
            $already = $this->sumPersonsOverlapping($listing->id, $dateStr, $startStr, $endStr);
            $spotsLeft = max(0, $personLimit - $already);
            if ($requestedPersons > $spotsLeft) {
                throw ValidationException::withMessages([
                    'number_of_persons' => __('Not enough capacity left for one or more selected slots (:spots spot(s) left in the tightest window).', ['spots' => $spotsLeft]),
                ]);
            }
        }
    }

    private function sumPersonsOverlapping(int $listingId, string $date, string $start, string $end): int
    {
        $bookings = GymBooking::query()
            ->where('gym_listing_id', $listingId)
            ->whereDate('booking_date', $date)
            ->where('status', 'confirmed')
            ->get(['start_time', 'end_time', 'time_slots', 'number_of_persons']);

        $sum = 0;
        foreach ($bookings as $b) {
            foreach ($this->bookingOccupiedIntervalsForModel($b) as [$s, $e]) {
                if ($this->timeRangesOverlap($start, $end, $s, $e)) {
                    $sum += max(1, (int) $b->number_of_persons);
                    break;
                }
            }
        }

        return $sum;
    }

    /**
     * @return list<array{0: string, 1: string}> [start, end] as H:i
     */
    private function bookingOccupiedIntervalsForModel(GymBooking $booking): array
    {
        $raw = $booking->time_slots;
        if (is_array($raw) && $raw !== []) {
            $out = [];
            foreach ($raw as $slot) {
                if (! is_string($slot)) {
                    continue;
                }
                $norm = $this->normalizeSlotValue($slot);
                $parts = explode('|', $norm, 2);
                if (count($parts) !== 2) {
                    continue;
                }
                $out[] = [$this->formatTimeForApi($parts[0]), $this->formatTimeForApi($parts[1])];
            }
            if ($out !== []) {
                return $out;
            }
        }

        return [[$this->formatTimeForApi($booking->start_time), $this->formatTimeForApi($booking->end_time)]];
    }

    private function timeRangesOverlap(string $s1, string $e1, string $s2, string $e2): bool
    {
        $a = $this->normalizeTimeStr($s1);
        $b = $this->normalizeTimeStr($e1);
        $c = $this->normalizeTimeStr($s2);
        $d = $this->normalizeTimeStr($e2);

        return $a < $d && $b > $c;
    }

    private function normalizeTimeStr(string $t): string
    {
        return Carbon::parse($t)->format('H:i');
    }

    /**
     * @param  array<string, mixed>  $daySchedule
     * @return list<int>
     */
    private function allowedSlotDurationsForDay(array $daySchedule): array
    {
        $durs = $daySchedule['slotDuration'] ?? ['60'];
        if (! is_array($durs)) {
            $durs = [$durs];
        }
        $out = [];
        foreach (array_map('strval', $durs) as $d) {
            $n = (int) $d;
            if (in_array($n, [40, 60], true)) {
                $out[] = $n;
            }
        }

        return $out !== [] ? array_values(array_unique($out)) : [60];
    }

    /**
     * @return array<string, true> keys normalized "HH:mm|HH:mm"
     */
    private function buildValidSlotSet(array $daySchedule, int $slotMinutes): array
    {
        $start = $daySchedule['startTime'] ?? null;
        $end = $daySchedule['endTime'] ?? null;
        if (! is_string($start) || ! is_string($end) || $start === '' || $end === '') {
            return [];
        }
        $startM = $this->minutesFromMidnight($start);
        $endM = $this->minutesFromMidnight($end);
        if ($endM <= $startM) {
            return [];
        }

        $set = [];
        for ($cur = $startM; $cur + $slotMinutes <= $endM; $cur += $slotMinutes) {
            $a = $this->minutesToHHmm($cur);
            $b = $this->minutesToHHmm($cur + $slotMinutes);
            $key = $this->normalizeSlotValue($a.'|'.$b);
            $set[$key] = true;
        }

        return $set;
    }

    private function minutesFromMidnight(string $hms): int
    {
        $p = explode(':', trim($hms));
        $h = (int) ($p[0] ?? 0);
        $m = (int) ($p[1] ?? 0);

        return $h * 60 + $m;
    }

    private function minutesToHHmm(int $m): string
    {
        $h = intdiv($m, 60);
        $mm = $m % 60;

        return sprintf('%02d:%02d', $h, $mm);
    }

    /**
     * @param  list<string>  $normalizedSlots
     * @return array{0: string, 1: string}
     */
    private function boundsFromSlots(array $normalizedSlots): array
    {
        $slots = $normalizedSlots;
        usort($slots, fn (string $x, string $y) => strcmp(explode('|', $x, 2)[0], explode('|', $y, 2)[0]));
        $first = explode('|', $slots[0], 2);
        $last = explode('|', $slots[array_key_last($slots)], 2);

        return [$first[0], $last[1]];
    }

    public function normalizeSlotValue(string $slotStr): string
    {
        $parts = explode('|', $slotStr, 2);
        if (count($parts) < 2) {
            return '';
        }

        return $this->normalizeTimeStr($parts[0]).'|'.$this->normalizeTimeStr($parts[1]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function dayAvailabilityRow(GymListing $listing, string $dayKey): ?array
    {
        $schedule = is_array($listing->availability_schedule) ? $listing->availability_schedule : [];

        return is_array($schedule[$dayKey] ?? null) ? $schedule[$dayKey] : null;
    }

    /**
     * @param  array<string, bool|int|string>  $trainerPerSlot
     * @param  list<string>  $timeSlots  normalized
     * @return array<string, bool>
     */
    private function filterTrainerSelections(
        GymListing $listing,
        Carbon $date,
        array $trainerPerSlot,
        array $timeSlots,
        bool $ptFreeTrial,
        ?string $ptFreeTrialSlot
    ): array {
        if ($ptFreeTrial) {
            $norm = $this->normalizeSlotValue((string) ($ptFreeTrialSlot ?? ''));
            if ($norm === '' || ! in_array($norm, $timeSlots, true)) {
                throw ValidationException::withMessages(['pt_free_trial_slot' => __('Invalid free trial slot.')]);
            }
            if (! $this->isSlotPtAvailable($listing, $date, $norm)) {
                throw ValidationException::withMessages(['pt_free_trial_slot' => __('Personal training is not available for this slot.')]);
            }

            return [$norm => true];
        }

        $out = [];
        foreach ($timeSlots as $slot) {
            $raw = $trainerPerSlot[$slot] ?? false;
            $want = $raw === true || $raw === 1 || $raw === '1';
            if (! $want) {
                continue;
            }
            if (! $this->isSlotPtAvailable($listing, $date, $slot)) {
                throw ValidationException::withMessages(['trainer_per_slot' => __('Personal training is not available for one of the selected slots.')]);
            }
            $out[$slot] = true;
        }

        return $out;
    }

    private function isSlotPtAvailable(GymListing $listing, Carbon $date, string $normalizedSlot): bool
    {
        if (! $listing->personal_training_available) {
            return false;
        }
        $dayKey = strtolower($date->format('l'));
        $gymRow = $this->dayAvailabilityRow($listing, $dayKey);
        if (! RyjGymSchedule::gymAvailabilityAllowsOneHourPt($gymRow)) {
            return false;
        }
        $pt = is_array($listing->personal_training_availability) ? $listing->personal_training_availability : [];
        $row = is_array($pt[$dayKey] ?? null) ? $pt[$dayKey] : null;
        if ($row === null) {
            return false;
        }
        if (filter_var($row['isClosed'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }
        $slots = $row['timeSlots'] ?? [];
        if (! is_array($slots) || $slots === []) {
            return false;
        }
        foreach ($slots as $s) {
            if (! is_string($s)) {
                continue;
            }
            if (RyjGymSchedule::filterPtTimeSlotsToOneHour([$s]) === []) {
                continue;
            }
            $ptNorm = $this->normalizeSlotValue($s);
            if ($this->guestBookingSlotWithinPtOneHourWindow($normalizedSlot, $ptNorm)) {
                return true;
            }
        }

        return false;
    }

    /**
     * True when the guest booking segment (40 or 60 minutes) lies entirely inside a configured one-hour PT window.
     */
    private function guestBookingSlotWithinPtOneHourWindow(string $normalizedGuestSlot, string $normalizedPtSlot): bool
    {
        $g = $this->slotStartEndMinutes($normalizedGuestSlot);
        $p = $this->slotStartEndMinutes($normalizedPtSlot);
        if ($g === null || $p === null) {
            return false;
        }
        if ($p['end'] <= $p['start'] || $g['end'] <= $g['start']) {
            return false;
        }
        if (($p['end'] - $p['start']) !== 60) {
            return false;
        }

        return $g['start'] >= $p['start'] && $g['end'] <= $p['end'];
    }

    /**
     * @return array{start: int, end: int}|null
     */
    private function slotStartEndMinutes(string $normalizedSlot): ?array
    {
        $parts = explode('|', $normalizedSlot, 2);
        if (count($parts) < 2) {
            return null;
        }

        return [
            'start' => $this->timeHmToMinutes($parts[0]),
            'end' => $this->timeHmToMinutes($parts[1]),
        ];
    }

    private function timeHmToMinutes(string $hm): int
    {
        $parts = explode(':', trim($hm));
        $h = (int) ($parts[0] ?? 0);
        $m = (int) ($parts[1] ?? 0);

        return $h * 60 + $m;
    }

    /**
     * @param  list<string>  $timeSlots
     */
    private function assertPtFreeTrialAllowed(GymListing $listing, string $guestEmail, string $ptFreeTrialSlot, array $timeSlots): void
    {
        $norm = $this->normalizeSlotValue($ptFreeTrialSlot);
        if ($norm === '' || ! in_array($norm, array_map($this->normalizeSlotValue(...), $timeSlots), true)) {
            throw ValidationException::withMessages(['pt_free_trial_slot' => __('Invalid free trial slot.')]);
        }

        $emailLower = strtolower($guestEmail);
        $taken = GymBooking::query()
            ->where('gym_listing_id', $listing->id)
            ->where('status', 'confirmed')
            ->where('pt_free_trial', true)
            ->where(function ($q) use ($emailLower): void {
                $q->whereHas('user', function ($uq) use ($emailLower): void {
                    $uq->whereRaw('LOWER(email) = ?', [$emailLower]);
                })->orWhereRaw('LOWER(guest_email) = ?', [$emailLower]);
            })
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages(['pt_addon' => __('You have already used a free personal training trial at this gym.')]);
        }
    }

    /**
     * @return array{User, ?string}
     */
    private function resolveOrCreateGuestUser(string $email, string $name): array
    {
        $existing = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
        if ($existing) {
            return [$existing, null];
        }

        $plain = Str::password(14);
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($plain),
        ]);
        $user->assignRole(UserRole::Subscriber->value);

        return [$user, $plain];
    }

    /**
     * @return array{base: float, trainer_fee: float, subtotal: float}
     */
    private function computePriceBreakdown(
        int $numberOfSlots,
        int $slotDuration,
        int $persons,
        float $rate40,
        float $rate1hr,
        int $trainerSlotCount,
        float $ptSlotPrice,
        bool $ptFreeTrialApplies
    ): array {
        $perSlot = $slotDuration === 40 ? $rate40 : $rate1hr;
        $base = round($numberOfSlots * $perSlot * $persons, 2);
        $trainerFee = ($trainerSlotCount > 0 && ! $ptFreeTrialApplies)
            ? round($trainerSlotCount * $ptSlotPrice, 2)
            : 0.0;

        return [
            'base' => $base,
            'trainer_fee' => $trainerFee,
            'subtotal' => round($base + $trainerFee, 2),
        ];
    }

    private function assertCouponValidForBooking(
        GymListing $listing,
        Coupon $coupon,
        float $base,
        float $trainerFee
    ): void {
        if (! $coupon->is_active) {
            throw ValidationException::withMessages(['coupon_code' => __('This coupon is not active.')]);
        }

        if (! $coupon->appliesToListing($listing)) {
            throw ValidationException::withMessages(['coupon_code' => __('This coupon does not apply to this gym.')]);
        }

        if ($base + $trainerFee <= 0.0) {
            throw ValidationException::withMessages(['coupon_code' => __('This booking has no charge to apply a coupon to.')]);
        }

        if ($coupon->percent_discount_enabled) {
            $pct = (float) ($coupon->percent_discount ?? 0);
            if ($pct <= 0.0 || $pct > 100.0) {
                throw ValidationException::withMessages(['coupon_code' => __('This coupon percentage discount is not configured.')]);
            }

            return;
        }

        if ((int) $coupon->valid_sessions < 1) {
            throw ValidationException::withMessages(['coupon_code' => __('This coupon is not configured for any sessions.')]);
        }
    }

    /**
     * Guest email from the request, or the logged-in subscriber’s account email when the field is left blank.
     */
    private function effectiveGuestEmailForBooking(array $validated): string
    {
        $raw = strtolower(trim((string) ($validated['guest_email'] ?? '')));
        if ($raw !== '') {
            return $raw;
        }
        $user = Auth::user();
        if ($user instanceof User && $user->hasRole(UserRole::Subscriber->value)) {
            return strtolower(trim((string) $user->email));
        }

        return '';
    }

    private function isAuthenticatedSubscriber(): bool
    {
        return $this->authenticatedSubscriberUserId() !== null;
    }

    private function authenticatedSubscriberUserId(): ?int
    {
        $user = Auth::user();
        if ($user instanceof User && $user->hasRole(UserRole::Subscriber->value)) {
            return (int) $user->id;
        }

        return null;
    }

    /**
     * Sum of free time slots already redeemed with this coupon (confirmed bookings only).
     * Subscribers are tracked by {@see GymBooking::$user_id}; guests by {@see GymBooking::$guest_email}.
     */
    private function sumCouponAppliedSlotsForIdentity(int $couponId, string $guestEmailLower, ?int $subscriberUserId): int
    {
        $q = GymBooking::query()
            ->where('coupon_id', $couponId)
            ->where('status', 'confirmed');

        if ($subscriberUserId !== null) {
            $q->where('user_id', $subscriberUserId);
        } else {
            if ($guestEmailLower === '') {
                return 0;
            }
            $q->whereRaw('LOWER(guest_email) = ?', [$guestEmailLower]);
        }

        return (int) $q->sum('coupon_applied_slots');
    }

    /**
     * @param  ?string  $guestNewAccountPlainPassword  Non-null only when a new Subscriber user was created for this booking email.
     */
    private function sendNotifications(GymListing $listing, GymBooking $booking, ?string $guestNewAccountPlainPassword): void
    {
        $createdGuestAccount = $guestNewAccountPlainPassword !== null;

        $adminEmails = User::query()
            ->role(UserRole::Administrator->value)
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        foreach ($adminEmails as $adminEmail) {
            try {
                Mail::to($adminEmail)->send(new GymBookingCreatedForAdminMail($listing, $booking, $createdGuestAccount));
            } catch (\Throwable $e) {
                Log::error('Gym booking admin mail failed', [
                    'to' => $adminEmail,
                    'booking_id' => $booking->id,
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $hostEmail = $listing->user?->email;
        if ($hostEmail === null || $hostEmail === '') {
            $hostEmail = $listing->email;
        }
        if (is_string($hostEmail) && $hostEmail !== '') {
            try {
                Mail::to($hostEmail)->send(new GymBookingCreatedForHostMail($listing, $booking));
            } catch (\Throwable $e) {
                Log::error('Gym booking host mail failed', [
                    'to' => $hostEmail,
                    'booking_id' => $booking->id,
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $guestEmail = strtolower(trim((string) $booking->guest_email));
        if ($guestEmail !== '') {
            try {
                Mail::to($guestEmail)->send(new GymBookingCreatedForGuestMail($listing, $booking, $guestNewAccountPlainPassword));
            } catch (\Throwable $e) {
                Log::error('Gym booking guest mail failed', [
                    'to' => $guestEmail,
                    'booking_id' => $booking->id,
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
