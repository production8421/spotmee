@php
    use App\Support\RyjGymSchedule;

    $dayKeys = config('gym_listing.weekday_keys', []);
    $dayLabels = config('gym_listing.weekday_labels', []);
    $def = config('gym_listing.default_personal_training_availability_row', []);
    $ptOn = filter_var(
        old('personal_training_available', $gymListing?->personal_training_available ?? false),
        FILTER_VALIDATE_BOOLEAN
    );

    $ptScheduleForm = old('pt_schedule');
    if (! is_array($ptScheduleForm)) {
        $ptScheduleForm = [];
        foreach ($dayKeys as $day) {
            if (($isEdit ?? false) && isset($gymListing) && is_array($gymListing->personal_training_availability[$day] ?? null)) {
                $ptScheduleForm[$day] = RyjGymSchedule::ptRowToForm($gymListing->personal_training_availability[$day]);
            } else {
                $ptScheduleForm[$day] = RyjGymSchedule::ptRowToForm([
                    'closed' => (bool) ($def['closed'] ?? false),
                    'start' => (string) ($def['start'] ?? '09:00'),
                    'end' => (string) ($def['end'] ?? '17:00'),
                    'slot_minutes' => (int) ($def['slot_minutes'] ?? 60),
                ]);
            }
        }
    }

    $ptTimeSlotsOld = old('pt_time_slots');
    if (! is_array($ptTimeSlotsOld)) {
        $ptTimeSlotsOld = [];
        foreach ($dayKeys as $day) {
            $ptTimeSlotsOld[$day] = [];
            if (($isEdit ?? false) && isset($gymListing) && is_array($gymListing->personal_training_availability[$day] ?? null)) {
                $ts = $gymListing->personal_training_availability[$day]['timeSlots'] ?? [];
                $raw = is_array($ts) ? array_values(array_filter($ts, 'is_string')) : [];
                $filtered = RyjGymSchedule::filterPtTimeSlotsToOneHour($raw);
                $gymRow = is_array($gymListing->availability_schedule[$day] ?? null)
                    ? $gymListing->availability_schedule[$day]
                    : null;
                $ptTimeSlotsOld[$day] = RyjGymSchedule::gymAvailabilityAllowsOneHourPt($gymRow) ? $filtered : [];
            }
        }
    }
@endphp

<h6 class="fw-semibold pb-2 mb-3 mt-4 border-bottom">{{ __('Personal training time slots') }}</h6>

