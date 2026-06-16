@extends('layouts.cuba.guest')

@section('title', __('Banking details').' — '.config('app.name'))

@push('styles')
    <style>
        .login-card.host-apply-form {
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 1.5rem;
            padding-bottom: 2.5rem;
        }
        .login-card.host-apply-form .login-main {
            width: 100%;
            max-width: min(56rem, calc(100vw - 2rem));
            box-sizing: border-box;
            padding: clamp(1.25rem, 4vw, 2.5rem);
        }
        .login-card.host-apply-form .theme-form .form-group {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-2 px-sm-3">
        <div class="login-card login-dark host-apply-form">
            <div class="w-100 d-flex justify-content-center">
                <div class="login-main">
                    <form class="theme-form" method="POST" action="{{ route('host.apply.banking.store') }}" novalidate autocomplete="off">
                        @csrf
                        <a class="logo" href="{{ route('login') }}">
                            @include('cuba.partials.brand-header-images')
                        </a>

                        <h4 class="mt-2">{{ __('Become a host') }}</h4>
                        <p class="mb-2">{{ __('Add your bank account details so we can send your payouts.') }}</p>
                        <p class="small mb-3">
                            <a href="{{ route('host.apply.create') }}">{{ __('Back to application') }}</a>
                        </p>

                        @include('host.partials.apply-progress', ['step' => 3])

                        <div class="alert alert-light border mb-4 text-start" role="note">
                            <i class="fa-solid fa-lock text-primary me-2" aria-hidden="true"></i>
                            {{ __('Your banking information is encrypted and only used for host payouts.') }}
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="account_holder_name">{{ __('Account holder name') }}</label>
                                    <input
                                        class="form-control @error('account_holder_name') is-invalid @enderror"
                                        id="account_holder_name"
                                        type="text"
                                        name="account_holder_name"
                                        value="{{ old('account_holder_name') }}"
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
                                        value="{{ old('bank_name') }}"
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
                                        <option value="checking" @selected(old('account_type') === 'checking')>{{ __('Checking') }}</option>
                                        <option value="savings" @selected(old('account_type') === 'savings')>{{ __('Savings') }}</option>
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
                                    >{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 pt-1">
                                    <button class="btn btn-primary w-100" type="submit">{{ __('Submit application') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
