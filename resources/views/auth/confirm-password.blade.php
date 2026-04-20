@extends('layouts.cuba.guest')

@section('title', __('Confirm password').' — '.config('app.name'))

@section('content')
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo" href="{{ route('dashboard') }}">
                                @include('cuba.partials.brand-header-images')
                            </a>
                        </div>
                        <div class="login-main">
                            <p class="text-muted mb-3">{{ __('This is a secure area. Please confirm your password before continuing.') }}</p>

                            <form class="theme-form" method="POST" action="{{ route('password.confirm') }}" novalidate>
                                @csrf
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
                                        >
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-0 text-end">
                                    <button class="btn btn-primary w-100" type="submit">{{ __('Confirm') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
