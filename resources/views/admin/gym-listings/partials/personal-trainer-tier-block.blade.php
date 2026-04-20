{{-- Expects: $ptTier (silver|gold|platinum), $tierTitle, $settings; optional $tierWrapperClass, $tierHeadingClass, $tierIconStroke, $tierIconStrokeClass, $gymSettingsSprite --}}
@php
    $gymSettingsSprite = $gymSettingsSprite ?? asset(config('cuba.assets_path').'/svg/icon-sprite.svg');
    $tierIconStroke = $tierIconStroke ?? 'stroke-price';
    $tierIconStrokeClass = $tierIconStrokeClass ?? 'text-primary';
    $priceKey = 'pt_'.$ptTier.'_price_per_slot';
    $commKey = 'pt_'.$ptTier.'_admin_commission_pct';
    $p = old($priceKey, $settings->{$priceKey});
    $c = old($commKey, $settings->{$commKey});
    $initTotal = \App\Models\ApplicationSetting::tierTotalWithCommission(
        $p !== null && $p !== '' ? (float) $p : null,
        $c !== null && $c !== '' ? (float) $c : null
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
            <label class="form-label fw-semibold" for="{{ $priceKey }}">{{ __('Price (per slot)') }}</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input
                    class="form-control @error($priceKey) is-invalid @enderror"
                    id="{{ $priceKey }}"
                    type="number"
                    name="{{ $priceKey }}"
                    value="{{ $p !== null && $p !== '' ? $p : '' }}"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                >
            </div>
            <p class="text-muted small fst-italic mb-0 mt-1">
                {{ __('Price per personal trainer slot (:tier tier)', ['tier' => ucfirst($ptTier)]) }}
            </p>
            @error($priceKey)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="{{ $commKey }}">{{ __('Admin Commission (%)') }}</label>
            <div class="input-group">
                <input
                    class="form-control @error($commKey) is-invalid @enderror"
                    id="{{ $commKey }}"
                    type="number"
                    name="{{ $commKey }}"
                    value="{{ $c !== null && $c !== '' ? $c : '' }}"
                    step="0.1"
                    min="0"
                    max="100"
                    placeholder="0"
                >
                <span class="input-group-text">%</span>
            </div>
            <p class="text-muted small fst-italic mb-0 mt-1">{{ __('Commission for personal trainer slot') }}</p>
            @error($commKey)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-4 pt-3 border-top">
        <p class="form-label fw-semibold mb-2">{{ __('Total per slot') }}</p>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-light-success rounded-pill px-3 py-2 fs-6 fw-normal">
                <span id="pt_{{ $ptTier }}_total_slot">@if ($initTotal !== null){{ '$'.number_format($initTotal, 2) }}@else—@endif</span>
            </span>
        </div>
        <p class="text-muted small fst-italic mb-0 mt-2">{{ __('Price + Commission Amount') }}</p>
    </div>
</div>
