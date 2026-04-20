@extends('layouts.cuba.guest')

@section('title', __('Register').' — '.config('app.name'))

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
                            <form class="theme-form" method="POST" action="{{ route('register') }}" novalidate>
                                @csrf
                                <h4>{{ __('Create account') }}</h4>
                                <p>{{ __('Enter your details to register on :app.', ['app' => config('app.name')]) }}</p>

                                <div class="form-group">
                                    <label class="col-form-label" for="name">{{ __('Name') }}</label>
                                    <input
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="name"
                                        autofocus
                                    >
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                            autocomplete="new-password"
                                            placeholder="*********"
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
                                            placeholder="*********"
                                        >
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Register') }}</button>
                                    <p class="text-muted small mt-3 mb-0 text-center">
                                        <a href="{{ route('login') }}">{{ __('Already registered? Sign in') }}</a>
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
