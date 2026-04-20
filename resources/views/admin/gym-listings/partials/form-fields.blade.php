@push('styles')
<style>
    /* Cuba theme can wash out checkbox borders; keep gym listing inputs clearly visible */
    .gym-listing-form .form-check-input {
        width: 1.125em;
        height: 1.125em;
        margin-top: 0.2em;
        border: 2px solid #6c757d;
        background-color: #fff;
        opacity: 1;
        box-shadow: none;
    }
    .gym-listing-form .form-check-input:focus {
        border-color: var(--theme-default, #7366ff);
        box-shadow: 0 0 0 0.2rem rgba(115, 102, 255, 0.25);
        outline: 0;
    }
    .gym-listing-form .form-check-input[type="checkbox"]:not(:checked) {
        background-image: none;
    }
    .gym-listing-form .form-check-input:checked[type="checkbox"] {
        background-color: var(--theme-default, #7366ff);
        border-color: var(--theme-default, #7366ff);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        background-size: 0.75em 0.75em;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Text inputs & selects: same visible 2px border as checkboxes */
    .gym-listing-form .form-control,
    .gym-listing-form .form-select {
        border: 2px solid #6c757d;
        background-color: #fff;
        color: var(--body-font-color, #212529);
    }
    .gym-listing-form .form-control:focus,
    .gym-listing-form .form-select:focus {
        border-color: var(--theme-default, #7366ff);
        box-shadow: 0 0 0 0.2rem rgba(115, 102, 255, 0.25);
        outline: 0;
        background-color: #fff;
    }
    .gym-listing-form .form-control.is-invalid,
    .gym-listing-form .form-select.is-invalid {
        border-color: #dc3545;
    }
    .gym-listing-form .form-control.is-invalid:focus,
    .gym-listing-form .form-select.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    .gym-listing-form .gym-availability-table .form-check-input[type="radio"],
    .gym-listing-form .gym-availability-table .form-check-input[type="checkbox"] {
        width: 1.125em;
        height: 1.125em;
        border: 2px solid #6c757d;
    }
    .gym-listing-form .gym-availability-table .form-check-input[type="radio"]:checked,
    .gym-listing-form .gym-availability-table .form-check-input[type="checkbox"]:checked {
        background-color: var(--theme-default, #7366ff);
        border-color: var(--theme-default, #7366ff);
    }
    /* Compact time fields — not full table column width */
    .gym-listing-form .gym-availability-table .gym-availability-time {
        flex: 0 0 auto;
        width: 8.75rem;
        max-width: 8.75rem;
        min-width: 8.75rem;
        box-sizing: border-box;
    }
    /* Gallery remove: trash control (checkbox stays for form submit, hidden from view) */
    .gym-listing-form .gallery-edit-card {
        position: relative;
        border-radius: 0.375rem;
        overflow: hidden;
        background: #f8f9fa;
    }
    .gym-listing-form .gallery-edit-thumb {
        display: block;
        width: 100%;
        max-height: 140px;
        object-fit: cover;
        vertical-align: middle;
    }
    .gym-listing-form .gallery-remove-cb {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    .gym-listing-form .gallery-remove-btn {
        position: absolute;
        top: 0.4rem;
        right: 0.4rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        padding: 0;
        margin: 0;
        line-height: 0;
        cursor: pointer;
        border-radius: 0.35rem;
        background: #fff;
        border: 1px solid rgba(220, 53, 69, 0.45);
        color: #dc3545;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    }
    .gym-listing-form .gallery-remove-btn:hover {
        background: #fff5f5;
        border-color: #dc3545;
        color: #b02a37;
    }
    .gym-listing-form .gallery-remove-btn svg {
        width: 18px;
        height: 18px;
    }
    .gym-listing-form .gallery-edit-card:has(.gallery-remove-cb:checked) .gallery-edit-thumb {
        opacity: 0.42;
    }
    .gym-listing-form .gallery-edit-card:has(.gallery-remove-cb:checked) .gallery-remove-btn {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
    .gym-listing-form .gallery-edit-card:has(.gallery-remove-cb:checked) .gallery-remove-btn svg {
        stroke: #fff;
    }
    .gym-listing-form .gallery-remove-cb:focus-visible + .gallery-remove-btn {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.35);
    }
</style>
@endpush

@php
    /** Create passes gymListing null; edit passes a persisted model. Do not rely on $model->exists (can block previews). */
    $isEdit = $gymListing instanceof \App\Models\GymListing;
    $cfg = config('gym_listing');
    $equipmentOld = old('equipment');
    if ($equipmentOld !== null) {
        $equipmentRows = $equipmentOld;
    } elseif ($isEdit && is_array($gymListing->equipment) && count($gymListing->equipment) > 0) {
        $equipmentRows = $gymListing->equipment;
    } else {
        $equipmentRows = [['name' => '', 'quantity' => 1]];
    }
    $selectedServices = old('service_options', $isEdit ? ($gymListing->service_options ?? []) : []);
    $selectedAmenities = old('amenities', $isEdit ? ($gymListing->amenities ?? []) : []);
@endphp

<h6 class="fw-semibold pb-2 mb-4 border-bottom">{{ __('Facility details') }}</h6>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="name">{{ __('Gym title') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name', $gymListing?->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $gymListing?->email) }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('phone') is-invalid @enderror" id="phone" type="text" name="phone" value="{{ old('phone', $gymListing?->phone) }}" required>
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('address') is-invalid @enderror" id="address" type="text" name="address" value="{{ old('address', $gymListing?->address) }}" required>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="city">{{ __('City') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('city') is-invalid @enderror" id="city" type="text" name="city" value="{{ old('city', $gymListing?->city) }}" required>
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="state">{{ __('State') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <select class="form-select @error('state') is-invalid @enderror" id="state" name="state" required>
            <option value="" disabled @selected(old('state', $gymListing?->state) === null || old('state', $gymListing?->state) === '')>{{ __('Select state') }}</option>
            @foreach ($cfg['states'] as $code => $label)
                <option value="{{ $code }}" @selected(old('state', $gymListing?->state) === $code)>{{ $label }}</option>
            @endforeach
        </select>
        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="postal_code">{{ __('Postal code') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $gymListing?->postal_code) }}" required>
        @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="facility_type">{{ __('Facility type') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <select class="form-select @error('facility_type') is-invalid @enderror" id="facility_type" name="facility_type" required>
            <option value="" disabled @selected(old('facility_type', $gymListing?->facility_type) === null || old('facility_type', $gymListing?->facility_type) === '')>{{ __('Select type') }}</option>
            @foreach ($cfg['facility_types'] as $key => $label)
                <option value="{{ $key }}" @selected(old('facility_type', $gymListing?->facility_type) === $key)>{{ __($label) }}</option>
            @endforeach
        </select>
        @error('facility_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="area_size">{{ __('Area size') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <select class="form-select @error('area_size') is-invalid @enderror" id="area_size" name="area_size" required>
            <option value="" disabled @selected(old('area_size', $gymListing?->area_size) === null || old('area_size', $gymListing?->area_size) === '')>{{ __('Select size') }}</option>
            @foreach ($cfg['area_sizes'] as $key => $label)
                <option value="{{ $key }}" @selected(old('area_size', $gymListing?->area_size) === $key)>{{ __($label) }}</option>
            @endforeach
        </select>
        @error('area_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold pt-0">{{ __('Service options') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <div class="d-flex flex-wrap gap-3 @error('service_options') is-invalid border border-danger rounded p-2 @enderror">
            @foreach ($cfg['service_options'] as $key => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_options[]" id="service_{{ $key }}" value="{{ $key }}" @checked(in_array($key, $selectedServices, true))>
                    <label class="form-check-label" for="service_{{ $key }}">{{ __($label) }}</label>
                </div>
            @endforeach
        </div>
        @error('service_options')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="pets_policy">{{ __('Pets policy') }} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <select class="form-select @error('pets_policy') is-invalid @enderror" id="pets_policy" name="pets_policy" required>
            <option value="" disabled @selected(old('pets_policy', $gymListing?->pets_policy) === null || old('pets_policy', $gymListing?->pets_policy) === '')>{{ __('Select policy') }}</option>
            @foreach ($cfg['pets_policies'] as $key => $label)
                <option value="{{ $key }}" @selected(old('pets_policy', $gymListing?->pets_policy) === $key)>{{ __($label) }}</option>
            @endforeach
        </select>
        @error('pets_policy')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="check_in_method">{{ __('Check-in method') }}</label>
    <div class="col-sm-9">
        <select class="form-select @error('check_in_method') is-invalid @enderror" id="check_in_method" name="check_in_method">
            <option value="">{{ __('Select method') }}</option>
            @foreach ($cfg['check_in_methods'] as $key => $label)
                <option value="{{ $key }}" @selected(old('check_in_method', $gymListing?->check_in_method) === $key)>{{ __($label) }}</option>
            @endforeach
        </select>
        @error('check_in_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3 align-items-center">
    <label class="col-sm-3 col-form-label fw-semibold" for="person_limit">{{ __('Person limit') }}</label>
    <div class="col-sm-9">
        <select class="form-select @error('person_limit') is-invalid @enderror" id="person_limit" name="person_limit">
            <option value="" @selected(old('person_limit', $gymListing?->person_limit) === null || old('person_limit', $gymListing?->person_limit) === '')>{{ __('Use schedule limit') }}</option>
            @for ($n = 1; $n <= 50; $n++)
                <option value="{{ $n }}" @selected((string) old('person_limit', $gymListing?->person_limit) === (string) $n)>{{ $n }}</option>
            @endfor
        </select>
        <p class="text-muted small mb-0 mt-1">{{ __('Optional override. If empty, capacity comes from the day schedule.') }}</p>
        @error('person_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<h6 class="fw-semibold pb-2 mb-4 mt-5 border-bottom">{{ __('Description') }} <span class="text-danger">*</span></h6>

<div class="row mb-4">
    <div class="col-12">
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required minlength="10">{{ old('description', $gymListing?->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<h6 class="fw-semibold pb-2 mb-3 border-bottom">{{ __('Equipment') }} <span class="text-danger">*</span></h6>
<p class="text-muted small mb-2">{{ __('Equipment name') }} <span class="text-danger">*</span> — {{ __('Quantity') }} <span class="text-danger">*</span></p>

<div id="equipment-block">
<div id="equipment-rows" class="mb-2">
    @foreach ($equipmentRows as $i => $row)
        <div class="row g-2 mb-2 align-items-end" data-equipment-row>
            <div class="col-md-6">
                <input class="form-control @error('equipment.'.$i.'.name') is-invalid @enderror" type="text" name="equipment[{{ $i }}][name]" value="{{ old('equipment.'.$i.'.name', $row['name'] ?? '') }}" placeholder="{{ __('Equipment name') }}">
                @error('equipment.'.$i.'.name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 col-6">
                <input class="form-control @error('equipment.'.$i.'.quantity') is-invalid @enderror" type="number" name="equipment[{{ $i }}][quantity]" value="{{ old('equipment.'.$i.'.quantity', $row['quantity'] ?? 1) }}" min="1" placeholder="1">
                @error('equipment.'.$i.'.quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 col-6">
                <button class="btn btn-outline-primary btn-sm w-100" type="button" data-remove-equipment>{{ __('Remove') }}</button>
            </div>
        </div>
    @endforeach
</div>
@error('equipment')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

<button class="btn btn-outline-primary btn-sm mb-4" type="button" data-add-equipment>+ {{ __('Add equipment') }}</button>
</div>

<template id="equipment-row-template">
    <div class="row g-2 mb-2 align-items-end" data-equipment-row>
        <div class="col-md-6">
            <input class="form-control" type="text" name="equipment[__INDEX__][name]" value="" placeholder="{{ __('Equipment name') }}">
        </div>
        <div class="col-md-3 col-6">
            <input class="form-control" type="number" name="equipment[__INDEX__][quantity]" value="1" min="1">
        </div>
        <div class="col-md-3 col-6">
            <button class="btn btn-outline-primary btn-sm w-100" type="button" data-remove-equipment>{{ __('Remove') }}</button>
        </div>
    </div>
</template>

<h6 class="fw-semibold pb-2 mb-3 border-bottom">{{ __('Amenities') }}</h6>
<div class="border rounded p-3 mb-4 @error('amenities') border-danger @enderror">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
        @foreach ($cfg['amenities'] as $key => $label)
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="amenities[]" id="amenity_{{ $key }}" value="{{ $key }}" @checked(in_array($key, $selectedAmenities, true))>
                    <label class="form-check-label" for="amenity_{{ $key }}">{{ __($label) }}</label>
                </div>
            </div>
        @endforeach
    </div>
    @error('amenities')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
</div>

<h6 class="fw-semibold pb-2 mb-3 border-bottom">{{ __('Images') }}</h6>

<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('Main image') }} @if(! $isEdit || ! $gymListing?->main_image_path)<span class="text-danger">*</span>@endif</label>
    @if ($isEdit && $gymListing->main_image_path)
        <div class="mb-2">
            <img src="{{ $gymListing->mainImageUrl() }}" alt="" class="img-thumbnail" style="max-height: 120px;">
            <p class="text-muted small mb-0">{{ __('Upload a new file to replace the current main image.') }}</p>
        </div>
    @endif
    <label class="border border-2 border-dashed rounded p-4 text-center d-block cursor-pointer bg-light @error('main_image') border-danger @enderror" data-upload-trigger="main_image">
        <span class="text-muted">{{ __('Drag & drop or click to upload main image') }}</span>
        <input class="d-none @error('main_image') is-invalid @enderror" id="main_image" type="file" name="main_image" accept="image/*" @if(! $isEdit || ! $gymListing?->main_image_path) required @endif>
    </label>
    @error('main_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('Gallery images') }}</label>
    @if ($isEdit && is_array($gymListing->gallery_paths) && count($gymListing->gallery_paths) > 0)
        <div class="row g-2 mb-2">
            @foreach ($gymListing->gallery_paths as $path)
                @php
                    $gid = 'rmg_'.md5($path);
                @endphp
                <div class="col-6 col-md-3">
                    <div class="gallery-edit-card border">
                        <img src="{{ \App\Models\GymListing::publicStorageUrl($path) }}" alt="" class="gallery-edit-thumb">
                        <input
                            class="gallery-remove-cb"
                            type="checkbox"
                            name="remove_gallery[]"
                            id="{{ $gid }}"
                            value="{{ $path }}"
                        >
                        <label class="gallery-remove-btn" for="{{ $gid }}" title="{{ __('Remove image') }}">
                            <i data-feather="trash-2"></i>
                            <span class="visually-hidden">{{ __('Remove image') }}</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <label class="border border-2 border-dashed rounded p-4 text-center d-block cursor-pointer bg-light @error('gallery') border-danger @enderror" data-upload-trigger="gallery">
        <span class="text-muted">{{ __('Drag & drop or click to upload gallery images (max 15)') }}</span>
        <input class="d-none" id="gallery" type="file" name="gallery[]" accept="image/*" multiple>
    </label>
    @error('gallery')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @error('gallery.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('Intro video') }}</label>
    @if ($isEdit && $gymListing->intro_video_path)
        <p class="text-muted small">{{ __('A video is already uploaded. Upload a new file to replace it.') }}</p>
    @endif
    <label class="border border-2 border-dashed rounded p-4 text-center d-block cursor-pointer bg-light @error('intro_video') border-danger @enderror" data-upload-trigger="intro_video">
        <span class="text-muted">{{ __('Drag & drop or click to upload an intro video (optional)') }}</span>
        <input class="d-none" id="intro_video" type="file" name="intro_video" accept="video/mp4,video/quicktime,video/webm">
    </label>
    <p class="text-muted small mt-1 mb-0">{{ __('Short clip showcasing your gym. MP4 or similar video formats are supported.') }}</p>
    @error('intro_video')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

@include('admin.gym-listings.partials.availability-schedule')

@include('admin.gym-listings.partials.personal-training')

@if ($showGymListingPublishedToggle ?? true)
<div class="form-check mb-0">
    <input type="hidden" name="is_published" value="0">
    <input class="form-check-input @error('is_published') is-invalid @enderror" id="is_published" type="checkbox" name="is_published" value="1" @checked(old('is_published', $gymListing?->is_published ?? true))>
    <label class="form-check-label" for="is_published">{{ __('Published') }}</label>
    @error('is_published')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
@endif

@include('admin.gym-listings.partials.form-scripts')
