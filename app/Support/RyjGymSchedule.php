<?php

namespace App\Support;

class RyjGymSchedule
{
    /**
     * @param  array<string, mixed>  $rawByDay
     * @return array<string, array<string, mixed>>
     */
    public static function normalizeGymFormToStorage(array $rawByDay): array
    {
        $out = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $r = is_array($rawByDay[$day] ?? null) ? $rawByDay[$day] : [];
            $isClosed = filter_var($r['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $slots = $r['slot_duration'] ?? [];
            if (! is_array($slots)) {
                $slots = [];
            }
            $slots = array_values(array_intersect(array_map('strval', $slots), ['60']));
            if ($slots === []) {
                $slots = ['60'];
            }
            $out[$day] = [
                'isClosed' => $isClosed,
                'startTime' => $isClosed ? null : self::emptyToNull($r['start_time'] ?? null),
                'endTime' => $isClosed ? null : self::emptyToNull($r['end_time'] ?? null),
                'slotDuration' => $slots,
                'personLimit' => max(1, (int) ($r['person_limit'] ?? 1)),
            ];
        }

        return $out;
    }

    /**
     * Whether gym availability for a day allows one-hour bookings (PT is always 1 hour).
     *
     * @param  array<string, mixed>|null  $gymRow  Normalized availability row (isClosed, slotDuration, …)
     */
    public static function gymAvailabilityAllowsOneHourPt(?array $gymRow): bool
    {
        if (! is_array($gymRow)) {
            return false;
        }
        if (filter_var($gymRow['isClosed'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }
        $durs = $gymRow['slotDuration'] ?? [];
        if (! is_array($durs)) {
            $durs = $durs !== null && $durs !== '' ? [(string) $durs] : [];
        }

        return in_array('60', array_map('strval', $durs), true);
    }

    /**
     * @param  array<string, mixed>  $ptByDay
     * @param  array<string, array<int, string>>  $timeSlotsByDay
     * @param  array<string, mixed>  $gymByDay  Normalized availability_schedule (same shape as storage)
     * @return array<string, array<string, mixed>>
     */
    public static function normalizePtFormToStorage(array $ptByDay, array $timeSlotsByDay, array $gymByDay = []): array
    {
        $out = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $r = is_array($ptByDay[$day] ?? null) ? $ptByDay[$day] : [];
            $isClosed = filter_var($r['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $gymRow = is_array($gymByDay[$day] ?? null) ? $gymByDay[$day] : null;
            $ts = is_array($timeSlotsByDay[$day] ?? null) ? $timeSlotsByDay[$day] : [];
            $ts = array_values(array_filter(
                $ts,
                fn ($v) => is_string($v) && self::ptSlotIsOneHourString($v)
            ));
            if (! self::gymAvailabilityAllowsOneHourPt($gymRow)) {
                $ts = [];
            }
            $startTime = null;
            $endTime = null;
            if ($ts !== []) {
                $parts = explode('|', $ts[0], 2);
                $startTime = $parts[0] ?? null;
                $endTime = $parts[1] ?? null;
            }
            $out[$day] = [
                'isClosed' => $isClosed,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'timeSlots' => $ts,
                'slotDuration' => ['60'],
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>|null  $row
     * @return array{is_closed: bool, start_time: string, end_time: string, slot_duration: list<string>, person_limit: int}
     */
    public static function gymRowToForm(?array $row): array
    {
        $row = is_array($row) ? $row : [];
        if (isset($row['startTime']) || isset($row['isClosed']) || isset($row['is_closed'])) {
            $durations = $row['slotDuration'] ?? ['60'];
            if (! is_array($durations)) {
                $durations = [$durations];
            }
            $durations = array_values(array_intersect(array_map('strval', $durations), ['60']));
            if ($durations === []) {
                $durations = ['60'];
            }

            return [
                'is_closed' => filter_var($row['isClosed'] ?? $row['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'start_time' => (string) ($row['startTime'] ?? $row['start'] ?? ''),
                'end_time' => (string) ($row['endTime'] ?? $row['end'] ?? ''),
                'slot_duration' => $durations,
                'person_limit' => max(1, (int) ($row['personLimit'] ?? 1)),
            ];
        }

        $dur = ['60'];

        return [
            'is_closed' => filter_var($row['closed'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'start_time' => (string) ($row['start'] ?? ''),
            'end_time' => (string) ($row['end'] ?? ''),
            'slot_duration' => $dur,
            'person_limit' => 1,
        ];
    }

    /**
     * @param  array<string, mixed>|null  $row
     * @return array{is_closed: bool, slot_duration: list<string>, time_slots: list<string>}
     */
    public static function ptRowToForm(?array $row): array
    {
        $row = is_array($row) ? $row : [];
        if (isset($row['timeSlots']) || isset($row['isClosed']) || isset($row['slotDuration'])) {
            $ts = $row['timeSlots'] ?? [];
            if (! is_array($ts)) {
                $ts = [];
            }

            return [
                'is_closed' => filter_var($row['isClosed'] ?? $row['closed'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'slot_duration' => ['60'],
                'time_slots' => self::filterPtTimeSlotsToOneHour(array_values(array_filter($ts, 'is_string'))),
            ];
        }

        return [
            'is_closed' => filter_var($row['closed'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'slot_duration' => ['60'],
            'time_slots' => [],
        ];
    }

    /**
     * @param  list<string>  $slots
     * @return list<string>
     */
    public static function filterPtTimeSlotsToOneHour(array $slots): array
    {
        return array_values(array_filter($slots, fn ($v) => is_string($v) && self::ptSlotIsOneHourString($v)));
    }

    private static function ptSlotIsOneHourString(string $v): bool
    {
        if (preg_match('/^(\d{2}):(\d{2})\|(\d{2}):(\d{2})$/', $v, $m) !== 1) {
            return false;
        }

        $start = (int) $m[1] * 60 + (int) $m[2];
        $end = (int) $m[3] * 60 + (int) $m[4];

        return $end > $start && ($end - $start) === 60;
    }

    /**
     * @param  array<string, mixed>|null  $schedule
     * @return array<string, array<string, mixed>>|null
     */
    public static function migrateLegacyAvailability(?array $schedule): ?array
    {
        if ($schedule === null || $schedule === []) {
            return $schedule;
        }
        $first = reset($schedule);
        if (! is_array($first)) {
            return $schedule;
        }
        if (isset($first['isClosed']) || isset($first['startTime'])) {
            return $schedule;
        }

        $out = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $form = self::gymRowToForm(is_array($schedule[$day] ?? null) ? $schedule[$day] : null);
            $isClosed = $form['is_closed'];
            $slots = array_values(array_intersect($form['slot_duration'], ['60']));
            if ($slots === []) {
                $slots = ['60'];
            }
            $out[$day] = [
                'isClosed' => $isClosed,
                'startTime' => $isClosed ? null : self::emptyToNull($form['start_time']),
                'endTime' => $isClosed ? null : self::emptyToNull($form['end_time']),
                'slotDuration' => $slots,
                'personLimit' => max(1, (int) $form['person_limit']),
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>|null  $schedule
     * @return array<string, array<string, mixed>>|null
     */
    public static function migrateLegacyPt(?array $schedule): ?array
    {
        if ($schedule === null || $schedule === []) {
            return $schedule;
        }
        $first = reset($schedule);
        if (! is_array($first)) {
            return $schedule;
        }
        if (isset($first['timeSlots'])) {
            return $schedule;
        }

        $out = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $row = $schedule[$day] ?? [];
            if (! is_array($row)) {
                $row = [];
            }
            $closed = filter_var($row['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $start = $row['start'] ?? null;
            $end = $row['end'] ?? null;
            $ts = [];
            if (! $closed && $start && $end) {
                $ts = [(string) $start.'|'.(string) $end];
            }
            $out[$day] = [
                'isClosed' => $closed,
                'startTime' => $ts === [] ? null : explode('|', $ts[0], 2)[0],
                'endTime' => $ts === [] ? null : (explode('|', $ts[0], 2)[1] ?? null),
                'timeSlots' => $ts,
                'slotDuration' => ['60'],
            ];
        }

        return $out;
    }

    private static function emptyToNull(mixed $v): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }

        return (string) $v;
    }

    /**
     * Whether weekly gym availability offers one-hour slots (aligned with WP gym main page flags).
     *
     * @param  array<string, mixed>|null  $schedule  Stored `availability_schedule` (normalized shape)
     * @return array{offers_40min: bool, offers_1hr: bool}
     */
    public static function gymScheduleOfferSlotLengths(?array $schedule): array
    {
        $offers1hr = false;
        if (! is_array($schedule) || $schedule === []) {
            return ['offers_40min' => false, 'offers_1hr' => true];
        }
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $dayData = $schedule[$day] ?? null;
            if (! is_array($dayData)) {
                continue;
            }
            $isClosed = filter_var($dayData['isClosed'] ?? $dayData['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            if ($isClosed) {
                continue;
            }
            $durs = $dayData['slotDuration'] ?? [];
            if (! is_array($durs)) {
                $durs = $durs !== null && $durs !== '' ? [(string) $durs] : [];
            }
            foreach (array_map('strval', $durs) as $d) {
                if ($d === '60' || $d === '40') {
                    $offers1hr = true;
                    break 2;
                }
            }
        }
        if (! $offers1hr) {
            $offers1hr = true;
        }

        return ['offers_40min' => false, 'offers_1hr' => $offers1hr];
    }
}
