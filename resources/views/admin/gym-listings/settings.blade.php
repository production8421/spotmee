@extends('layouts.cuba.app')

@php
    $gymSettingsSprite = asset(config('cuba.assets_path').'/svg/icon-sprite.svg');
@endphp

@section('title', __('Gym listings settings').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Gym listings settings') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gym-listings.index') }}">{{ __('Gym listings') }}</a></li>
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
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-start gap-3">
                        @include('admin.gym-listings.partials.settings-heading-icon', [
                            'sprite' => $gymSettingsSprite,
                            'stroke' => 'stroke-ecommerce',
                        ])
                        <div class="min-w-0">
                            <h5 class="mb-0 fw-semibold">{{ __('Stripe Payment Settings') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Connect Stripe to accept payments for gym bookings.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gym-listings.settings.update') }}" novalidate autocomplete="off">
                        @csrf
                        @method('PUT')

                        @php
                            $stripeMode = old('stripe_mode', $settings->stripe_mode ?? 'test');
                        @endphp
                        <div class="mb-4">
                            <fieldset>
                                <legend class="form-label fw-semibold mb-2">{{ __('Active Stripe keys') }}</legend>
                                <div class="row g-2">
                                    <div class="col-12 col-sm-6">
                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="stripe_mode"
                                            id="stripe_mode_test"
                                            value="test"
                                            autocomplete="off"
                                            @checked($stripeMode === 'test')
                                        >
                                        <label class="btn btn-outline-primary w-100 py-3 fw-semibold" for="stripe_mode_test">
                                            {{ __('Test keys active') }}
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="stripe_mode"
                                            id="stripe_mode_live"
                                            value="live"
                                            autocomplete="off"
                                            @checked($stripeMode === 'live')
                                        >
                                        <label class="btn btn-outline-primary w-100 py-3 fw-semibold" for="stripe_mode_live">
                                            {{ __('Live keys active') }}
                                        </label>
                                    </div>
                                </div>
                                <p class="text-muted small fst-italic mb-0 mt-2">
                                    {{ __('Choose which key pair is used for gym bookings and checkout. You can save both test and live credentials below and switch here at any time.') }}
                                </p>
                                @error('stripe_mode')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </fieldset>
                        </div>

                        <hr class="text-muted">

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="stripe_test_publishable_key">{{ __('Test Publishable Key') }}</label>
                            <input
                                class="form-control font-monospace @error('stripe_test_publishable_key') is-invalid @enderror"
                                id="stripe_test_publishable_key"
                                type="text"
                                name="stripe_test_publishable_key"
                                value="{{ old('stripe_test_publishable_key', $settings->stripe_test_publishable_key) }}"
                                placeholder="pk_test_…"
                                spellcheck="false"
                                autocapitalize="off"
                                autocomplete="off"
                            >
                            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Get this from your Stripe Dashboard → Developers → API keys.') }}</p>
                            @error('stripe_test_publishable_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="stripe_test_secret_key">{{ __('Test Secret Key') }}</label>
                            @if ($settings->hasStripeTestSecret())
                                <p class="small text-success mb-2">
                                    {{ __('A secret key is saved on the server. For security it is never filled into this box again.') }}
                                    <span class="d-block mt-1 font-monospace text-body">{{ $settings->maskedStripeTestSecretKey() }}</span>
                                </p>
                            @endif
                            <input
                                class="form-control font-monospace @error('stripe_test_secret_key') is-invalid @enderror"
                                id="stripe_test_secret_key"
                                type="password"
                                name="stripe_test_secret_key"
                                value=""
                                placeholder="sk_test_…"
                                spellcheck="false"
                                autocapitalize="off"
                                autocomplete="new-password"
                            >
                            @if ($settings->hasStripeTestSecret())
                                <p class="text-muted small mb-0 mt-1">{{ __('Leave blank to keep the current secret. Paste a new key only if you want to replace it.') }}</p>
                            @endif
                            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Keep this secret! Never share it publicly.') }}</p>
                            @error('stripe_test_secret_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="text-muted">

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="stripe_live_publishable_key">{{ __('Live Publishable Key') }}</label>
                            <input
                                class="form-control font-monospace @error('stripe_live_publishable_key') is-invalid @enderror"
                                id="stripe_live_publishable_key"
                                type="text"
                                name="stripe_live_publishable_key"
                                value="{{ old('stripe_live_publishable_key', $settings->stripe_live_publishable_key) }}"
                                placeholder="pk_live_…"
                                spellcheck="false"
                                autocapitalize="off"
                                autocomplete="off"
                            >
                            @error('stripe_live_publishable_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="stripe_live_secret_key">{{ __('Live Secret Key') }}</label>
                            @if ($settings->hasStripeLiveSecret())
                                <p class="small text-success mb-2">
                                    {{ __('A secret key is saved on the server. For security it is never filled into this box again.') }}
                                    <span class="d-block mt-1 font-monospace text-body">{{ $settings->maskedStripeLiveSecretKey() }}</span>
                                </p>
                            @endif
                            <input
                                class="form-control font-monospace @error('stripe_live_secret_key') is-invalid @enderror"
                                id="stripe_live_secret_key"
                                type="password"
                                name="stripe_live_secret_key"
                                value=""
                                placeholder="sk_live_…"
                                spellcheck="false"
                                autocapitalize="off"
                                autocomplete="new-password"
                            >
                            @if ($settings->hasStripeLiveSecret())
                                <p class="text-muted small mb-0 mt-1">{{ __('Leave blank to keep the current secret. Paste a new key only if you want to replace it.') }}</p>
                            @endif
                            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Keep this secret! Never share it publicly.') }}</p>
                            @error('stripe_live_secret_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="text-muted my-4">

                        @include('admin.gym-listings.partials.tier-pricing-block', [
                            'tier' => 'silver',
                            'tierTitle' => __('Silver Tier'),
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-price',
                            'tierIconStrokeClass' => 'text-secondary',
                            'settings' => $settings,
                        ])

                        @include('admin.gym-listings.partials.tier-pricing-block', [
                            'tier' => 'gold',
                            'tierTitle' => __('Gold Tier'),
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-ecommerce',
                            'tierIconStrokeClass' => 'text-warning',
                            'settings' => $settings,
                        ])

                        @include('admin.gym-listings.partials.tier-pricing-block', [
                            'tier' => 'platinum',
                            'tierTitle' => __('Platinum Tier'),
                            'tierHeadingClass' => 'text-primary',
                            'tierWrapperClass' => 'border rounded p-3 mb-4 bg-white border-start border-primary border-4',
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-widget',
                            'tierIconStrokeClass' => 'text-primary',
                            'settings' => $settings,
                        ])

                        <hr class="text-muted my-4">

                        <div class="rounded-3 border bg-light p-3 p-md-4 mb-4">
                            <div class="d-flex align-items-start gap-3">
                                @include('admin.gym-listings.partials.settings-heading-icon', [
                                    'sprite' => $gymSettingsSprite,
                                    'stroke' => 'stroke-learning',
                                ])
                                <div class="min-w-0">
                                    <h5 class="mb-2 fw-semibold">{{ __('Personal Trainer Level Pricing') }}</h5>
                                    <p class="text-muted small mb-0">
                                        {{ __('Configure the guest price per slot for each trainer level: Junior Trainer, Advanced Trainer, and Senior Trainer.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @include('admin.gym-listings.partials.personal-trainer-tier-block', [
                            'ptTier' => 'silver',
                            'tierTitle' => __('Personal Trainer – Junior Trainer'),
                            'tierHeadingClass' => 'text-primary',
                            'tierWrapperClass' => 'border rounded p-3 mb-4 bg-white border-start border-primary border-4',
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-price',
                            'tierIconStrokeClass' => 'text-secondary',
                            'settings' => $settings,
                        ])

                        @include('admin.gym-listings.partials.personal-trainer-tier-block', [
                            'ptTier' => 'gold',
                            'tierTitle' => __('Personal Trainer – Advanced Trainer'),
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-ecommerce',
                            'tierIconStrokeClass' => 'text-warning',
                            'settings' => $settings,
                        ])

                        @include('admin.gym-listings.partials.personal-trainer-tier-block', [
                            'ptTier' => 'platinum',
                            'tierTitle' => __('Personal Trainer – Senior Trainer'),
                            'tierHeadingClass' => 'text-primary',
                            'tierWrapperClass' => 'border rounded p-3 mb-4 bg-white border-start border-primary border-4',
                            'gymSettingsSprite' => $gymSettingsSprite,
                            'tierIconStroke' => 'stroke-widget',
                            'tierIconStrokeClass' => 'text-primary',
                            'settings' => $settings,
                        ])

                        <hr class="text-muted my-4">

                        <div class="mb-4">
                            <div class="d-flex align-items-start gap-3 mb-3 pb-2 border-bottom">
                                @include('admin.gym-listings.partials.settings-heading-icon', [
                                    'sprite' => $gymSettingsSprite,
                                    'stroke' => 'stroke-file',
                                ])
                                <div class="min-w-0">
                                    <h5 class="mb-1 fw-semibold">{{ __('Legal Pages URLs') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('Configure the URLs for Terms and Privacy Policy pages displayed in forms.') }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                @include('admin.gym-listings.partials.settings-heading-icon', [
                                    'sprite' => $gymSettingsSprite,
                                    'stroke' => 'stroke-calendar',
                                ])
                                <h6 class="text-primary mb-0 fw-semibold">{{ __('Booking Form URLs') }}</h6>
                            </div>
                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0 pt-md-2" for="legal_booking_terms_url">{{ __('Terms & Conditions URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('legal_booking_terms_url') is-invalid @enderror" id="legal_booking_terms_url" type="url" name="legal_booking_terms_url" value="{{ old('legal_booking_terms_url', $settings->legal_booking_terms_url) }}" placeholder="https://…" autocomplete="off">
                                    @error('legal_booking_terms_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-4 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="legal_booking_privacy_url">{{ __('Privacy Policy URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('legal_booking_privacy_url') is-invalid @enderror" id="legal_booking_privacy_url" type="url" name="legal_booking_privacy_url" value="{{ old('legal_booking_privacy_url', $settings->legal_booking_privacy_url) }}" placeholder="https://…" autocomplete="off">
                                    @error('legal_booking_privacy_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-3 mt-4">
                                @include('admin.gym-listings.partials.settings-heading-icon', [
                                    'sprite' => $gymSettingsSprite,
                                    'stroke' => 'stroke-home',
                                ])
                                <h6 class="text-primary mb-0 fw-semibold">{{ __('Host Registration Form URLs') }}</h6>
                            </div>
                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="legal_host_terms_url">{{ __('Terms & Conditions URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('legal_host_terms_url') is-invalid @enderror" id="legal_host_terms_url" type="url" name="legal_host_terms_url" value="{{ old('legal_host_terms_url', $settings->legal_host_terms_url) }}" placeholder="https://…" autocomplete="off">
                                    @error('legal_host_terms_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="legal_host_privacy_url">{{ __('Privacy Policy URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('legal_host_privacy_url') is-invalid @enderror" id="legal_host_privacy_url" type="url" name="legal_host_privacy_url" value="{{ old('legal_host_privacy_url', $settings->legal_host_privacy_url) }}" placeholder="https://…" autocomplete="off">
                                    @error('legal_host_privacy_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="booking_cancel_result_url">{{ __('Cancellation result page URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('booking_cancel_result_url') is-invalid @enderror" id="booking_cancel_result_url" type="url" name="booking_cancel_result_url" value="{{ old('booking_cancel_result_url', $settings->booking_cancel_result_url) }}" placeholder="https://…" autocomplete="off">
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('After a guest cancels a booking (via the email link), redirect them to this URL to show the cancellation message. Implement that page in your app or site as needed.') }}</p>
                                    @error('booking_cancel_result_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-0 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="legal_host_registration_url">{{ __('Host registration URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('legal_host_registration_url') is-invalid @enderror" id="legal_host_registration_url" type="url" name="legal_host_registration_url" value="{{ old('legal_host_registration_url', $settings->legal_host_registration_url) }}" placeholder="{{ __('Leave empty to use the default host application page') }}" autocomplete="off">
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Used for the “become a Host” link when no gyms match a search. If empty, the built-in host application route is used.') }}</p>
                                    @error('legal_host_registration_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="mb-4">
                            <div class="d-flex align-items-start gap-3 mb-3 pb-2 border-bottom">
                                @include('admin.gym-listings.partials.settings-heading-icon', [
                                    'sprite' => $gymSettingsSprite,
                                    'stroke' => 'stroke-api',
                                ])
                                <div class="min-w-0">
                                    <h5 class="mb-1 fw-semibold">{{ __('Webhooks') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('Optional: notify an external URL when a booking is completed or cancelled. The payload is sent as JSON (POST) with booking details.') }}</p>
                                </div>
                            </div>

                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="webhook_booking_completed_url">{{ __('Booking completed webhook URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('webhook_booking_completed_url') is-invalid @enderror" id="webhook_booking_completed_url" type="url" name="webhook_booking_completed_url" value="{{ old('webhook_booking_completed_url', $settings->webhook_booking_completed_url) }}" placeholder="https://…" autocomplete="off">
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Leave empty to disable. Called when a booking is confirmed (after payment or free checkout).') }}</p>
                                    @error('webhook_booking_completed_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-4 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="webhook_booking_completed_secret">{{ __('Webhook secret (optional)') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control font-monospace @error('webhook_booking_completed_secret') is-invalid @enderror" id="webhook_booking_completed_secret" type="password" name="webhook_booking_completed_secret" value="" placeholder="{{ __('Secret for X-RYJ-Signature header') }}" autocomplete="new-password" spellcheck="false">
                                    @if ($settings->hasWebhookBookingCompletedSecret())
                                        <p class="text-muted small mb-0 mt-1">{{ __('Leave blank to keep the current secret.') }}</p>
                                    @endif
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('If set, requests include X-RYJ-Signature (HMAC-SHA256 of the raw JSON body, prefixed with sha256=), plus X-RYJ-Event and User-Agent RentYourGym-Webhook/1.0. Up to 3 delivery attempts on non-2xx responses.') }}</p>
                                    @error('webhook_booking_completed_secret')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row mb-2 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="webhook_booking_cancelled_url">{{ __('Booking cancelled webhook URL') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control @error('webhook_booking_cancelled_url') is-invalid @enderror" id="webhook_booking_cancelled_url" type="url" name="webhook_booking_cancelled_url" value="{{ old('webhook_booking_cancelled_url', $settings->webhook_booking_cancelled_url) }}" placeholder="https://…" autocomplete="off">
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Leave empty to disable. Called when a guest cancels a booking (via email link).') }}</p>
                                    @error('webhook_booking_cancelled_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-0 g-3 align-items-start">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold mb-0" for="webhook_booking_cancelled_secret">{{ __('Cancelled webhook secret (optional)') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control font-monospace @error('webhook_booking_cancelled_secret') is-invalid @enderror" id="webhook_booking_cancelled_secret" type="password" name="webhook_booking_cancelled_secret" value="" placeholder="{{ __('Secret for X-RYJ-Signature header') }}" autocomplete="new-password" spellcheck="false">
                                    @if ($settings->hasWebhookBookingCancelledSecret())
                                        <p class="text-muted small mb-0 mt-1">{{ __('Leave blank to keep the current secret.') }}</p>
                                    @endif
                                    <p class="text-muted small fst-italic mb-0 mt-1">{{ __('If set, cancellation requests use the same signing and headers as completed webhooks (sha256= prefix, X-RYJ-Event, retries).') }}</p>
                                    @error('webhook_booking_cancelled_secret')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 pt-4 mt-2 border-top">
                            <button class="btn btn-primary" type="submit">{{ __('Save settings') }}</button>
                            <a class="btn btn-light" href="{{ route('admin.gym-listings.index') }}">{{ __('Back to listings') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    function parseNum(v) {
        if (v === null || v === undefined || v === '') return null;
        var n = parseFloat(String(v).replace(',', '.'));
        return Number.isFinite(n) ? n : null;
    }
    function formatMoney(n) {
        return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(n);
    }
    function updateTierTotals(tier) {
        var p1El = document.getElementById(tier + '_tier_price_1_hour');
        var c1El = document.getElementById(tier + '_tier_admin_commission_1_hour_pct');
        var out1 = document.getElementById(tier + '_total_1h');
        if (!p1El || !out1) return;
        var p1 = parseNum(p1El.value);
        var c1 = parseNum(c1El.value);
        var t1 = (p1 !== null && c1 !== null) ? p1 * (1 + c1 / 100) : null;
        out1.textContent = t1 !== null ? formatMoney(t1) : '—';
    }
    var tiers = ['silver', 'gold', 'platinum'];
    var suffixes = ['_tier_price_1_hour', '_tier_admin_commission_1_hour_pct'];
    tiers.forEach(function (tier) {
        suffixes.forEach(function (suf) {
            var el = document.getElementById(tier + suf);
            if (el) el.addEventListener('input', function () { updateTierTotals(tier); });
        });
        updateTierTotals(tier);
    });

    function updatePtTierTotals(ptTier) {
        var pEl = document.getElementById('pt_' + ptTier + '_price_per_slot');
        var cEl = document.getElementById('pt_' + ptTier + '_admin_commission_pct');
        var out = document.getElementById('pt_' + ptTier + '_total_slot');
        if (!pEl || !out) return;
        var p = parseNum(pEl.value);
        var c = cEl ? parseNum(cEl.value) : null;
        var t = (p !== null && c !== null) ? p * (1 + c / 100) : null;
        out.textContent = t !== null ? formatMoney(t) : '—';
    }
    ['silver', 'gold', 'platinum'].forEach(function (ptTier) {
        ['pt_' + ptTier + '_price_per_slot', 'pt_' + ptTier + '_admin_commission_pct'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('input', function () { updatePtTierTotals(ptTier); });
        });
        updatePtTierTotals(ptTier);
    });
})();
</script>
@endpush
