@extends('layouts.web.master')
@section('title', 'How It Works — SPOTMEE')
@section('content')

<main class="spotmee-main">

    {{-- ============================================================
         1 · INNER BANNER
         ============================================================ --}}
    <section class="site-container pt-6 sm:pt-10">
        <div class="inner-banner"
             style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.85)] via-[rgba(0,109,119,0.55)] to-[rgba(131,197,190,0.25)]"></div>

            <div class="inner-banner__content">
                <span class="inner-banner__eyebrow" data-aos="fade-down">
                    <i class="fa-solid fa-compass"></i>
                    {{ __('How it works') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('How') }} <span class="text-[var(--color-brand-200)]">SPOTMEE</span> {{ __('Works') }}
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('Your guide to finding the perfect private workout space or sharing your own gym with the community.') }}
                </p>
            </div>
        </div>
    </section>


    {{-- ============================================================
         2 · FOR GYM SEEKERS  (3 steps)
         ============================================================ --}}
    @php
        $seekerSteps = [
            ['icon' => 'fa-solid fa-magnifying-glass', 'badge' => 'Step 01', 'title' => 'Discover Spaces',   'body' => "Enter your location and browse through a variety of unique, local private gyms. Filter by equipment and price."],
            ['icon' => 'fa-solid fa-calendar-days',    'badge' => 'Step 02', 'title' => 'Book Your Session', 'body' => 'Choose a date and time that fits your schedule. Pay securely and receive instant confirmation with access details.'],
            ['icon' => 'fa-solid fa-dumbbell',         'badge' => 'Step 03', 'title' => 'Work Out In Peace', 'body' => 'Arrive at your private gym and enjoy a focused workout without any interruptions. No memberships required.'],
        ];
    @endphp
    <section class="site-container py-20 sm:py-24">
        <div class="section-head" data-aos="fade-up">
            <span class="eyebrow">{{ __('For members') }}</span>
            <h2 class="section-head__title">
                {{ __('For') }} <span class="text-[var(--color-primary)]">{{ __('Gym Seekers') }}</span>
            </h2>
            <p class="section-head__subtitle">
                {{ __("Skip the crowds and commitments. Here's how you can start your private fitness journey.") }}
            </p>
        </div>

        <div class="relative grid grid-cols-1 gap-8 md:grid-cols-3 lg:gap-10">
            {{-- dashed connector (desktop) --}}
            <div class="pointer-events-none absolute left-[16.66%] right-[16.66%] top-[56px] hidden h-[2px] md:block"
                 style="background-image: linear-gradient(to right, var(--color-brand-200) 50%, transparent 50%); background-size: 12px 2px;"></div>

            @foreach ($seekerSteps as $index => $step)
                <div class="group relative flex flex-col items-center rounded-[24px] bg-white p-8 text-center shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 100) }}">
                    <div class="relative mb-6 flex h-[112px] w-[112px] items-center justify-center rounded-full bg-[var(--color-brand-50)] transition-colors duration-300 group-hover:bg-[var(--color-primary)]">
                        <i class="{{ $step['icon'] }} text-[32px] text-[var(--color-primary)] transition-colors duration-300 group-hover:text-white"></i>
                        <span class="absolute -right-2 -top-2 flex h-9 w-9 items-center justify-center rounded-full bg-[var(--color-primary)] text-[13px] font-bold text-white ring-4 ring-white">
                            {{ $index + 1 }}
                        </span>
                    </div>

                    <span class="eyebrow">{{ $step['badge'] }}</span>
                    <h3 class="mt-4 text-[20px] font-bold leading-tight text-[var(--color-ink-900)]">{{ $step['title'] }}</h3>
                    <p class="mt-3 text-[15px] leading-relaxed text-[var(--color-ink-500)]">{{ $step['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ============================================================
         3 · BECOME A HOST  (split layout)
         ============================================================ --}}
    @php
        $hostBullets = [
            ['title' => 'Full Control',   'body' => 'You decide when your gym is available and set your own hourly rates.'],
            ['title' => 'Safe & Secure',  'body' => 'All guests are verified. You are protected by our community guidelines and insurance support.'],
            ['title' => 'Easy Payments',  'body' => 'Receive automatic payouts directly to your bank account twice a month.'],
        ];
    @endphp
    <section class="relative bg-[var(--color-brand-50)] py-20 sm:py-24">
        <div class="site-container">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-20">
                <div class="order-2 lg:order-1" data-aos="fade-right">
                    <div class="relative">
                        <div class="pointer-events-none absolute -inset-6 -z-10 rounded-[40px] bg-gradient-to-br from-[var(--color-brand-200)]/30 via-white to-transparent blur-2xl"></div>
                        <img src="{{ asset('images/work-img-1.png') }}"
                             alt="{{ __('Host Your Gym') }}"
                             class="relative w-full rounded-[32px] object-cover shadow-[var(--shadow-lg)]">
                    </div>
                </div>

                <div class="order-1 lg:order-2" data-aos="fade-left">
                    <span class="eyebrow bg-white">{{ __('For hosts') }}</span>
                    <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                        style="font-size: var(--text-h1);">
                        {{ __('Become a') }} <span class="text-[var(--color-primary)]">{{ __('Host') }}</span>
                    </h2>
                    <p class="mt-4 max-w-lg text-[17px] leading-relaxed text-[var(--color-ink-700)]">
                        {{ __('Turn your home gym into a source of income. Join a community of fitness enthusiasts and help others reach their goals.') }}
                    </p>

                    <ul class="mt-8 space-y-6">
                        @foreach ($hostBullets as $i => $bullet)
                            <li class="flex gap-4" data-aos="fade-up" data-aos-delay="{{ 150 + ($i * 100) }}">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-[var(--shadow-md)]">
                                    <i class="fa-solid fa-check text-[13px]"></i>
                                </span>
                                <div>
                                    <h4 class="text-[17px] font-bold text-[var(--color-ink-900)]">{{ $bullet['title'] }}</h4>
                                    <p class="mt-1 text-[15px] leading-relaxed text-[var(--color-ink-500)]">{{ $bullet['body'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-10" data-aos="fade-up" data-aos-delay="500">
                        <a href="{{ route('become-a-host') }}" class="btn btn-primary btn-lg">
                            {{ __('Start Hosting Now') }}
                            <i class="fa-solid fa-arrow-right text-[13px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         4 · FEATURES GRID
         ============================================================ --}}
    @php
        $features = [
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Secure Access',    'body' => 'Digital key integration and smart access for seamless entry.'],
            ['icon' => 'fa-solid fa-star',          'title' => 'Review System',    'body' => 'Honest feedback from our community ensures high quality standards.'],
            ['icon' => 'fa-solid fa-comments',      'title' => 'In-App Messaging', 'body' => 'Communicate directly with hosts or guests through our secure portal.'],
            ['icon' => 'fa-solid fa-mobile-screen', 'title' => 'App Support',      'body' => 'Manage your bookings and gym listings on the go with our mobile app.'],
        ];
    @endphp
    <section class="site-container py-20 sm:py-24">
        <div class="section-head" data-aos="fade-up">
            <span class="eyebrow">{{ __('Built for you') }}</span>
            <h2 class="section-head__title">
                {{ __('Everything You Need To') }} <span class="text-[var(--color-primary)]">{{ __('Succeed') }}</span>
            </h2>
            <p class="section-head__subtitle">
                {{ __("We've built the tools to make private gym rental seamless for everyone.") }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-7">
            @foreach ($features as $index => $feature)
                <div class="group flex h-full flex-col rounded-[24px] border border-transparent bg-white p-7 shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 100) }}">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] transition-colors duration-300 group-hover:bg-[var(--color-primary)]">
                        <i class="{{ $feature['icon'] }} text-[22px] text-[var(--color-primary)] transition-colors duration-300 group-hover:text-white"></i>
                    </div>
                    <h3 class="text-[18px] font-bold leading-tight text-[var(--color-ink-900)]">{{ $feature['title'] }}</h3>
                    <p class="mt-3 text-[15px] leading-relaxed text-[var(--color-ink-500)]">{{ $feature['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ============================================================
         5 · FINAL CTA
         ============================================================ --}}
    <section class="site-container pb-20 sm:pb-24">
        <div class="relative overflow-hidden rounded-[28px] sm:rounded-[36px]"
             style="background-image: url('{{ asset('images/perfect.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.85)] via-[rgba(0,109,119,0.55)] to-[rgba(131,197,190,0.25)]"></div>

            <div class="relative z-10 flex min-h-[360px] flex-col items-center justify-center px-6 py-16 text-center sm:min-h-[420px] sm:px-10 sm:py-20">
                <span class="inner-banner__eyebrow" data-aos="fade-down">
                    <i class="fa-solid fa-bolt"></i>
                    {{ __('Join the community') }}
                </span>
                <h2 class="mt-5 font-bold leading-[1.05] text-white"
                    style="font-size: clamp(1.75rem, 3.5vw, 2.5rem);" data-aos="fade-up" data-aos-delay="100">
                    {{ __('Ready to Start Your') }}<br>{{ __('Private Workout Journey?') }}
                </h2>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row" data-aos="zoom-in" data-aos-delay="200">
                    <a href="{{ route('find-a-gym') }}" class="btn btn-lg bg-white text-[var(--color-primary)] hover:bg-[var(--color-brand-50)]">
                        {{ __('Find a Gym') }}
                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                    </a>
                    <a href="{{ route('become-a-host') }}" class="btn btn-lg border border-white/60 bg-white/10 text-white backdrop-blur-sm hover:bg-white/20">
                        {{ __('List Your Space') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

@endsection
