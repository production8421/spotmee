@php
    /** @var \App\Models\Coupon|null $coupon */
    $coupon = $coupon ?? null;
    $isEdit = $coupon !== null;
    /** @var \Illuminate\Support\Collection<int, \App\Models\User>|\Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $hosts */
    $hosts = $hosts ?? collect();
    /** @var \Illuminate\Support\Collection<int, \App\Models\GymListing>|\Illuminate\Database\Eloquent\Collection<int, \App\Models\GymListing> $gymListings */
    $gymListings = $gymListings ?? collect();
    $selectedHostIds = old('host_ids', $coupon ? $coupon->hosts->pluck('id')->map(fn ($id) => (int) $id)->all() : []);
    $selectedGymIds = old('gym_listing_ids', $coupon ? $coupon->gymListings->pluck('id')->map(fn ($id) => (int) $id)->all() : []);
    if (! is_array($selectedHostIds)) {
        $selectedHostIds = [];
    }
    if (! is_array($selectedGymIds)) {
        $selectedGymIds = [];
    }
    $selectedHostIds = array_map('intval', $selectedHostIds);
    $selectedGymIds = array_map('intval', $selectedGymIds);
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="coupon-code">{{ __('Code') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <input
                class="form-control @error('code') is-invalid @enderror"
                id="coupon-code"
                name="code"
                type="text"
                value="{{ old('code', $coupon?->code) }}"
                maxlength="64"
                autocomplete="off"
                required
            >
            <button
                type="button"
                class="btn btn-outline-secondary"
                id="coupon-generate-code"
                data-url="{{ route('admin.coupons.generate-code', array_filter(['except' => $coupon?->id])) }}"
            >{{ __('Autogenerate') }}</button>
        </div>
        <div class="form-text">{{ __('Letters, numbers, hyphens, and underscores. Stored in uppercase.') }}</div>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6" id="coupon-slot-sessions-wrap">
        <label class="form-label" for="coupon-valid-sessions">{{ __('Valid sessions') }} <span class="text-danger">*</span></label>
        <input
            class="form-control @error('valid_sessions') is-invalid @enderror"
            id="coupon-valid-sessions"
            name="valid_sessions"
            type="number"
            inputmode="numeric"
            min="1"
            max="100000"
            step="1"
            value="{{ old('valid_sessions', $coupon?->valid_sessions ?? 1) }}"
            required
        >
        <div class="form-text">
            {{ __('Total number of free time slots each guest or subscriber can use with this code across bookings (confirmed bookings count toward their limit). Gym slot fees are waived for those slots; personal trainer add-ons are still charged when selected.') }}
        </div>
        @error('valid_sessions')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <hr class="text-muted my-1">
        <div class="form-check mb-2">
            <input
                class="form-check-input @error('percent_discount_enabled') is-invalid @enderror"
                type="checkbox"
                name="percent_discount_enabled"
                value="1"
                id="coupon-percent-enabled"
                @checked(old('percent_discount_enabled', $coupon?->percent_discount_enabled ?? false))
            >
            <label class="form-check-label fw-semibold" for="coupon-percent-enabled">
                {{ __('Percentage discount on total price') }}
            </label>
            <div class="form-text">
                {{ __('When enabled, the discount is a percentage of the full booking total (gym slots plus personal trainer fees). Slot count does not change the rate—it always applies to the whole subtotal. The free-slot option above is ignored while this is on.') }}
            </div>
            @error('percent_discount_enabled')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div id="coupon-percent-value-wrap" class="row g-2" style="display: none;">
            <div class="col-md-6">
                <label class="form-label" for="coupon-percent-discount">{{ __('Percentage (%)') }} <span class="text-danger">*</span></label>
                <input
                    class="form-control @error('percent_discount') is-invalid @enderror"
                    id="coupon-percent-discount"
                    name="percent_discount"
                    type="number"
                    inputmode="decimal"
                    min="0.01"
                    max="100"
                    step="0.01"
                    value="{{ old('percent_discount', $coupon?->percent_discount) }}"
                >
                @error('percent_discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label" for="coupon-description">{{ __('Description') }}</label>
        <textarea
            class="form-control @error('description') is-invalid @enderror"
            id="coupon-description"
            name="description"
            rows="3"
            maxlength="500"
            placeholder="{{ __('Optional notes for your team.') }}"
        >{{ old('description', $coupon?->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <hr class="text-muted my-1">
        <h6 class="text-muted mb-3">{{ __('Limit where this coupon can be used') }}</h6>
    </div>
    <div class="col-12">
        <div class="row g-3 coupon-multiselect-row">
        <div class="col-md-6">
            <label class="form-label" for="coupon-host-ids">{{ __('Assign to hosts') }} <span class="text-muted fw-normal">({{ __('optional') }})</span></label>
            <select
                class="form-select @error('host_ids') is-invalid @enderror @error('host_ids.*') is-invalid @enderror"
                id="coupon-host-ids"
                name="host_ids[]"
                multiple
                data-placeholder="{{ __('Search or choose hosts…') }}"
            >
                @forelse ($hosts as $host)
                    <option value="{{ $host->id }}" @selected(in_array((int) $host->id, $selectedHostIds, true))>{{ $host->name }}</option>
                @empty
                    <option value="" disabled>{{ __('No hosts found') }}</option>
                @endforelse
            </select>
            <div class="form-text">
                {{ __('Searchable list with removable tags. Leave empty so this code works for any host’s gyms.') }}
            </div>
            @error('host_ids')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('host_ids.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="coupon-gym-ids">{{ __('Assign to gyms') }} <span class="text-muted fw-normal">({{ __('optional') }})</span></label>
            <select
                class="form-select @error('gym_listing_ids') is-invalid @enderror @error('gym_listing_ids.*') is-invalid @enderror"
                id="coupon-gym-ids"
                name="gym_listing_ids[]"
                multiple
                data-placeholder="{{ __('Search or choose gyms…') }}"
            >
                @forelse ($gymListings as $gym)
                    <option value="{{ $gym->id }}" @selected(in_array((int) $gym->id, $selectedGymIds, true))>
                        {{ $gym->name }} — {{ $gym->city }}, {{ $gym->state }}
                    </option>
                @empty
                    <option value="" disabled>{{ __('No gym listings found') }}</option>
                @endforelse
            </select>
            <div class="form-text">
                {{ __('Search by name or city. Leave empty so this code works at any gym.') }}
            </div>
            @error('gym_listing_ids')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('gym_listing_ids.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input
                class="form-check-input @error('is_active') is-invalid @enderror"
                id="coupon-active"
                name="is_active"
                type="checkbox"
                value="1"
                @checked((bool) filter_var(old('is_active', $coupon === null ? true : $coupon->is_active), FILTER_VALIDATE_BOOLEAN))
            >
            <label class="form-check-label" for="coupon-active">{{ __('Active') }}</label>
            @error('is_active')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    @if ($isEdit)
        @php
            $slotsRedeemedAll = (int) $coupon->gymBookings()->where('status', 'confirmed')->sum('coupon_applied_slots');
        @endphp
        <div class="col-12">
            <p class="text-muted small mb-0">
                {{ __('Slots redeemed (all bookings)') }}: <strong>{{ $slotsRedeemedAll }}</strong>
            </p>
        </div>
    @endif
</div>
<script>
(function () {
    function couponDiscountModeSync() {
        var cb = document.getElementById('coupon-percent-enabled');
        var pctWrap = document.getElementById('coupon-percent-value-wrap');
        var slotWrap = document.getElementById('coupon-slot-sessions-wrap');
        var sessionsInput = document.getElementById('coupon-valid-sessions');
        var pctInput = document.getElementById('coupon-percent-discount');
        if (!cb || !pctWrap || !slotWrap) return;
        var on = cb.checked;
        pctWrap.style.display = on ? 'block' : 'none';
        slotWrap.style.display = on ? 'none' : 'block';
        if (sessionsInput) {
            sessionsInput.required = !on;
            sessionsInput.readOnly = on;
        }
        if (pctInput) {
            pctInput.required = on;
            pctInput.readOnly = !on;
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        var cb = document.getElementById('coupon-percent-enabled');
        if (cb) {
            couponDiscountModeSync();
            cb.addEventListener('change', couponDiscountModeSync);
        }
    });
})();
</script>
