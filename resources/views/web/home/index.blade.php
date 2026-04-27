@extends('layouts.web.master')
@section('title', 'SPOTMEE — Private gyms you can book by the hour')

@section('content')
@php
    /** @var \App\Models\ApplicationSetting|null $settings */
    $heroSettings    = $settings ?? null;
    $heroHeading     = $heroSettings?->homeHeroHeadingDisplay() ?? __('Find the Perfect Private Gym Near You');
    $heroBgKind      = $heroSettings?->homeHeroBackgroundKind() ?? 'color';
    $heroBgImageUrl  = $heroSettings?->homeHeroBackgroundPublicUrl();
    $heroBgColor     = $heroSettings?->homeHeroSolidColorCss() ?? '#edf6f9';
    $heroHasImage    = $heroBgKind === 'image' && filled($heroBgImageUrl);
    $heroPrimaryCta  = $heroSettings?->homeHeroCtaOne()   ?? ['label' => __('Book a Gym'),     'href' => route('find-a-gym')];
    $heroSecondaryCta= $heroSettings?->homeHeroCtaTwo()   ?? ['label' => __('Become a Host'), 'href' => route('become-a-host')];
@endphp

<main class="spotmee-main">

    {{-- ============================================================
         1 · HERO
         ============================================================ --}}
    <section class="site-container pt-6 sm:pt-10">
        <div class="relative overflow-hidden rounded-[28px] sm:rounded-[36px]"
             style="{{ $heroHasImage ? "background-image:url('{$heroBgImageUrl}');background-size:cover;background-position:center;" : "background-color:{$heroBgColor};" }}">

            {{-- decorative overlays --}}
            @if ($heroHasImage)
                <div class="absolute inset-0 bg-gradient-to-r from-[rgba(0,69,77,0.75)] via-[rgba(0,69,77,0.35)] to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-white/60 via-[rgba(237,246,249,0.3)] to-transparent"></div>
                <div class="pointer-events-none absolute -top-24 -right-20 h-80 w-80 rounded-full bg-[var(--color-brand-200)]/40 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-10 h-96 w-96 rounded-full bg-[var(--color-brand-50)]/80 blur-3xl"></div>
            @endif

            <div class="relative z-10 px-6 py-16 sm:px-10 sm:py-20 lg:px-16 lg:py-24">
                <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-14">

                    {{-- LEFT · Heading + CTAs --}}
                    <div class="lg:col-span-7" data-aos="fade-right">
                        <h1 class="font-bold tracking-tight leading-[1.05] {{ $heroHasImage ? 'text-white' : 'text-[var(--color-ink-900)]' }}"
                            style="font-size: clamp(2rem, 5vw, 3.5rem); max-width: 620px;">
                            {{ $heroHeading }}
                        </h1>

                        <div class="mt-10 flex flex-wrap gap-3">
                            <a href="{{ $heroPrimaryCta['href'] }}" class="btn btn-primary btn-lg">
                                {{ $heroPrimaryCta['label'] }}
                                <i class="fa-solid fa-arrow-right text-[13px]"></i>
                            </a>
                            <a href="{{ $heroSecondaryCta['href'] }}"
                               class="btn btn-lg {{ $heroHasImage ? 'bg-white/95 text-[var(--color-ink-900)] hover:bg-white' : 'btn-outline' }}">
                                {{ $heroSecondaryCta['label'] }}
                            </a>
                        </div>
                    </div>

                    {{-- RIGHT · Search card --}}
                    <div class="lg:col-span-5" data-aos="fade-left">
                        <div class="rounded-[24px] border border-white bg-white/95 p-6 shadow-[0_24px_60px_rgba(0,109,119,0.14)] backdrop-blur-xl sm:p-7">
                            <h2 class="mb-6 text-[20px] font-bold text-[var(--color-ink-900)] sm:text-[22px]">
                                {{ __('Find a home gym near you') }}
                            </h2>

                            <form method="GET" action="{{ route('find-a-gym') }}" class="space-y-5">
                                <div>
                                    <label class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-ink-500)]">
                                        {{ __('Location') }}
                                    </label>
                                    <div class="relative">
                                        <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-[var(--color-primary)]"></i>
                                        <input type="text"
                                               name="searchby"
                                               value="{{ request('searchby') }}"
                                               placeholder="{{ __('Enter city, neighbourhood or ZIP') }}"
                                               class="w-full rounded-xl bg-[var(--color-ink-50)] px-11 py-4 text-[15px] font-medium text-[var(--color-ink-900)] outline-none transition-all placeholder:text-[var(--color-ink-300)] focus:bg-white focus:ring-2 focus:ring-[var(--color-primary)]">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-full">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    {{ __('Find Gyms') }}
                                </button>
                            </form>

                            <p class="mt-5 text-center text-[14px] text-[var(--color-ink-500)]">
                                {{ __('Private gyms near you. No memberships. No waiting.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         2 · HOW IT WORKS
         ============================================================ --}}
    @php
        $defaultHow = [
            'heading' => 'How Works',
            'emphasis' => 'SPOTMEE',
            'steps' => [
                ['badge' => 'Step 1', 'title' => 'Find Home Gyms Near You', 'body' => 'Find verified home gyms nearby. Filter by equipment, workout type, ratings, and availability.', 'image_url' => asset('images/work-img-1.png')],
                ['badge' => 'Step 2', 'title' => 'Select Date & Book Now',   'body' => 'Select a date and time, and book your session easily no commitments required.',              'image_url' => asset('images/work-img-2.png')],
                ['badge' => 'Step 3', 'title' => 'Work Out Your Way',        'body' => 'Enjoy a clean, private workout. No crowds, no waiting—just you and your goals.',           'image_url' => asset('images/work-img-3.png')],
            ],
        ];
        $howSection      = $settings?->homeHowSectionForView() ?? $defaultHow;
        $howHeading      = (string) ($howSection['heading'] ?? $defaultHow['heading']);
        $howEmphasis     = trim((string) ($howSection['emphasis'] ?? $defaultHow['emphasis']));
        $headingParts    = ($howEmphasis !== '' && str_contains($howHeading, $howEmphasis))
            ? explode($howEmphasis, $howHeading, 2) : null;
        $howSteps        = is_array($howSection['steps'] ?? null) ? $howSection['steps'] : $defaultHow['steps'];
        $defaultStepDelays = [100, 250, 400];
    @endphp
    <section class="site-container py-20 sm:py-24">
        <div class="mx-auto mb-14 max-w-3xl text-center" data-aos="fade-up">
            <span class="eyebrow">{{ __('How it works') }}</span>
            <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                style="font-size: var(--text-h1);">
                @if ($headingParts !== null)
                    {{ $headingParts[0] }}<span class="text-[var(--color-primary)]">{{ $howEmphasis }}</span>{{ $headingParts[1] ?? '' }}
                @else
                    {{ $howHeading }}@if ($howEmphasis !== '') <span class="text-[var(--color-primary)]">{{ $howEmphasis }}</span>@endif
                @endif
                <span class="testing-poyooil"></span>
            </h2>
            <p class="mt-4 text-[16px] text-[var(--color-ink-500)]">
                {{ __('Three steps from discovering to working out — all on your schedule.') }}
            </p>
        </div>

        <div class="relative grid grid-cols-1 gap-10 md:grid-cols-3 lg:gap-12">
            {{-- dashed connector (desktop) --}}
            <div class="pointer-events-none absolute left-[16.66%] right-[16.66%] top-[140px] hidden h-[2px] md:block"
                 style="background-image: linear-gradient(to right, var(--color-brand-200) 50%, transparent 50%); background-size: 12px 2px;"></div>

            @foreach ($howSteps as $index => $step)
                @php
                    $fallback = $defaultHow['steps'][$index] ?? $defaultHow['steps'][0];
                    $stepBadge = trim((string) ($step['badge'] ?? $fallback['badge']));
                    $stepTitle = trim((string) ($step['title'] ?? $fallback['title']));
                    $stepBody  = trim((string) ($step['body']  ?? $fallback['body']));
                    $stepImage = $step['image_url'] ?? $fallback['image_url'];
                    $stepDelay = $defaultStepDelays[$index] ?? 100;
                @endphp
                <div class="group relative" data-aos="fade-up" data-aos-delay="{{ $stepDelay }}">
                    <div class="relative overflow-hidden rounded-[24px] bg-white shadow-[var(--shadow-sm)] transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-[var(--shadow-lg)]">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $stepImage }}"
                                 alt="{{ $stepTitle }}"
                                 width="640"
                                 height="480"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                 decoding="async"
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
                            <span class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-primary)] shadow-md">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[var(--color-primary)] text-[11px] text-white">{{ $index + 1 }}</span>
                                {{ $stepBadge }}
                            </span>
                        </div>
                        <div class="p-6 sm:p-7">
                            <h3 class="text-[20px] font-bold text-[var(--color-ink-900)] leading-tight">{{ $stepTitle }}</h3>
                            <p class="mt-3 text-[15px] leading-relaxed text-[var(--color-ink-500)]">{{ $stepBody }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ============================================================
         3 · POPULAR GYMS (slick slider — class names preserved)
         ============================================================ --}}
    @php
        $popularGyms = [
            ['image' => 'popular-gym-img-001.png', 'name' => 'The Strength Studio',   'type' => 'Private Garage Gym',    'rating' => '4.9', 'location' => 'Dallas, TX',   'distance' => '1.2 miles away', 'price' => '18'],
            ['image' => 'popular-gym-img-002.png', 'name' => 'The Cardio Loft',       'type' => 'Modern Home Fitness',    'rating' => '4.8', 'location' => 'Austin, TX',   'distance' => '3.4 miles away', 'price' => '15'],
            ['image' => 'popular-gym-img-003.png', 'name' => 'Ironclad Elite Gym',    'type' => 'Basement Training',      'rating' => '5.0', 'location' => 'Houston, TX',  'distance' => '2.0 miles away', 'price' => '22'],
            ['image' => 'popular-gym-img-004.png', 'name' => 'ZenFlex Pilates',       'type' => 'Core Studio',            'rating' => '4.9', 'location' => 'Miami, FL',    'distance' => '0.9 miles away', 'price' => '20'],
            ['image' => 'popular-gym-img-001.png', 'name' => 'Titan Fitness',         'type' => 'Heavy Lifting Zone',     'rating' => '4.7', 'location' => 'Chicago, IL',  'distance' => '2.5 miles away', 'price' => '16'],
            ['image' => 'popular-gym-img-002.png', 'name' => 'Urban Yoga Space',      'type' => 'Peaceful Retreat',       'rating' => '4.9', 'location' => 'Seattle, WA',  'distance' => '1.8 miles away', 'price' => '25'],
        ];
    @endphp
    <section class="site-container pb-16 sm:pb-20">
        <div class="mb-10 flex flex-col items-start justify-between gap-6 md:mb-12 md:flex-row md:items-end">
            <div data-aos="fade-up" class="max-w-2xl">
                <span class="eyebrow">{{ __('Top rated') }}</span>
                <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                    style="font-size: var(--text-h1);">
                    {{ __('Popular') }} <span class="text-[var(--color-primary)]">{{ __('Gyms') }}</span> {{ __('Near You') }}
                </h2>
                <p class="mt-3 text-[16px] text-[var(--color-ink-500)]">
                    {{ __('Handpicked private home gyms with top-tier equipment, great reviews, and instant booking.') }}
                </p>
            </div>

            {{-- Arrow controls --}}
            <div class="hidden gap-3 md:flex" data-aos="fade-left">
                <button type="button"
                        class="gym-prev flex h-12 w-12 items-center justify-center rounded-full border border-[var(--color-ink-100)] bg-white text-[var(--color-ink-700)] transition-all hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-primary)]"
                        aria-label="{{ __('Show previous gyms') }}">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button"
                        class="gym-next flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-[var(--shadow-md)] transition-all hover:-translate-y-0.5 hover:bg-[var(--color-primary-hover)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-primary)]"
                        aria-label="{{ __('Show next gyms') }}">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="gym-slider -mx-3" data-aos="fade-up" data-aos-delay="100">
            @foreach ($popularGyms as $index => $gym)
                @php
                    $cityQuery = trim(explode(',', (string) ($gym['location'] ?? ''))[0] ?? '');
                    $gymFindHref = $cityQuery !== ''
                        ? route('find-a-gym', array_filter(['searchby' => $cityQuery]))
                        : route('find-a-gym');
                @endphp
                <div class="gym-card px-3">
                    <div class="group relative overflow-hidden rounded-[24px] bg-white shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-md)]">
                        <a href="{{ $gymFindHref }}" class="block">
                            <div class="relative aspect-[4/3] overflow-hidden rounded-[20px]">
                                <img src="{{ asset('images/'.$gym['image']) }}"
                                     alt="{{ $gym['name'] }}"
                                     width="640"
                                     height="480"
                                     loading="lazy"
                                     decoding="async"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">

                            {{-- rating chip top-left --}}
                            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-[13px] font-semibold text-[var(--color-ink-900)] shadow-sm backdrop-blur">
                                <i class="fa-solid fa-star text-[#f7b500] text-[11px]" aria-hidden="true"></i>
                                {{ $gym['rating'] }}
                            </span>

                            {{-- price pill bottom-left --}}
                            <span class="absolute bottom-4 left-4 inline-flex items-baseline gap-1 rounded-full bg-[var(--color-primary)] px-4 py-1.5 text-[13px] font-semibold text-white shadow-md">
                                <span class="text-[15px]">${{ $gym['price'] }}</span>
                                <span class="text-[11px] font-normal opacity-90">/ {{ __('hour') }}</span>
                            </span>
                        </div>

                        <div class="pt-4 pb-2">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-[18px] font-bold text-[var(--color-ink-900)] leading-tight truncate">{{ $gym['name'] }}</h3>
                            </div>
                            <p class="mt-1 text-[14px] text-[var(--color-ink-500)]">{{ $gym['type'] }}</p>
                            <div class="mt-3 flex items-center gap-2 text-[14px] text-[var(--color-ink-500)]">
                                <i class="fa-solid fa-location-dot text-[var(--color-primary)]" aria-hidden="true"></i>
                                <span>{{ $gym['location'] }} · {{ $gym['distance'] }}</span>
                            </div>
                        </div>
                        </a>
                        {{-- Outside <a> so we never nest interactive content inside a link (HTML + a11y). --}}
                        <button type="button"
                                class="absolute right-4 top-4 z-20 flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-full border-0 bg-white/90 text-[var(--color-ink-400)] opacity-80 backdrop-blur"
                                disabled
                                aria-label="{{ __('Save :name to favorites (coming soon)', ['name' => $gym['name']]) }}"
                                title="{{ __('Wishlist coming soon') }}">
                            <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('find-a-gym') }}" class="btn btn-outline btn-lg">
                {{ __('View All Gyms') }}
                <i class="fa-solid fa-arrow-right text-[13px]"></i>
            </a>
        </div>
    </section>


    {{-- ============================================================
         4 · WHY PEOPLE LOVE SPOTMEE
         ============================================================ --}}
    @php
        $defaultWhy = [
            'heading' => 'Why People Love', 'emphasis' => 'SPOTMEE',
            'description' => 'Discover why thousands choose private home gyms over crowded public spaces.',
            'features' => [
                ['image_url' => asset('images/icon-1.png'), 'link_label' => 'Private & Safe',        'link_href' => null, 'text' => 'Work out alone and enjoy it!'],
                ['image_url' => asset('images/icon-2.png'), 'link_label' => 'Affordable & Flexible', 'link_href' => null, 'text' => 'Pay Only for the Time You Need.'],
                ['image_url' => asset('images/icon-3.png'), 'link_label' => 'Quality Equipment',     'link_href' => null, 'text' => 'Premium Equipment for Every Workout Style.'],
                ['image_url' => asset('images/icon-4.png'), 'link_label' => 'Verified Hosts',        'link_href' => null, 'text' => 'Trusted, Reviewed, and Screened Hosts.'],
            ],
            'cta' => ['label' => 'Get Started', 'href' => route('find-a-gym')],
        ];
        $whySection      = $settings?->homeWhySectionForView() ?? $defaultWhy;
        $whyHeading      = (string) ($whySection['heading'] ?? $defaultWhy['heading']);
        $whyEmphasis     = trim((string) ($whySection['emphasis'] ?? $defaultWhy['emphasis']));
        $whyDescription  = trim((string) ($whySection['description'] ?? $defaultWhy['description']));
        $whyParts        = ($whyEmphasis !== '' && str_contains($whyHeading, $whyEmphasis))
            ? explode($whyEmphasis, $whyHeading, 2) : null;
        $whyFeatures     = is_array($whySection['features'] ?? null) ? $whySection['features'] : $defaultWhy['features'];
        $whyCta          = is_array($whySection['cta'] ?? null) ? $whySection['cta'] : $defaultWhy['cta'];
        $whyDelays       = [100, 200, 300, 400];
    @endphp
    <section class="relative bg-[var(--color-brand-50)] py-20 sm:py-24">
        <div class="site-container">
            <div class="mx-auto mb-14 max-w-2xl text-center" data-aos="fade-up">
                <span class="eyebrow bg-white">{{ __('Why choose us') }}</span>
                <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                    style="font-size: var(--text-h1);">
                    @if ($whyParts !== null)
                        {{ $whyParts[0] }}<span class="text-[var(--color-primary)]">{{ $whyEmphasis }}</span>{{ $whyParts[1] ?? '' }}
                    @else
                        {{ $whyHeading }} @if ($whyEmphasis !== '')<span class="text-[var(--color-primary)]">{{ $whyEmphasis }}</span>@endif
                    @endif
                </h2>
                @if ($whyDescription !== '')
                    <p class="mt-4 text-[16px] text-[var(--color-ink-500)]" data-aos="fade-up" data-aos-delay="100">
                        {{ $whyDescription }}
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-7">
                @foreach ($whyFeatures as $index => $feature)
                    @php
                        $fallbackFeature = $defaultWhy['features'][$index] ?? $defaultWhy['features'][0];
                        $featureImage    = $feature['image_url']  ?? $fallbackFeature['image_url'];
                        $featureLabel    = trim((string) ($feature['link_label'] ?? $fallbackFeature['link_label']));
                        $featureHref     = $feature['link_href']  ?? null;
                        $featureText     = trim((string) ($feature['text'] ?? $fallbackFeature['text']));
                        $featureDelay    = $whyDelays[$index] ?? 100;
                    @endphp
                    <div class="group flex h-full flex-col items-center rounded-[24px] border border-transparent bg-white p-7 text-center shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
                         data-aos="fade-up" data-aos-delay="{{ $featureDelay }}">
                        <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-[var(--color-brand-50)] transition-colors duration-300 group-hover:bg-[var(--color-primary)]">
                            <img src="{{ $featureImage }}"
                                 width="80"
                                 height="80"
                                 loading="lazy"
                                 decoding="async"
                                 class="h-10 w-10 object-contain transition-all duration-300 group-hover:brightness-0 group-hover:invert"
                                 alt="{{ $featureLabel !== '' ? $featureLabel : 'Feature' }}">
                        </div>
                        @if ($featureLabel !== '')
                            @if (! empty($featureHref))
                                <a href="{{ $featureHref }}" class="mb-3 inline-flex items-center rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-primary)]">{{ $featureLabel }}</a>
                            @else
                                <span class="mb-3 inline-flex items-center rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-primary)]">{{ $featureLabel }}</span>
                            @endif
                        @endif
                        <h3 class="text-[17px] font-bold leading-snug text-[var(--color-ink-900)]">{{ $featureText }}</h3>
                    </div>
                @endforeach
            </div>

            @if (! empty($whyCta['label']) && ! empty($whyCta['href']))
                <div class="mt-14 text-center" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ $whyCta['href'] }}" class="btn btn-primary btn-lg">
                        {{ $whyCta['label'] }}
                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>


    {{-- ============================================================
         5 · EARN MONEY WITH YOUR HOME GYM
         ============================================================ --}}
    @php
        $defaultEarn = [
            'heading' => 'With Your Home Gym', 'emphasis' => 'Earn Money',
            'points' => ['Share your space.', 'Support your community.', 'Unlock new income.'],
            'description' => 'List your fitness space with SPOTMEE. Add photos, set pricing, and accept bookings. We connect you with users seeking quality workout spaces.',
            'footnote' => 'Join hundreds of hosts earning monthly income with spaces they already own.',
            'cta' => ['label' => 'Start Hosting', 'href' => route('become-a-host')],
            'image_url' => asset('images/earn-money-right-img.png'),
        ];
        $earnSection     = $settings?->homeEarnSectionForView() ?? $defaultEarn;
        $earnHeading     = (string) ($earnSection['heading'] ?? $defaultEarn['heading']);
        $earnEmphasis    = trim((string) ($earnSection['emphasis'] ?? $defaultEarn['emphasis']));
        $earnPoints      = is_array($earnSection['points'] ?? null) ? $earnSection['points'] : $defaultEarn['points'];
        $earnDescription = trim((string) ($earnSection['description'] ?? $defaultEarn['description']));
        $earnFootnote    = trim((string) ($earnSection['footnote'] ?? $defaultEarn['footnote']));
        $earnCta         = is_array($earnSection['cta'] ?? null) ? $earnSection['cta'] : $defaultEarn['cta'];
        $earnImageUrl    = $earnSection['image_url'] ?? $defaultEarn['image_url'];
    @endphp
    <section class="site-container py-20 sm:py-24">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-20">
            <div class="order-2 lg:order-1" data-aos="fade-right">
                <span class="eyebrow">{{ __('For hosts') }}</span>
                <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                    style="font-size: var(--text-h1);" data-aos="fade-up" data-aos-delay="100">
                    @if ($earnEmphasis !== '')
                        <span class="text-[var(--color-primary)]">{{ $earnEmphasis }}</span>
                    @endif
                    {{ $earnHeading }}
                </h2>

                <ul class="mt-8 space-y-4">
                    @foreach ($earnPoints as $pointIndex => $pointText)
                        @if (trim((string) $pointText) !== '')
                            <li class="flex items-center gap-3" data-aos="fade-up" data-aos-delay="{{ 200 + ($pointIndex * 100) }}">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </span>
                                <span class="text-[17px] font-semibold text-[var(--color-ink-900)]">{{ $pointText }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>

                @if ($earnDescription !== '')
                    <p class="mt-8 max-w-md text-[16px] leading-relaxed text-[var(--color-ink-500)]" data-aos="fade-up" data-aos-delay="500">
                        {{ $earnDescription }}
                    </p>
                @endif

                @if (! empty($earnCta['label']) && ! empty($earnCta['href']))
                    <div class="mt-8" data-aos="fade-up" data-aos-delay="600">
                        <a href="{{ $earnCta['href'] }}" class="btn btn-primary btn-lg">
                            {{ $earnCta['label'] }}
                            <i class="fa-solid fa-arrow-right text-[13px]"></i>
                        </a>
                    </div>
                @endif

                @if ($earnFootnote !== '')
                    <p class="mt-6 max-w-sm text-[14px] leading-relaxed text-[var(--color-ink-500)]" data-aos="fade-up" data-aos-delay="700">
                        <i class="fa-solid fa-circle-info mr-1 text-[var(--color-brand-300)]"></i>
                        {{ $earnFootnote }}
                    </p>
                @endif
            </div>

            <div class="order-1 lg:order-2" data-aos="fade-left">
                <div class="relative">
                    <div class="pointer-events-none absolute -inset-6 -z-10 rounded-[40px] bg-gradient-to-br from-[var(--color-brand-200)]/30 via-[var(--color-brand-50)] to-transparent blur-2xl"></div>
                    <img src="{{ $earnImageUrl }}"
                         alt="{{ trim($earnEmphasis.' '.$earnHeading) }}"
                         width="960"
                         height="720"
                         loading="lazy"
                         decoding="async"
                         class="relative w-full rounded-[32px] object-cover">
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         6 · COMMUNITY & DISCUSSIONS
         ============================================================ --}}
    @php
        $defaultCommunity = [
            'heading' => 'Community &', 'emphasis' => 'Discussions',
            'description' => 'Where fitness lovers, hosts, and users come together.',
            'cards' => [
                ['image_url' => asset('images/community-img-001.png'), 'title' => 'Real Conversations',   'body' => 'Join active discussions, share ideas, and talk to others on similar fitness journeys.'],
                ['image_url' => asset('images/community-img-002.png'), 'title' => 'Share Your Journey',   'body' => 'Post your workout stories, progress moments, and inspire others in the community.'],
                ['image_url' => asset('images/community-img-003.png'), 'title' => 'Expert Tips & Guides', 'body' => 'Explore fitness insights from hosts, trainers, and experienced athletes.'],
                ['image_url' => asset('images/community-img-004.png'), 'title' => 'Connect With Hosts',   'body' => 'Build real connections with home gym owners and local fitness enthusiasts.'],
            ],
            'cta' => ['label' => 'View FAQ', 'href' => route('faq')],
        ];
        $communitySection     = $settings?->homeCommunitySectionForView() ?? $defaultCommunity;
        $communityHeading     = trim((string) ($communitySection['heading'] ?? $defaultCommunity['heading']));
        $communityEmphasis    = trim((string) ($communitySection['emphasis'] ?? $defaultCommunity['emphasis']));
        $communityDescription = trim((string) ($communitySection['description'] ?? $defaultCommunity['description']));
        $communityCards       = is_array($communitySection['cards'] ?? null) ? $communitySection['cards'] : $defaultCommunity['cards'];
        $communityCta         = is_array($communitySection['cta'] ?? null) ? $communitySection['cta'] : $defaultCommunity['cta'];
        $communityDelays      = [100, 200, 300, 400];
    @endphp
    <section class="site-container pb-20 sm:pb-24">
        <div class="mx-auto mb-12 max-w-2xl text-center" data-aos="fade-up">
            <span class="eyebrow">{{ __('Together') }}</span>
            <h2 class="mt-4 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                style="font-size: var(--text-h1);">
                {{ $communityHeading }} @if ($communityEmphasis !== '')<span class="text-[var(--color-primary)]">{{ $communityEmphasis }}</span>@endif
            </h2>
            @if ($communityDescription !== '')
                <p class="mt-4 text-[16px] text-[var(--color-ink-500)]" data-aos="fade-up" data-aos-delay="100">
                    {{ $communityDescription }}
                </p>
            @endif
        </div>

        <div class="mb-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-7">
            @foreach ($communityCards as $index => $card)
                @php
                    $fallbackCard = $defaultCommunity['cards'][$index] ?? $defaultCommunity['cards'][0];
                    $cardImage    = $card['image_url'] ?? $fallbackCard['image_url'];
                    $cardTitle    = trim((string) ($card['title'] ?? $fallbackCard['title']));
                    $cardBody     = trim((string) ($card['body']  ?? $fallbackCard['body']));
                    $cardDelay    = $communityDelays[$index] ?? 100;
                @endphp
                <article class="group flex h-full flex-col overflow-hidden rounded-[24px] bg-white shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lg)]"
                         data-aos="fade-up" data-aos-delay="{{ $cardDelay }}">
                    <div class="aspect-[16/10] overflow-hidden">
                        <img src="{{ $cardImage }}"
                             alt="{{ $cardTitle }}"
                             width="640"
                             height="400"
                             loading="lazy"
                             decoding="async"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="text-[18px] font-bold leading-tight text-[var(--color-ink-900)]">{{ $cardTitle }}</h3>
                        <p class="mt-3 text-[15px] leading-relaxed text-[var(--color-ink-500)]">{{ $cardBody }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        @if (! empty($communityCta['label']) && ! empty($communityCta['href']))
            <div class="text-center" data-aos="fade-up" data-aos-delay="500">
                <a href="{{ $communityCta['href'] }}" class="btn btn-primary btn-lg">
                    {{ $communityCta['label'] }}
                    <i class="fa-solid fa-arrow-right text-[13px]"></i>
                </a>
            </div>
        @endif
    </section>


    {{-- ============================================================
         7 · PROMO BANNER — Your Perfect Workout
         ============================================================ --}}
    @php
        $defaultPromo = [
            'heading' => 'Your Space Is Just a Click Away.',
            'emphasis' => 'Perfect Workout',
            'cta' => ['label' => 'Get Started', 'href' => route('find-a-gym')],
            'image_url' => asset('images/perfect.png'),
            'on_image' => true,
        ];
        $promoSection  = $settings?->homePromoBannerSectionForView() ?? $defaultPromo;
        $promoHeading  = trim((string) ($promoSection['heading'] ?? $defaultPromo['heading']));
        $promoEmphasis = trim((string) ($promoSection['emphasis'] ?? $defaultPromo['emphasis']));
        $promoCta      = is_array($promoSection['cta'] ?? null) ? $promoSection['cta'] : $defaultPromo['cta'];
        $promoImageUrl = $promoSection['image_url'] ?? $defaultPromo['image_url'];
    @endphp
    <section class="site-container pb-16 sm:pb-20">
        <div class="relative overflow-hidden rounded-[28px] sm:rounded-[36px]"
             style="background-image: url('{{ $promoImageUrl }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-r from-[rgba(0,69,77,0.85)] via-[rgba(0,109,119,0.55)] to-transparent"></div>

            <div class="relative z-10 flex min-h-[420px] items-center px-8 py-14 sm:min-h-[500px] sm:px-14 lg:min-h-[560px] lg:px-20">
                <div class="max-w-xl" data-aos="fade-right">
                    <span class="eyebrow bg-white/20 text-white backdrop-blur-sm">
                        <i class="fa-solid fa-dumbbell"></i>
                        {{ __('Ready when you are') }}
                    </span>
                    <h2 class="mt-5 font-bold leading-[1.05] text-white"
                        style="font-size: clamp(2rem, 4.5vw, 3.25rem);" data-aos="fade-up" data-aos-delay="100">
                        @if ($promoEmphasis !== '' && str_contains($promoHeading, $promoEmphasis))
                            @php($promoParts = explode($promoEmphasis, $promoHeading, 2))
                            {{ $promoParts[0] }}<span class="text-[var(--color-brand-200)]">{{ $promoEmphasis }}</span>{{ $promoParts[1] ?? '' }}
                        @elseif ($promoEmphasis !== '')
                            {{ $promoHeading }}<br><span class="text-[var(--color-brand-200)]">{{ $promoEmphasis }}</span>
                        @else
                            {{ $promoHeading }}
                        @endif
                    </h2>

                    @if (! empty($promoCta['label']) && ! empty($promoCta['href']))
                        <div class="mt-8" data-aos="zoom-in" data-aos-delay="250">
                            <a href="{{ $promoCta['href'] }}"
                               class="btn btn-lg bg-white text-[var(--color-primary)] hover:bg-[var(--color-brand-50)]">
                                {{ $promoCta['label'] }}
                                <i class="fa-solid fa-arrow-right text-[13px]"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@push('scripts')
@endpush
