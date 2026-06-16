@php
    $bankingDetail = $bankingDetail ?? null;
    $accountHolderName = old('account_holder_name', $bankingDetail?->account_holder_name);
    $bankName = old('bank_name', $bankingDetail?->bank_name);
    $accountType = old('account_type', $bankingDetail?->account_type);
    $notes = old('notes', $bankingDetail?->notes);
@endphp

<div class="row g-3">
    <div class="col-12">
        <div class="form-group">
            <label class="col-form-label" for="account_holder_name">{{ __('Account holder name') }}</label>
            <input
                class="form-control @error('account_holder_name') is-invalid @enderror"
                id="account_holder_name"
                type="text"
                name="account_holder_name"
                value="{{ $accountHolderName }}"
                required
                autocomplete="name"
                spellcheck="false"
            >
            @error('account_holder_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-form-label" for="bank_name">{{ __('Bank name') }}</label>
            <input
                class="form-control @error('bank_name') is-invalid @enderror"
                id="bank_name"
                type="text"
                name="bank_name"
                value="{{ $bankName }}"
                required
            >
            @error('bank_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-form-label" for="account_type">{{ __('Account type') }}</label>
            <select
                class="form-select @error('account_type') is-invalid @enderror"
                id="account_type"
                name="account_type"
                required
            >
                <option value="">{{ __('Select account type') }}</option>
                <option value="checking" @selected($accountType === 'checking')>{{ __('Checking') }}</option>
                <option value="savings" @selected($accountType === 'savings')>{{ __('Savings') }}</option>
            </select>
            @error('account_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-form-label" for="routing_number">{{ __('Routing number') }}</label>
            <input
                class="form-control @error('routing_number') is-invalid @enderror"
                id="routing_number"
                type="password"
                name="routing_number"
                required
                inputmode="numeric"
                maxlength="9"
                autocomplete="off"
                spellcheck="false"
                placeholder="123456789"
            >
            @error('routing_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-form-label" for="account_number">{{ __('Account number') }}</label>
            <input
                class="form-control @error('account_number') is-invalid @enderror"
                id="account_number"
                type="password"
                name="account_number"
                required
                inputmode="numeric"
                maxlength="17"
                autocomplete="off"
                spellcheck="false"
            >
            @error('account_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-form-label" for="account_number_confirmation">{{ __('Confirm account number') }}</label>
            <input
                class="form-control @error('account_number_confirmation') is-invalid @enderror"
                id="account_number_confirmation"
                type="password"
                name="account_number_confirmation"
                required
                inputmode="numeric"
                maxlength="17"
                autocomplete="off"
                spellcheck="false"
            >
            @error('account_number_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label class="col-form-label" for="notes">{{ __('Notes') }} <span class="text-muted">({{ __('optional') }})</span></label>
            <textarea
                class="form-control @error('notes') is-invalid @enderror"
                id="notes"
                name="notes"
                rows="3"
                maxlength="1000"
            >{{ $notes }}</textarea>
            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
