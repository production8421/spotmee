{{-- Expects: $tier (silver|gold|platinum), $tierTitle, $settings; optional $tierIconStroke, $tierIconStrokeClass, $gymSettingsSprite --}}
@php
    $gymSettingsSprite = $gymSettingsSprite ?? asset(config('cuba.assets_path').'/svg/icon-sprite.svg');
    $tierIconStroke = $tierIconStroke ?? 'stroke-price';
    $tierIconStrokeClass = $tierIconStrokeClass ?? 'text-primary';
    $p1Key = $tier.'_tier_price_1_hour';
    $c1Key = $tier.'_tier_admin_commission_1_hour_pct';
    $p1 = old($p1Key, $settings->{$p1Key});
    $c1 = old($c1Key, $settings->{$c1Key});
    $init1 = \App\Models\ApplicationSetting::tierTotalWithCommission(
        $p1 !== null && $p1 !== '' ? (float) $p1 : null,
        $c1 !== null && $c1 !== '' ? (float) $c1 : null
    );
    $wrapperClass = (isset($tierWrapperClass) && is_string($tierWrapperClass) && $tierWrapperClass !== '')
        ? $tierWrapperClass
        : 'border rounded p-3 mb-4 bg-light';
@endphp
<div class="{{ $wrapperClass }}">
    <h5 class="mb-3 pb-2 border-bottom d-flex align-items-center gap-2 {{ $tierHeadingClass ?? '' }}">
        @include('admin.gym-listings.partials.settings-heading-icon', [
            'sprite' => $gymSettingsSprite,
            'stroke' => $tierIconStroke,
            'svgClass' => $tierIconStrokeClass,
        ])
        {{ $tierTitle }}
    </h5>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="{{ $p1Key }}">{{ __('Price (1 Hour)') }}</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input
                    class="form-control @error($p1Key) is-invalid @enderror"
                    id="{{ $p1Key }}"
                    type="number"
                    name="{{ $p1Key }}"
                    value="{{ $p1 !== null && $p1 !== '' ? $p1 : '' }}"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                >
            </div>
            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Price per 1 hour slot') }}</p>
            @error($p1Key)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="{{ $c1Key }}">{{ __('Admin Commission (1 Hour)') }}</label>
            <div class="input-group">
                <input
                    class="form-control @error($c1Key) is-invalid @enderror"
                    id="{{ $c1Key }}"
                    type="number"
                    name="{{ $c1Key }}"
                    value="{{ $c1 !== null && $c1 !== '' ? $c1 : '' }}"
                    step="0.1"
                    min="0"
                    max="100"
                    placeholder="0"
                >
                <span class="input-group-text">%</span>
            </div>
            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Commission for 1 hour slot') }}</p>
            @error($c1Key)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-4 pt-3 border-top">
        <p class="form-label fw-semibold mb-2">{{ __('Total price') }}</p>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-light-success rounded-pill px-3 py-2 fs-6 fw-normal">
                {{ __('1 Hour') }}: <span id="{{ $tier }}_total_1h">@if ($init1 !== null){{ '$'.number_format($init1, 2) }}@else—@endif</span>
            </span>
        </div>
        <p class="text-muted small fst-italic mb-0 mt-2">{{ __('Price + Commission Amount') }}</p>
    </div>
</div>
