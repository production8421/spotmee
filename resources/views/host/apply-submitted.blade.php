@extends('layouts.cuba.guest')

@section('title', __('Registration Submitted').' — '.config('app.name'))

@section('content')
    <div class="container-fluid p-0">
        <div class="row m-0 justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo" href="{{ url('/') }}">
                                @include('cuba.partials.brand-header-images')
                            </a>
                        </div>
                        <div class="login-main">
                            <div class="theme-form text-center">
                                <div class="mb-4">
                                    <i class="fa-solid fa-circle-check text-success" style="font-size: 3.5rem;" aria-hidden="true"></i>
                                    <h3 class="mt-3 mb-2">{{ __('Registration Submitted!') }}</h3>
                                    <p class="text-muted mb-0">
                                        {{ __('Thank you for registering as a host on :app.', ['app' => config('app.name')]) }}
                                    </p>
                                </div>

                                <div class="card border border-2 border-primary rounded-3 mb-4 text-center shadow-none" style="background-color: rgba(13, 110, 253, 0.06);">
                                    <div class="card-body p-4">
                                        <i class="fa-solid fa-stopwatch text-primary fa-2x mb-3" aria-hidden="true"></i>
                                        <h5 class="text-primary mb-3">{{ __('Waiting for Admin Approval') }}</h5>
                                        <p class="text-muted mb-0 small text-start">
                                            {{ __('Your account is pending approval. Please allow up to') }}
                                            <strong>{{ __('24 hours') }}</strong>
                                            {{ __('for our team to review your application. Once approved, you will receive an email with your login credentials.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-start">
                                    <h6 class="fw-bold mb-3">{{ __('What happens next?') }}</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start mb-3">
                                            <i class="fa-solid fa-envelope text-primary me-2 mt-1" aria-hidden="true"></i>
                                            <span>{{ __('Check your email for updates') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start mb-3">
                                            <i class="fa-solid fa-clipboard-check text-primary me-2 mt-1" aria-hidden="true"></i>
                                            <span>{{ __('An administrator will review your application') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start mb-3">
                                            <i class="fa-solid fa-key text-primary me-2 mt-1" aria-hidden="true"></i>
                                            <span>{{ __('You will receive login credentials once approved') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start mb-0">
                                            <i class="fa-solid fa-house text-primary me-2 mt-1" aria-hidden="true"></i>
                                            <span>{{ __('You can start hosting on :app after approval', ['app' => config('app.name')]) }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <a class="btn btn-primary btn-block w-100" href="{{ url('/') }}">{{ __('Return to Home') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
