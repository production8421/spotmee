@extends('layouts.web.master')

@section('title', 'Become a Host — SPOTMEE')

@php
    $hostTerms   = $applicationSetting?->legal_host_terms_url;
    $hostPrivacy = $applicationSetting?->legal_host_privacy_url;
    $autoApproveEnabled = (bool) ($applicationSetting?->host_registration_auto_approve ?? false);
@endphp

@section('content')
    {{-- =====================================================================
         Hero / inner banner
    ===================================================================== --}}
    <section class="site-container pt-6 sm:pt-10">
        <div class="inner-banner"
             style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.88)] via-[rgba(0,109,119,0.6)] to-[rgba(131,197,190,0.3)]"></div>

            <div class="inner-banner__content">
                <span class="inner-banner__eyebrow" data-aos="fade-down">
                    <i class="fa-solid fa-shield-halved"></i>
                    {{ __('Become a host') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('Turn your space into') }}
                    <span class="text-[var(--color-brand-200)]">{{ __('steady income') }}</span>
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('Create your host account to start listing your private gym, studio, or training space on SPOTMEE.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Signup card — How it works + Why host + What you will need + Terms
    ===================================================================== --}}
    <section class="site-container py-14 sm:py-20">
        <div class="mx-auto max-w-5xl">

            {{-- Top card shell --}}
            <div class="relative overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] bg-white shadow-[var(--shadow-md)]"
                 data-aos="fade-up">

                {{-- Top brand band --}}
                <div class="relative bg-gradient-to-br from-[var(--color-primary)] via-[var(--color-brand-500)] to-[var(--color-brand-200)] px-6 py-8 text-center sm:px-10 sm:py-10">
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.18),transparent_60%)]"></div>

                    <div class="relative z-10 mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 text-white shadow-lg ring-1 ring-white/30 backdrop-blur-sm sm:h-20 sm:w-20">
                        <i class="fa-solid fa-shield-halved text-2xl sm:text-3xl"></i>
                    </div>
                    <h2 class="relative z-10 mt-5 text-2xl font-bold leading-tight text-white sm:text-3xl">
                        {{ __('Become a Host') }}
                    </h2>
                    <p class="relative z-10 mx-auto mt-2 max-w-xl text-[15px] text-white/85">
                        {{ __('Create your host account to start listing your private gym, studio, or training space on SPOTMEE.') }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="px-5 py-8 sm:px-10 sm:py-10">

                    {{-- Info grid: How it works + Why host with us --}}
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                        {{-- How it works --}}
                        <div class="rounded-2xl border border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_55%,#ffffff)]"
                             data-aos="fade-up" data-aos-delay="100">
                            <div class="flex items-center gap-3 border-b border-[var(--color-brand-100)] px-5 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[var(--color-primary)] shadow-sm">
                                    <i class="fa-solid fa-route text-[13px]"></i>
                                </span>
                                <strong class="text-[12px] font-semibold uppercase tracking-[0.1em] text-[var(--color-ink-500)]">
                                    {{ __('How it works') }}
                                </strong>
                            </div>
                            <ul class="divide-y divide-[var(--color-brand-100)] px-5 py-1">
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        <span class="font-semibold">1.</span> {{ __('Create your host account') }}
                                    </span>
                                </li>
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        <span class="font-semibold">2.</span>
                                        {{ $autoApproveEnabled
                                            ? __('Your host account is automatically approved')
                                            : __('Admin approves your account and listing') }}
                                    </span>
                                </li>
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        <span class="font-semibold">3.</span>
                                        {{ $autoApproveEnabled
                                            ? __('Your listing goes live immediately and you start reaching guests near you')
                                            : __('Your listing goes live and you start reaching guests near you') }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        {{-- Why host with us --}}
                        <div class="rounded-2xl border border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_55%,#ffffff)]"
                             data-aos="fade-up" data-aos-delay="150">
                            <div class="flex items-center gap-3 border-b border-[var(--color-brand-100)] px-5 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[var(--color-primary)] shadow-sm">
                                    <i class="fa-solid fa-trophy text-[13px]"></i>
                                </span>
                                <strong class="text-[12px] font-semibold uppercase tracking-[0.1em] text-[var(--color-ink-500)]">
                                    {{ __('Why host with us?') }}
                                </strong>
                            </div>
                            <ul class="divide-y divide-[var(--color-brand-100)] px-5 py-1">
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        {{ __('Reach people looking for spaces like yours') }}
                                    </span>
                                </li>
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        {{ __('Monetize unused hours') }}
                                    </span>
                                </li>
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        {{ __('Simple booking and payment management') }}
                                    </span>
                                </li>
                                <li class="flex items-start gap-3 py-3">
                                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                    <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                        {{ __('Showcase your equipment and what makes your place unique') }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- What you will need --}}
                    <div class="mt-5 rounded-2xl border border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_55%,#ffffff)]"
                         data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center gap-3 border-b border-[var(--color-brand-100)] px-5 py-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[var(--color-primary)] shadow-sm">
                                <i class="fa-solid fa-clipboard-list text-[13px]"></i>
                            </span>
                            <strong class="text-[12px] font-semibold uppercase tracking-[0.1em] text-[var(--color-ink-500)]">
                                {{ __('What you will need') }}
                            </strong>
                        </div>

                        <ul class="grid grid-cols-1 divide-y divide-[var(--color-brand-100)] px-5 sm:grid-cols-2 sm:divide-y-0 sm:gap-x-6 sm:[&>li]:border-0">
                            <li class="flex items-start gap-3 py-3">
                                <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </span>
                                <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                    {{ __('Full name and date of birth') }}
                                </span>
                            </li>
                            <li class="flex items-start gap-3 py-3">
                                <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </span>
                                <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                    {{ __('Contact information (phone, email)') }}
                                </span>
                            </li>
                            <li class="flex items-start gap-3 py-3">
                                <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </span>
                                <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                    {{ __('Address details') }}
                                </span>
                            </li>
                            <li class="flex items-start gap-3 py-3">
                                <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </span>
                                <span class="text-[14px] leading-snug text-[var(--color-ink-900)]">
                                    {{ __('Social Security Number (optional)') }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- Agreement + CTA --}}
                    <form method="POST" action="{{ route('host.apply.begin') }}" novalidate class="mt-8"
                          data-aos="fade-up" data-aos-delay="250">
                        @csrf

                        <label for="terms_accepted"
                               class="flex cursor-pointer items-start gap-3 rounded-2xl border border-[var(--color-brand-100)] bg-white p-4 transition-colors hover:border-[var(--color-primary)]">
                            <input type="checkbox"
                                   id="terms_accepted"
                                   name="terms_accepted"
                                   value="1"
                                   @checked(old('terms_accepted'))
                                   class="mt-0.5 h-5 w-5 flex-shrink-0 cursor-pointer rounded border-[var(--color-brand-200)] text-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/30">
                            <span class="text-[14px] leading-relaxed text-[var(--color-ink-700)]">
                                {{ __('I agree to the') }}
                                @if (filled($hostTerms))
                                    <a href="{{ $hostTerms }}" target="_blank" rel="noopener noreferrer"
                                       class="font-semibold text-[var(--color-primary)] underline underline-offset-2 hover:text-[var(--color-primary-hover)]">{{ __('Terms and Conditions') }}</a>
                                @else
                                    <a href="#"
                                       onclick="event.preventDefault();"
                                       class="font-semibold text-[var(--color-primary)] underline underline-offset-2 hover:text-[var(--color-primary-hover)]">{{ __('Terms and Conditions') }}</a>
                                @endif
                                {{ __('and') }}
                                @if (filled($hostPrivacy))
                                    <a href="{{ $hostPrivacy }}" target="_blank" rel="noopener noreferrer"
                                       class="font-semibold text-[var(--color-primary)] underline underline-offset-2 hover:text-[var(--color-primary-hover)]">{{ __('Privacy Policy') }}</a>
                                @else
                                    <a href="#"
                                       onclick="event.preventDefault();"
                                       class="font-semibold text-[var(--color-primary)] underline underline-offset-2 hover:text-[var(--color-primary-hover)]">{{ __('Privacy Policy') }}</a>
                                @endif
                            </span>
                        </label>
                        @error('terms_accepted')
                            <p class="mt-2 text-[13px] font-semibold text-red-600">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="btn btn-primary btn-lg mt-5 w-full justify-center">
                            {{ __('Create Account') }}
                            <i class="fa-solid fa-arrow-right text-[13px]"></i>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-[14px] text-[var(--color-ink-500)]">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}"
                           class="font-semibold text-[var(--color-primary)] underline underline-offset-2 hover:text-[var(--color-primary-hover)]">
                            {{ __('Sign in') }}
                        </a>
                    </p>
                </div>
            </div>

            {{-- Trust / reassurance row below the card --}}
            <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-3" data-aos="fade-up" data-aos-delay="300">
                <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white p-5 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                        <i class="fa-solid fa-lock text-[16px]"></i>
                    </span>
                    <h4 class="mt-3 text-[15px] font-bold text-[var(--color-ink-900)]">{{ __('Secure & private') }}</h4>
                    <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Your data is encrypted and never sold.') }}</p>
                </div>
                <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white p-5 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                        <i class="fa-solid fa-bolt text-[16px]"></i>
                    </span>
                    <h4 class="mt-3 text-[15px] font-bold text-[var(--color-ink-900)]">{{ __('Fast onboarding') }}</h4>
                    <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('List your facility in under 10 minutes.') }}</p>
                </div>
                <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white p-5 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                        <i class="fa-solid fa-hand-holding-dollar text-[16px]"></i>
                    </span>
                    <h4 class="mt-3 text-[15px] font-bold text-[var(--color-ink-900)]">{{ __('Direct payouts') }}</h4>
                    <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Earnings are deposited straight to your bank.') }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
