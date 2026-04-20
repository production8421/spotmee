@extends('layouts.cuba.guest')

@section('title', __('Become a host').' — '.config('app.name'))

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
        .login-card.host-apply-form .theme-form textarea.form-control {
            min-height: 7rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-2 px-sm-3">
        <div class="login-card login-dark host-apply-form">
            <div class="w-100 d-flex justify-content-center">
                <div class="login-main">
                    <form class="theme-form" method="POST" action="{{ route('host.apply.store') }}" novalidate>
                        @csrf
                        <a class="logo" href="{{ route('login') }}">
                            @include('cuba.partials.brand-header-images')
                        </a>

                        <h4 class="mt-2">{{ __('Become a host') }}</h4>
                        <p class="mb-2">{{ __('Apply to host on :app. Fields marked optional may be left blank.', ['app' => config('app.name')]) }}</p>
                        <p class="small mb-3">
                            <a href="{{ route('host.apply') }}">{{ __('Back to overview') }}</a>
                        </p>

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="full_name">{{ __('Full Name') }}</label>
                                    <input
                                        class="form-control @error('full_name') is-invalid @enderror"
                                        id="full_name"
                                        type="text"
                                        name="full_name"
                                        value="{{ old('full_name') }}"
                                        required
                                        autocomplete="name"
                                        autofocus
                                    >
                                    @error('full_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="date_of_birth">{{ __('Date of Birth') }}</label>
                                    <input
                                        class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth"
                                        type="date"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth') }}"
                                        required
                                        max="{{ now()->subDay()->toDateString() }}"
                                    >
                                    @error('date_of_birth')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="social_security_number">{{ __('Social Security Number') }} <span class="text-muted">({{ __('optional') }})</span></label>
                                    <input
                                        class="form-control @error('social_security_number') is-invalid @enderror"
                                        id="social_security_number"
                                        type="text"
                                        name="social_security_number"
                                        value="{{ old('social_security_number') }}"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        placeholder="XXX-XX-XXXX"
                                    >
                                    @error('social_security_number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="phone">{{ __('Phone Number') }}</label>
                                    <input
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        required
                                        autocomplete="tel"
                                    >
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="email">{{ __('Email Address') }}</label>
                                    <input
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email', auth()->user()?->email) }}"
                                        required
                                        autocomplete="email"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="street_address">{{ __('Street Address') }}</label>
                                    <input
                                        class="form-control @error('street_address') is-invalid @enderror"
                                        id="street_address"
                                        type="text"
                                        name="street_address"
                                        value="{{ old('street_address') }}"
                                        required
                                        autocomplete="street-address"
                                    >
                                    @error('street_address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="city">{{ __('City') }}</label>
                                    <input
                                        class="form-control @error('city') is-invalid @enderror"
                                        id="city"
                                        type="text"
                                        name="city"
                                        value="{{ old('city') }}"
                                        required
                                        autocomplete="address-level2"
                                    >
                                    @error('city')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="state">{{ __('State') }}</label>
                                    <input
                                        class="form-control @error('state') is-invalid @enderror"
                                        id="state"
                                        type="text"
                                        name="state"
                                        value="{{ old('state') }}"
                                        required
                                        autocomplete="address-level1"
                                    >
                                    @error('state')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="postal_code">{{ __('Postal Code') }}</label>
                                    <input
                                        class="form-control @error('postal_code') is-invalid @enderror"
                                        id="postal_code"
                                        type="text"
                                        name="postal_code"
                                        value="{{ old('postal_code') }}"
                                        required
                                        autocomplete="postal-code"
                                    >
                                    @error('postal_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="description">{{ __('Description') }} <span class="text-muted">({{ __('optional') }})</span></label>
                                    <textarea
                                        class="form-control @error('description') is-invalid @enderror"
                                        id="description"
                                        name="description"
                                        rows="5"
                                        maxlength="5000"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 pt-1">
                                    <button class="btn btn-primary w-100" type="submit">{{ __('Submit application') }}</button>
                                    <p class="text-muted small mt-3 mb-0 text-center">
                                        <a href="{{ route('login') }}">{{ __('Already have an account? Sign in') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
