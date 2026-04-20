@extends('layouts.cuba.guest')

@section('title', __('Reset password').' — '.config('app.name'))

@section('content')
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo" href="{{ route('login') }}">
                                @include('cuba.partials.brand-header-images')
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('password.store') }}" novalidate>
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                                <h4>{{ __('Set new password') }}</h4>
                                <p class="text-muted">{{ __('Choose a strong password for your account.') }}</p>

                                <div class="form-group">
                                    <label class="col-form-label" for="email_display">{{ __('Email address') }}</label>
                                    <input
                                        class="form-control"
                                        id="email_display"
                                        type="email"
                                        value="{{ old('email', $request->email) }}"
                                        disabled
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label" for="password">{{ __('Password') }}</label>
                                    <div class="form-input position-relative">
                                        <input
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label" for="password_confirmation">{{ __('Confirm password') }}</label>
                                    <div class="form-input position-relative">
                                        <input
                                            class="form-control"
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Reset password') }}</button>
                                    <p class="text-muted small mt-3 mb-0 text-center">
                                        <a href="{{ route('login') }}">{{ __('Back to sign in') }}</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
