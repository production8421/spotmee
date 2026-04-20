@extends('layouts.cuba.guest')

@section('title', __('Forgot password').' — '.config('app.name'))

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
                            <div class="mb-3">
                                <p class="text-muted mb-0">{{ __('Forgot your password? No problem. Enter your email and we will send a reset link.') }}</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success outline alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
                                </div>
                            @endif

                            <form class="theme-form" method="POST" action="{{ route('password.email') }}" novalidate>
                                @csrf
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
                                    >
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Email password reset link') }}</button>
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
