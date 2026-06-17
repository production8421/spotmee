@php
    $photoUrl = $photoUrl ?? null;
    $inputId = $inputId ?? 'profile_photo';
    $removeInputId = $removeInputId ?? 'remove_profile_photo';
    $showRemove = $showRemove ?? filled($photoUrl);
@endphp

<div class="form-group">
    <label class="col-form-label" for="{{ $inputId }}">{{ __('Profile photo') }} <span class="text-muted">({{ __('optional') }})</span></label>

    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
        @if ($photoUrl)
            <img
                src="{{ $photoUrl }}"
                alt="{{ __('Profile photo') }}"
                class="rounded-circle border object-fit-cover"
                width="80"
                height="80"
            >
        @else
            <div
                class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted fw-semibold"
                style="width: 80px; height: 80px;"
                aria-hidden="true"
            >
                {{ $initials ?? '?' }}
            </div>
        @endif
        <div class="small text-muted">{{ __('JPG, PNG, or WebP. Max 5 MB.') }}</div>
    </div>

    <label class="border border-2 border-dashed rounded p-4 text-center d-block bg-light @error($inputId) border-danger @enderror" style="cursor: pointer;">
        <span class="text-muted">{{ __('Click to upload a profile photo') }}</span>
        <input
            class="d-none @error($inputId) is-invalid @enderror"
            id="{{ $inputId }}"
            type="file"
            name="{{ $inputId }}"
            accept="image/jpeg,image/png,image/webp"
        >
    </label>
    @error($inputId)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @if ($showRemove)
        <div class="form-check mt-3">
            <input class="form-check-input" id="{{ $removeInputId }}" type="checkbox" name="{{ $removeInputId }}" value="1" @checked(old($removeInputId))>
            <label class="form-check-label" for="{{ $removeInputId }}">{{ __('Remove current photo') }}</label>
        </div>
    @endif
</div>