<div class="row mb-3 align-items-start">
    <label class="col-sm-3 col-form-label fw-semibold" for="personal_training_available">{{ __('Personal trainer available') }}</label>
    <div class="col-sm-9">
        <select
            class="form-select @error('personal_training_available') is-invalid @enderror"
            id="personal_training_available"
            name="personal_training_available"
            style="max-width: 10rem"
            data-pt-available-toggle
        >
            <option value="0" @selected(! $ptOn)>{{ __('No') }}</option>
            <option value="1" @selected($ptOn)>{{ __('Yes') }}</option>
        </select>
        <p class="text-muted small mb-0 mt-1">{{ __('Do you offer personal training service to guests?') }}</p>
        @error('personal_training_available')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div id="pt-details" class="{{ $ptOn ? '' : 'd-none' }}" data-pt-details>
    <div class="mb-4">
        <label class="form-label fw-semibold">{{ __('PT certification') }} <span class="text-danger">*</span></label>
        @if ($isEdit && $gymListing->personal_training_cert_path)
            <div class="mb-2">
                <div class="gallery-edit-card border d-inline-block align-top" style="max-width: 220px;">
                    <img src="{{ $gymListing->personalTrainingCertUrl() }}" alt="" class="gallery-edit-thumb" style="max-height: 120px;">
                    <input type="hidden" name="remove_personal_training_cert" value="0">
                    <input
                        type="checkbox"
                        name="remove_personal_training_cert"
                        value="1"
                        id="remove_pt_cert_cb"
                        class="gallery-remove-cb"
                        @checked(old('remove_personal_training_cert', false))
                    >
                    <label class="gallery-remove-btn" for="remove_pt_cert_cb" title="{{ __('Remove certification') }}">
                        <i data-feather="trash-2"></i>
                        <span class="visually-hidden">{{ __('Remove certification') }}</span>
                    </label>
                </div>
                <p class="text-muted small mb-0 mt-1">{{ __('Upload a new image to replace this file, or use the trash icon to remove it. If personal training stays enabled, you must upload a replacement before saving.') }}</p>
            </div>
        @endif
        <label class="border border-2 border-dashed rounded p-4 text-center d-block cursor-pointer bg-light @error('personal_training_cert') border-danger @enderror" data-upload-trigger="personal_training_cert">
            <span class="text-muted">{{ __('Drag & drop or click to upload certification (image)') }}</span>
            <input
                class="d-none @error('personal_training_cert') is-invalid @enderror"
                id="personal_training_cert"
                type="file"
                name="personal_training_cert"
                accept="image/*"
            >
        </label>
        <p class="text-muted small mt-1 mb-0">{{ __('Please upload a copy of your personal training certification.') }}</p>
        @error('personal_training_cert')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">{{ __('CPR / First aid certificate') }}</label>
        @if ($isEdit && $gymListing->personal_training_cpr_cert_path)
            <div class="mb-2">
                <div class="gallery-edit-card border d-inline-block align-top" style="max-width: 220px;">
                    <img src="{{ $gymListing->personalTrainingCprCertUrl() }}" alt="" class="gallery-edit-thumb" style="max-height: 120px;">
                    <input type="hidden" name="remove_personal_training_cpr_cert" value="0">
                    <input
                        type="checkbox"
                        name="remove_personal_training_cpr_cert"
                        value="1"
                        id="remove_pt_cpr_cert_cb"
                        class="gallery-remove-cb"
                        @checked(old('remove_personal_training_cpr_cert', false))
                    >
                    <label class="gallery-remove-btn" for="remove_pt_cpr_cert_cb" title="{{ __('Remove certificate') }}">
                        <i data-feather="trash-2"></i>
                        <span class="visually-hidden">{{ __('Remove certificate') }}</span>
                    </label>
                </div>
                <p class="text-muted small mb-0 mt-1">{{ __('Upload a new image to replace this file, or use the trash icon to remove it.') }}</p>
            </div>
        @endif
        <label class="border border-2 border-dashed rounded p-4 text-center d-block cursor-pointer bg-light @error('personal_training_cpr_cert') border-danger @enderror" data-upload-trigger="personal_training_cpr_cert">
            <span class="text-muted">{{ __('Drag & drop or click to upload CPR / First aid certificate (image)') }}</span>
            <input class="d-none" id="personal_training_cpr_cert" type="file" name="personal_training_cpr_cert" accept="image/*">
        </label>
        <p class="text-muted small mt-1 mb-0">{{ __('Please upload a copy of your CPR or first aid certificate if you have one.') }}</p>
        @error('personal_training_cpr_cert')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <p class="text-muted small mb-2">{{ __('Session times follow your gym opening hours for each day. Tick the one-hour slots you offer.') }}</p>

    <div class="table-responsive border rounded mb-4">
        <table class="table table-bordered align-middle mb-0 gym-availability-table">
            <thead class="table-primary">
                <tr>
                    <th scope="col">{{ __('Day') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col">{{ __('Slot duration') }}</th>
                    <th scope="col">{{ __('Time slots') }}</th>
                    <th scope="col" class="text-nowrap">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dayKeys as $day)
                    @php
                        $pf = is_array($ptScheduleForm[$day] ?? null) ? $ptScheduleForm[$day] : [];
                        $closed = filter_var($pf['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
                        $initialSlots = is_array($ptTimeSlotsOld[$day] ?? null) ? $ptTimeSlotsOld[$day] : [];
                    @endphp
                    <tr data-day-pt-row="{{ $day }}" data-initial-pt-slots='@json(array_values($initialSlots))'>
                        <td class="fw-semibold">{{ __($dayLabels[$day] ?? ucfirst($day)) }}</td>
                        <td>
                            <input type="hidden" name="pt_schedule[{{ $day }}][is_closed]" value="0">
                            <div class="form-check mb-0">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="pt_schedule[{{ $day }}][is_closed]"
                                    value="1"
                                    id="pt_closed_{{ $day }}"
                                    data-pt-closed-checkbox
                                    @checked($closed)
                                >
                                <label class="form-check-label" for="pt_closed_{{ $day }}">{{ __('Closed') }}</label>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ __('1 hour') }}</span>
                            <p class="text-muted small mb-0">{{ __('Personal training time slots are fixed to 1 hour.') }}</p>
                            <input type="hidden" name="pt_schedule[{{ $day }}][slot_duration][]" value="60">
                        </td>
                        <td class="pt-time-slots-cell">
                            <p class="text-muted small mb-1 d-none" data-pt-no-hour-slots-hint="{{ $day }}">
                                {{ __('Turn on “1 hour” under Availability schedule for this day to list personal training times.') }}
                            </p>
                            <div class="pt-time-range-list mb-1" data-pt-time-list="{{ $day }}"></div>
                            <div class="small" data-pt-slot-actions="{{ $day }}">
                                <a href="#" class="text-decoration-none" data-pt-select-all-day="{{ $day }}">{{ __('Select all') }}</a>
                                <span class="text-muted">·</span>
                                <a href="#" class="text-decoration-none" data-pt-clear-day="{{ $day }}">{{ __('Clear') }}</a>
                            </div>
                            @error('personal_training_availability.'.$day.'.timeSlots')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            @if ($day === 'monday')
                                <button type="button" class="btn btn-outline-primary btn-sm text-nowrap mb-1" data-apply-all-pt-availability>
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
</div>
