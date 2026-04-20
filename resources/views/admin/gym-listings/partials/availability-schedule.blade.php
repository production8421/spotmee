@php
    use App\Support\RyjGymSchedule;

    $dayKeys = config('gym_listing.weekday_keys', []);
    $dayLabels = config('gym_listing.weekday_labels', []);
    $def = config('gym_listing.default_availability_row', []);
    $gymAvailForm = old('gym_availability');
    if (! is_array($gymAvailForm)) {
        $gymAvailForm = [];
        foreach ($dayKeys as $day) {
            if (($isEdit ?? false) && isset($gymListing) && is_array($gymListing->availability_schedule[$day] ?? null)) {
                $gymAvailForm[$day] = RyjGymSchedule::gymRowToForm($gymListing->availability_schedule[$day]);
            } else {
                $gymAvailForm[$day] = RyjGymSchedule::gymRowToForm([
                    'closed' => (bool) ($def['closed'] ?? false),
                    'start' => (string) ($def['start'] ?? '09:00'),
                    'end' => (string) ($def['end'] ?? '17:00'),
                    'slot_minutes' => (int) ($def['slot_minutes'] ?? 40),
                ]);
            }
        }
    }
@endphp

<h6 class="fw-semibold pb-2 mb-3 mt-4 border-bottom">{{ __('Availability schedule') }}</h6>
<p class="text-muted small mb-3">{{ __('Set weekly hours and slot lengths. You can allow both 40-minute and 1-hour gym slots. Use Closed for days with no bookings.') }}</p>

<div class="table-responsive border rounded mb-4">
    <table class="table table-bordered align-middle mb-0 gym-availability-table">
        <thead class="table-light">
            <tr>
                <th scope="col">{{ __('Day') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Time range') }}</th>
                <th scope="col">{{ __('Slot duration') }}</th>
                <th scope="col" class="text-nowrap">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dayKeys as $day)
                @php
                    $gf = is_array($gymAvailForm[$day] ?? null) ? $gymAvailForm[$day] : [];
                    $closed = filter_var($gf['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    $slotDurs = $gf['slot_duration'] ?? ['40'];
                    if (! is_array($slotDurs)) {
                        $slotDurs = ['40'];
                    }
                    $slotDurs = array_values(array_intersect(array_map('strval', $slotDurs), ['40', '60']));
                    if ($slotDurs === []) {
                        $slotDurs = ['40'];
                    }
                    $startVal = (string) ($gf['start_time'] ?? '');
                    $endVal = (string) ($gf['end_time'] ?? '');
                    $personLimit = max(1, (int) ($gf['person_limit'] ?? 1));
                @endphp
                <tr data-day-schedule-row="{{ $day }}">
                    <td class="fw-semibold">{{ __($dayLabels[$day] ?? ucfirst($day)) }}</td>
                    <td>
                        <input type="hidden" name="gym_availability[{{ $day }}][is_closed]" value="0">
                        <div class="form-check mb-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="gym_availability[{{ $day }}][is_closed]"
                                value="1"
                                id="gym_closed_{{ $day }}"
                                data-gym-closed-checkbox
                                @checked($closed)
                            >
                            <label class="form-check-label" for="gym_closed_{{ $day }}">{{ __('Closed') }}</label>
                        </div>
                    </td>
                    <td class="gym-availability-time-cell">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <input
                                class="form-control gym-availability-time @error('gym_availability.'.$day.'.start_time') is-invalid @enderror"
                                type="time"
                                name="gym_availability[{{ $day }}][start_time]"
                                value="{{ $startVal }}"
                                data-gym-time-input
                                step="300"
                            >
                            <span class="text-muted small flex-shrink-0">{{ __('to') }}</span>
                            <input
                                class="form-control gym-availability-time @error('gym_availability.'.$day.'.end_time') is-invalid @enderror"
                                type="time"
                                name="gym_availability[{{ $day }}][end_time]"
                                value="{{ $endVal }}"
                                data-gym-time-input
                                step="300"
                            >
                        </div>
                        @error('gym_availability.'.$day.'.start_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('gym_availability.'.$day.'.end_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input gym-slot-duration-cb"
                                    type="checkbox"
                                    name="gym_availability[{{ $day }}][slot_duration][]"
                                    id="gym_slot40_{{ $day }}"
                                    value="40"
                                    data-gym-slot-duration
                                    @checked(in_array('40', $slotDurs, true))
                                >
                                <label class="form-check-label" for="gym_slot40_{{ $day }}">{{ __('40 min') }}</label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input gym-slot-duration-cb"
                                    type="checkbox"
                                    name="gym_availability[{{ $day }}][slot_duration][]"
                                    id="gym_slot60_{{ $day }}"
                                    value="60"
                                    data-gym-slot-duration
                                    @checked(in_array('60', $slotDurs, true))
                                >
                                <label class="form-check-label" for="gym_slot60_{{ $day }}">{{ __('1 hour') }}</label>
                            </div>
                        </div>
                        <input type="hidden" name="gym_availability[{{ $day }}][person_limit]" value="{{ $personLimit }}">
                    </td>
                    <td>
                        @if ($day === 'monday')
                            <button type="button" class="btn btn-outline-primary btn-sm text-nowrap" data-apply-all-availability>
                                {{ __('Apply all') }}
                            </button>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@error('availability_schedule')
    <div class="text-danger small mb-3">{{ $message }}</div>
@enderror
