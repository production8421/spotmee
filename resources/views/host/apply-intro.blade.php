@extends('layouts.cuba.guest')

@section('title', __('Become a host').' — '.config('app.name'))

@push('styles')
    <style>
        /* Cuba login template fixes .login-main to 450px — widen for long host onboarding copy */
        .login-card.host-apply-intro {
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 1.5rem;
            padding-bottom: 2.5rem;
        }
        .login-card.host-apply-intro .login-main {
            width: 100%;
            max-width: min(52rem, calc(100vw - 2rem));
            box-sizing: border-box;
            padding: clamp(1.25rem, 4vw, 2.5rem);
        }
        /*
         * Cuba .login-main .theme-form input applies text-field background/border to every input,
         * which breaks checkbox rendering and hit targets. Reset checkboxes explicitly.
         */
        .login-card.host-apply-intro .theme-form input[type="checkbox"].form-check-input {
            background-color: #fff;
            width: 1.2em;
            height: 1.2em;
            min-width: 1.2em;
            min-height: 1.2em;
            margin-top: 0.3em;
            padding: 0;
            cursor: pointer;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
            float: none;
            vertical-align: top;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
        .login-card.host-apply-intro .theme-form input[type="checkbox"].form-check-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .login-card.host-apply-intro .theme-form input[type="checkbox"].form-check-input:checked {
            background-color: var(--theme-default, #7366ff);
            border-color: var(--theme-default, #7366ff);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-2 px-sm-3">
        <div class="login-card login-dark host-apply-intro">
            <div class="w-100 d-flex justify-content-center">
                <div class="login-main">
                    <div class="theme-form">
                        <a class="logo" href="{{ route('login') }}">
                            @include('cuba.partials.brand-header-images')
                        </a>

                        <div class="text-center mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white mb-3" style="width: 4rem; height: 4rem;">
                                <i class="fa-solid fa-shield-halved fa-2x" aria-hidden="true"></i>
                            </span>
                            <h3 class="mb-2">{{ __('Become a Host') }}</h3>
                            <p class="text-muted mb-0 px-sm-2">
                                {{ __('Create your host account to start listing your facilities on :app.', ['app' => config('app.name')]) }}
                            </p>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-lg-6">
                                <div class="card shadow-sm border rounded-3 h-100">
                                    <div class="card-body p-0">
                                        <div class="px-3 py-2 border-bottom bg-light">
                                            <strong class="small text-uppercase text-muted">{{ __('How it works') }}</strong>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('1. Create your host account') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('2. Admin approves your account and listing') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-start border-0 py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('3. Your listing is live and you reach more people on :app', ['app' => config('app.name')]) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow-sm border rounded-3 h-100">
                                    <div class="card-body p-0">
                                        <div class="px-3 py-2 border-bottom bg-light">
                                            <strong class="small text-muted">{{ __('Why host with us?') }} <span aria-hidden="true">&#127942;</span></strong>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('Reach people looking for spaces like yours') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('Monetize unused hours') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('Simple booking and payment management') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-start border-0 py-2 py-md-3">
                                                <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                                <span>{{ __('Showcase your equipment and what makes your place unique') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border rounded-3 mb-4">
                            <div class="card-body p-0">
                                <div class="px-3 py-2 border-bottom bg-light">
                                    <strong class="small text-muted">{{ __('What you will need') }} <span aria-hidden="true">&#128203;</span></strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                        <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                        <span>{{ __('Full name and date of birth') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                        <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                        <span>{{ __('Contact information (phone, email)') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start border-0 border-bottom py-2 py-md-3">
                                        <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                        <span>{{ __('Address details') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start border-0 py-2 py-md-3">
                                        <i class="fa-solid fa-circle-check text-primary me-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                        <span>{{ __('Social Security Number (optional)') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('host.apply.begin') }}" novalidate>
                            @csrf
                            <div class="form-group mb-4">
                                <div class="d-flex gap-3 align-items-start text-start">
                                    <input
                                        class="checkbox-primary form-check-input @error('terms_accepted') is-invalid @enderror"
                                        id="terms_accepted"
                                        type="checkbox"
                                        name="terms_accepted"
                                        value="1"
                                        @checked(old('terms_accepted'))
                                    >
                                    <div class="flex-grow-1 min-w-0">
                                        <label class="form-check-label text-muted mb-0" for="terms_accepted">
                                            {{ __('I agree to the') }}
                                            @php
                                                $hostTerms = $applicationSetting->legal_host_terms_url;
                                                $hostPrivacy = $applicationSetting->legal_host_privacy_url;
                                            @endphp
                                            @if (filled($hostTerms))
                                                <a href="{{ $hostTerms }}" class="text-primary text-decoration-underline" target="_blank" rel="noopener noreferrer">{{ __('Terms and Conditions') }}</a>
                                            @else
                                                <a href="#" class="text-primary text-decoration-underline" onclick="event.preventDefault();">{{ __('Terms and Conditions') }}</a>
                                            @endif
                                            {{ __('and') }}
                                            @if (filled($hostPrivacy))
                                                <a href="{{ $hostPrivacy }}" class="text-primary text-decoration-underline" target="_blank" rel="noopener noreferrer">{{ __('Privacy Policy') }}</a>
                                            @else
                                                <a href="#" class="text-primary text-decoration-underline" onclick="event.preventDefault();">{{ __('Privacy Policy') }}</a>
                                            @endif
                                        </label>
                                        @error('terms_accepted')
                                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">{{ __('Create Account') }}</button>
                        </form>

                        <p class="text-muted small mt-3 mb-0 text-center">
                            <a href="{{ route('login') }}">{{ __('Already have an account? Sign in') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
