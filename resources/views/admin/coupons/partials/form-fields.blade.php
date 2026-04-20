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
    <div class="col-md-6">
        <label class="form-label" for="coupon-discount-type">{{ __('Discount type') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('discount_type') is-invalid @enderror" id="coupon-discount-type" name="discount_type" required>
            @php $type = old('discount_type', $coupon?->discount_type ?? \App\Models\Coupon::TYPE_PERCENT); @endphp
            <option value="{{ \App\Models\Coupon::TYPE_PERCENT }}" @selected($type === \App\Models\Coupon::TYPE_PERCENT)>{{ __('Percent off') }}</option>
            <option value="{{ \App\Models\Coupon::TYPE_FIXED }}" @selected($type === \App\Models\Coupon::TYPE_FIXED)>{{ __('Fixed amount (USD)') }}</option>
        </select>
        @error('discount_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="coupon-applies-to">{{ __('Apply discount to') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('applies_to') is-invalid @enderror" id="coupon-applies-to" name="applies_to" required>
            @php
                $applies = old(
                    'applies_to',
                    $coupon?->applies_to ?? \App\Models\Coupon::APPLIES_FULL_BOOKING
                );
            @endphp
            <option value="{{ \App\Models\Coupon::APPLIES_FULL_BOOKING }}" @selected($applies === \App\Models\Coupon::APPLIES_FULL_BOOKING)>
                {{ __('Entire booking (gym slots + any add-ons in total)') }}
            </option>
            <option value="{{ \App\Models\Coupon::APPLIES_PERSONAL_TRAINING }}" @selected($applies === \App\Models\Coupon::APPLIES_PERSONAL_TRAINING)>
                {{ __('Personal training fees only') }}
            </option>
        </select>
        <div class="form-text">
            {{ __('Personal-training-only codes are valid when the guest books paid personal training; the discount reduces the trainer add-on portion only.') }}
        </div>
        @error('applies_to')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="coupon-discount-value">{{ __('Discount value') }} <span class="text-danger">*</span></label>
        <input
            class="form-control @error('discount_value') is-invalid @enderror"
            id="coupon-discount-value"
            name="discount_value"
            type="number"
            step="0.01"
            min="0.01"
            value="{{ old('discount_value', $coupon?->discount_value) }}"
            required
        >
        @error('discount_value')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="coupon-max">{{ __('Max redemptions') }}</label>
        <input
            class="form-control @error('max_redemptions') is-invalid @enderror"
            id="coupon-max"
            name="max_redemptions"
            type="number"
            min="1"
            value="{{ old('max_redemptions', $coupon?->max_redemptions) }}"
            placeholder="{{ __('Unlimited if empty') }}"
        >
        @error('max_redemptions')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="coupon-starts">{{ __('Valid from') }}</label>
        <input
            class="form-control @error('starts_at') is-invalid @enderror"
            id="coupon-starts"
            name="starts_at"
            type="date"
            value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d')) }}"
        >
        @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="coupon-ends">{{ __('Valid until') }}</label>
        <input
            class="form-control @error('ends_at') is-invalid @enderror"
            id="coupon-ends"
            name="ends_at"
            type="date"
            value="{{ old('ends_at', $coupon?->ends_at?->format('Y-m-d')) }}"
        >
        @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="coupon-description">{{ __('Description') }}</label>
        <textarea
            class="form-control @error('description') is-invalid @enderror"
            id="coupon-description"
            name="description"
            rows="2"
            maxlength="500"
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
        <div class="col-12">
            <p class="text-muted small mb-0">
                {{ __('Times used') }}: <strong>{{ (int) $coupon->times_used }}</strong>
                @if ($coupon->max_redemptions !== null)
                    / {{ (int) $coupon->max_redemptions }}
                @endif
            </p>
        </div>
    @endif
</div>
