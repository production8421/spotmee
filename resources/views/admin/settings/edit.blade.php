@extends('layouts.cuba.app')

@section('title', __('Settings').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Settings') }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-home"></use>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ __('Settings') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Branding') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Choose logos from the media library for the dashboard header, sidebar, login page, and footer.') }}</p>
                </div>
                <div class="card-body">
                    <form id="admin-settings-form" method="POST" action="{{ route('admin.settings.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <div class="form-label fw-semibold">{{ __('Header logo') }}</div>
                            <p class="text-muted small">{{ __('Shown in the top bar, sidebar, and login screen. Choose a raster image (JPEG, PNG, GIF, WebP) from Media; recommended max height ~40px.') }}</p>
                            @if ($settings->header_logo_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->headerLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_header_logo" id="remove_header_logo" value="1">
                                    <label class="form-check-label" for="remove_header_logo">{{ __('Remove custom header logo (use site default logo)') }}</label>
                                </div>
                            @else
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->displayHeaderLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <p class="text-muted small mb-2">{{ __('This default appears on the public site and dashboard until you pick a logo from Media.') }}</p>
                            @endif
                            <input type="hidden" name="header_logo_media_id" id="header_logo_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="header">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="header_logo_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="header_logo_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="header_logo_media_preview_img" style="max-height: 48px;">
                                <p class="small text-muted mb-0" id="header_logo_media_preview_label"></p>
                            </div>
                            @error('header_logo_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-label fw-semibold">{{ __('Footer logo') }}</div>
                            <p class="text-muted small">{{ __('Shown above the copyright line in the dashboard footer. Choose an image from Media.') }}</p>
                            @if ($settings->footer_logo_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->footerLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_footer_logo" id="remove_footer_logo" value="1">
                                    <label class="form-check-label" for="remove_footer_logo">{{ __('Remove custom footer logo (use header or site default)') }}</label>
                                </div>
                            @else
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->displayFooterLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <p class="text-muted small mb-2">{{ __('Uses the header logo when set; otherwise the same site default as above.') }}</p>
                            @endif
                            <input type="hidden" name="footer_logo_media_id" id="footer_logo_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="footer">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="footer_logo_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="footer_logo_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="footer_logo_media_preview_img" style="max-height: 48px;">
                                <p class="small text-muted mb-0" id="footer_logo_media_preview_label"></p>
                            </div>
                            @error('footer_logo_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">{{ __('Home hero section') }}</h6>
                        <p class="text-muted small">{{ __('Controls only the first hero section on the home page.') }}</p>

                        <div class="mb-3">
                            <label class="form-label" for="home_hero_heading">{{ __('Hero heading') }}</label>
                            <input
                                id="home_hero_heading"
                                type="text"
                                name="home_hero_heading"
                                value="{{ old('home_hero_heading', $settings->home_hero_heading) }}"
                                class="form-control @error('home_hero_heading') is-invalid @enderror"
                                maxlength="255"
                            >
                            @error('home_hero_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">{{ __('Hero background type') }}</label>
                            @php
                                $heroType = old('home_hero_background_type', $settings->home_hero_background_type ?: 'color');
                            @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="home_hero_background_type" id="home_hero_background_type_color" value="color" {{ $heroType === 'color' ? 'checked' : '' }}>
                                <label class="form-check-label" for="home_hero_background_type_color">{{ __('Color') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="home_hero_background_type" id="home_hero_background_type_image" value="image" {{ $heroType === 'image' ? 'checked' : '' }}>
                                <label class="form-check-label" for="home_hero_background_type_image">{{ __('Image') }}</label>
                            </div>
                            @error('home_hero_background_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="home_hero_background_color">{{ __('Hero background color') }}</label>
                            <input
                                id="home_hero_background_color"
                                type="text"
                                name="home_hero_background_color"
                                value="{{ old('home_hero_background_color', $settings->home_hero_background_color ?: '#e3e3e0') }}"
                                class="form-control @error('home_hero_background_color') is-invalid @enderror"
                                placeholder="#e3e3e0"
                            >
                            @error('home_hero_background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-label fw-semibold">{{ __('Hero background image') }}</div>
                            @if ($settings->home_hero_background_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->homeHeroBackgroundPublicUrl() }}" alt="" class="img-fluid" style="max-height: 72px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_hero_background" id="remove_home_hero_background" value="1">
                                    <label class="form-check-label" for="remove_home_hero_background">{{ __('Remove current hero background image') }}</label>
                                </div>
                            @endif
                            <input type="hidden" name="home_hero_background_media_id" id="home_hero_background_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="hero">
                                    {{ __('Select hero image from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="home_hero_background_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="home_hero_background_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="home_hero_background_media_preview_img" style="max-height: 72px;">
                                <p class="small text-muted mb-0" id="home_hero_background_media_preview_label"></p>
                            </div>
                            @error('home_hero_background_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button1_label">{{ __('Button 1 label') }}</label>
                                <input
                                    id="home_hero_button1_label"
                                    type="text"
                                    name="home_hero_button1_label"
                                    value="{{ old('home_hero_button1_label', $settings->home_hero_button1_label) }}"
                                    class="form-control @error('home_hero_button1_label') is-invalid @enderror"
                                    maxlength="60"
                                >
                                @error('home_hero_button1_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button1_url">{{ __('Button 1 URL') }}</label>
                                <input
                                    id="home_hero_button1_url"
                                    type="url"
                                    name="home_hero_button1_url"
                                    value="{{ old('home_hero_button1_url', $settings->home_hero_button1_url) }}"
                                    class="form-control @error('home_hero_button1_url') is-invalid @enderror"
                                    placeholder="https://example.com"
                                >
                                @error('home_hero_button1_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button2_label">{{ __('Button 2 label') }}</label>
                                <input
                                    id="home_hero_button2_label"
                                    type="text"
                                    name="home_hero_button2_label"
                                    value="{{ old('home_hero_button2_label', $settings->home_hero_button2_label) }}"
                                    class="form-control @error('home_hero_button2_label') is-invalid @enderror"
                                    maxlength="60"
                                >
                                @error('home_hero_button2_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button2_url">{{ __('Button 2 URL') }}</label>
                                <input
                                    id="home_hero_button2_url"
                                    type="url"
                                    name="home_hero_button2_url"
                                    value="{{ old('home_hero_button2_url', $settings->home_hero_button2_url) }}"
                                    class="form-control @error('home_hero_button2_url') is-invalid @enderror"
                                    placeholder="https://example.com"
                                >
                                @error('home_hero_button2_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-2">{{ __('SMTP (outgoing mail)') }}</h6>
                        <p class="text-muted small mb-3">
                            {{ __('When enabled, the application sends mail (notifications, contact form, booking confirmations, etc.) through this SMTP server instead of the values in your .env file.') }}
                            {{ __('SMTP fields are saved when you click Save settings even if the toggle is off, so you can configure credentials first and enable later.') }}
                        </p>
                        <div class="alert alert-warning small mb-3" role="status">
                            <strong>{{ __('Important') }}:</strong>
                            {{ __('Booking and other emails only go through this SMTP server after you check “Use custom SMTP from these settings” and click Save settings. If that box is unchecked, Laravel uses your .env mail driver (often “log”, which does not send real email).') }}
                        </div>

                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="smtp_enabled"
                                id="smtp_enabled"
                                value="1"
                                {{ old('smtp_enabled', $settings->smtp_enabled ? '1' : '') === '1' ? 'checked' : '' }}
                            >
                            <label class="form-check-label fw-semibold" for="smtp_enabled">{{ __('Use custom SMTP from these settings') }}</label>
                        </div>
                        @error('smtp_enabled')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="row g-3 mb-2">
                            <div class="col-md-8">
                                <label class="form-label" for="smtp_host">{{ __('SMTP host') }}</label>
                                <input
                                    id="smtp_host"
                                    type="text"
                                    name="smtp_host"
                                    value="{{ old('smtp_host', $settings->smtp_host) }}"
                                    class="form-control @error('smtp_host') is-invalid @enderror"
                                    autocomplete="off"
                                    placeholder="smtp.example.com"
                                >
                                @error('smtp_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="smtp_port">{{ __('Port') }}</label>
                                <input
                                    id="smtp_port"
                                    type="number"
                                    name="smtp_port"
                                    value="{{ old('smtp_port', $settings->smtp_port ?: 587) }}"
                                    class="form-control @error('smtp_port') is-invalid @enderror"
                                    min="1"
                                    max="65535"
                                >
                                @error('smtp_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="smtp_encryption">{{ __('Encryption') }}</label>
                            @php
                                $smtpEnc = old('smtp_encryption', $settings->smtp_encryption ?: 'tls');
                            @endphp
                            <select name="smtp_encryption" id="smtp_encryption" class="form-select @error('smtp_encryption') is-invalid @enderror">
                                <option value="tls" {{ $smtpEnc === 'tls' ? 'selected' : '' }}>{{ __('TLS (STARTTLS — common on port 587)') }}</option>
                                <option value="ssl" {{ $smtpEnc === 'ssl' ? 'selected' : '' }}>{{ __('SSL (implicit TLS — common on port 465)') }}</option>
                                <option value="none" {{ $smtpEnc === 'none' ? 'selected' : '' }}>{{ __('None') }}</option>
                            </select>
                            @error('smtp_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="smtp_username">{{ __('Username') }}</label>
                                <input
                                    id="smtp_username"
                                    type="text"
                                    name="smtp_username"
                                    value="{{ old('smtp_username', $settings->smtp_username) }}"
                                    class="form-control @error('smtp_username') is-invalid @enderror"
                                    autocomplete="username"
                                >
                                @error('smtp_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="smtp_password">{{ __('Password') }}</label>
                                <input
                                    id="smtp_password"
                                    type="password"
                                    name="smtp_password"
                                    value=""
                                    class="form-control @error('smtp_password') is-invalid @enderror"
                                    autocomplete="new-password"
                                    placeholder="{{ filled($settings->smtp_password) ? __('Leave blank to keep current password') : __('Required when enabling custom SMTP') }}"
                                >
                                <p class="form-text text-muted small mb-0">
                                    {{ __('Gmail: use an App Password (Google Account → Security → 2-Step Verification → App passwords), not your normal login password, if 2-step verification is on.') }}
                                    {{ __('Typical Gmail: host smtp.gmail.com, port 587 + TLS, or port 465 + SSL — not port 443.') }}
                                </p>
                                @error('smtp_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="smtp_from_address">{{ __('From email address') }}</label>
                                <input
                                    id="smtp_from_address"
                                    type="email"
                                    name="smtp_from_address"
                                    value="{{ old('smtp_from_address', $settings->smtp_from_address) }}"
                                    class="form-control @error('smtp_from_address') is-invalid @enderror"
                                >
                                @error('smtp_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="smtp_from_name">{{ __('From name') }}</label>
                                <input
                                    id="smtp_from_name"
                                    type="text"
                                    name="smtp_from_name"
                                    value="{{ old('smtp_from_name', $settings->smtp_from_name) }}"
                                    class="form-control @error('smtp_from_name') is-invalid @enderror"
                                >
                                @error('smtp_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-2">{{ __('Host approval option') }}</h6>
                        <p class="text-muted small mb-3">
                            {{ __('When enabled, new “Become a Host” applications skip manual approval: a host account is created immediately, login details are emailed to the applicant, and administrators receive a dashboard notification.') }}
                        </p>
                        <div class="form-check mb-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="host_registration_auto_approve"
                                id="host_registration_auto_approve"
                                value="1"
                                {{ old('host_registration_auto_approve', $settings->host_registration_auto_approve ? '1' : '') === '1' ? 'checked' : '' }}
                            >
                            <label class="form-check-label fw-semibold" for="host_registration_auto_approve">{{ __('Host approval option (auto-approve registrations, no admin review)') }}</label>
                        </div>
                        @error('host_registration_auto_approve')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @include('admin.settings.partials.notification-email-templates', ['settings' => $settings])

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">{{ __('Save settings') }}</button>
                            <a class="btn btn-light" href="{{ route('dashboard') }}">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="brandingMediaPickerModal" tabindex="-1" aria-labelledby="brandingMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandingMediaPickerModalLabel">{{ __('Choose an image from media library') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">{{ __('Only raster images from your library are listed (JPEG, PNG, GIF, WebP).') }}</p>
                    <div id="brandingMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="brandingMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="brandingMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('No images in the library yet.') }}
                        <a href="{{ route('admin.media.index') }}">{{ __('Open Media') }}</a>
                    </div>
                    <div id="brandingMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var pickerUrl = @json(route('admin.media.picker-images'));
            var slot = 'header';
            var modalEl = document.getElementById('brandingMediaPickerModal');
            var gridEl = document.getElementById('brandingMediaPickerGrid');
            var loadingEl = document.getElementById('brandingMediaPickerLoading');
            var emptyEl = document.getElementById('brandingMediaPickerEmpty');
            var errorEl = document.getElementById('brandingMediaPickerError');
            if (!modalEl || !gridEl) {
                return;
            }

            function slotPrefix(s) {
                if (s === 'footer') {
                    return 'footer_logo';
                }
                if (s === 'hero') {
                    return 'home_hero_background';
                }

                return 'header_logo';
            }

            function clearSlotPreview(s) {
                var p = slotPrefix(s);
                var hid = document.getElementById(p + '_media_id');
                var wrap = document.getElementById(p + '_media_preview_wrap');
                var img = document.getElementById(p + '_media_preview_img');
                var lab = document.getElementById(p + '_media_preview_label');
                var clr = document.getElementById(p + '_media_clear');
                if (hid) {
                    hid.value = '';
                }
                if (wrap) {
                    wrap.classList.add('d-none');
                }
                if (img) {
                    img.classList.add('d-none');
                    img.removeAttribute('src');
                }
                if (lab) {
                    lab.textContent = '';
                }
                if (clr) {
                    clr.classList.add('d-none');
                }
            }

            function setSlotFromMedia(s, item) {
                var p = slotPrefix(s);
                var hid = document.getElementById(p + '_media_id');
                var wrap = document.getElementById(p + '_media_preview_wrap');
                var img = document.getElementById(p + '_media_preview_img');
                var lab = document.getElementById(p + '_media_preview_label');
                var clr = document.getElementById(p + '_media_clear');
                if (hid) {
                    hid.value = String(item.id);
                }
                if (wrap) {
                    wrap.classList.remove('d-none');
                }
                if (img && item.preview_url) {
                    img.src = item.preview_url;
                    img.classList.remove('d-none');
                }
                if (lab) {
                    lab.textContent = item.name || '';
                }
                if (clr) {
                    clr.classList.remove('d-none');
                }
            }

            document.querySelectorAll('[data-bs-target="#brandingMediaPickerModal"][data-branding-slot]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var target = btn.getAttribute('data-branding-slot');
                    slot = target === 'footer' || target === 'hero' ? target : 'header';
                });
            });

            ['header_logo', 'footer_logo', 'home_hero_background'].forEach(function (p) {
                var clr = document.getElementById(p + '_media_clear');
                if (clr) {
                    clr.addEventListener('click', function () {
                        clearSlotPreview(p);
                    });
                }
            });

            modalEl.addEventListener('show.bs.modal', function () {
                if (loadingEl) {
                    loadingEl.classList.remove('d-none');
                }
                if (emptyEl) {
                    emptyEl.classList.add('d-none');
                }
                if (errorEl) {
                    errorEl.classList.add('d-none');
                    errorEl.textContent = '';
                }
                gridEl.classList.add('d-none');
                gridEl.innerHTML = '';

                fetch(pickerUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                })
                    .then(function (r) {
                        if (!r.ok) {
                            throw new Error('HTTP ' + r.status);
                        }
                        return r.json();
                    })
                    .then(function (json) {
                        var rows = json && json.data ? json.data : [];
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (!rows.length) {
                            if (emptyEl) {
                                emptyEl.classList.remove('d-none');
                            }
                            return;
                        }
                        rows.forEach(function (item) {
                            var col = document.createElement('div');
                            col.className = 'col-6 col-sm-4 col-md-3';
                            var btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-light border w-100 p-1 text-start h-100';
                            btn.innerHTML =
                                '<span class="d-block ratio ratio-1x1 bg-light rounded overflow-hidden mb-1">' +
                                '<img src="" alt="" class="object-fit-contain w-100 h-100" loading="lazy">' +
                                '</span>' +
                                '<span class="small text-truncate d-block" title=""></span>';
                            var im = btn.querySelector('img');
                            var cap = btn.querySelector('span.small');
                            if (im) {
                                im.src = item.preview_url || '';
                            }
                            if (cap) {
                                cap.textContent = item.name || '';
                                cap.setAttribute('title', item.name || '');
                            }
                            btn.addEventListener('click', function () {
                                setSlotFromMedia(slot, item);
                                var inst = window.bootstrap && window.bootstrap.Modal.getInstance(modalEl);
                                if (inst) {
                                    inst.hide();
                                }
                            });
                            col.appendChild(btn);
                            gridEl.appendChild(col);
                        });
                        gridEl.classList.remove('d-none');
                    })
                    .catch(function () {
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (errorEl) {
                            errorEl.textContent = @json(__('Could not load media library.'));
                            errorEl.classList.remove('d-none');
                        }
                    });
            });
        })();
    </script>
@endpush
