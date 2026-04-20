@extends('layouts.cuba.guest')

@section('title', __('Verify email').' — '.config('app.name'))

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
                            <p class="text-muted mb-3">
                                {{ __('Thanks for signing up! Verify your email using the link we sent. If you did not receive it, you can request another.') }}
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success outline mb-3" role="alert">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button class="btn btn-primary w-100" type="submit">{{ __('Resend verification email') }}</button>
                                </form>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn btn-light w-100" type="submit">{{ __('Log out') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
