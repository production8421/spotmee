@extends('layouts.cuba.guest')

@section('title', __('Log in').' — '.config('app.name'))

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
                            @if (session('status'))
                                <div class="alert alert-success outline alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
                                </div>
                            @endif

                            <form class="theme-form" method="POST" action="{{ route('login') }}" novalidate>
                                @csrf
                                <h4>{{ __('Sign in to account') }}</h4>
                                <p>{{ __('Enter your email and password to log in.') }}</p>

                                <div class="form-group">
                                    <label class="col-form-label" for="email">{{ __('Email address') }}</label>
                                    <input
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        autofocus
                                        placeholder="name@example.com"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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
                                            autocomplete="current-password"
                                            placeholder="*********"
                                        >
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <div class="form-check">
                                        <input
                                            class="checkbox-primary form-check-input"
                                            id="remember"
                                            type="checkbox"
                                            name="remember"
                                            value="1"
                                            @checked(old('remember'))
                                        >
                                        <label class="text-muted form-check-label" for="remember">{{ __('Remember me') }}</label>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                                        @if (Route::has('password.request'))
                                            <a class="small" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                        @endif
                                    </div>
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Sign in') }}</button>
                                    </div>
                                    <p class="text-muted small mt-3 mb-0 text-center">
                                        <a href="{{ route('host.apply') }}">{{ __('Want to become a host? Apply here.') }}</a>
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
