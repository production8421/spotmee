@extends('layouts.web.master')
@section('title', 'Home')
@section('content')
 
<main class="spotmee-main">
    @php
        /** @var \App\Models\ApplicationSetting|null $settings */
        $heroSettings = $settings ?? null;
        $heroHeading = $heroSettings?->homeHeroHeadingDisplay() ?? __('Find the Perfect Private Gym Near You');
        $heroBgKind = $heroSettings?->homeHeroBackgroundKind() ?? 'color';
        $heroBgImageUrl = $heroSettings?->homeHeroBackgroundPublicUrl();
        $heroBgColor = $heroSettings?->homeHeroSolidColorCss() ?? '#e3e3e0';
        $heroBgStyle = $heroBgKind === 'image' && filled($heroBgImageUrl)
            ? "background-image: url('{$heroBgImageUrl}');"
            : "background-image: none; background-color: {$heroBgColor};";
        $heroPrimaryCta = $heroSettings?->homeHeroCtaOne() ?? ['label' => __('Book a Gym'), 'href' => route('find-a-gym')];
        $heroSecondaryCta = $heroSettings?->homeHeroCtaTwo() ?? ['label' => __('Become a Host'), 'href' => route('become-a-host')];
    @endphp
    <div class="px-5">
 <section
    class="hero-banner"
    style="{{ $heroBgStyle }}">

    <div class="absolute inset-0 bg-black/10"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-start ">

            <!-- LEFT: Search Card -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-start"
                 data-aos="fade-right">
                <div class="form-wrapper">
                    <h2 class="text-[20px] md:text-[30px] font-bold text-[var(--text-color)] mb-6"
                        data-aos="fade-up" data-aos-delay="100">
                        Find a home gym near you
                    </h2>

                    <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                        <label class="block text-[20px] font-regular mb-2">Location</label>
                        <input type="text" placeholder="Enter location or ZIP"
                            class="w-full bg-[#F3F4F6] rounded-lg px-5 py-3 outline-none focus:ring-2 focus:ring-[#4682B4]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6"
                         data-aos="fade-up" data-aos-delay="300">
                        <div>
                            <label class="block text-[20px] font-regular mb-2">Date</label>
                            <input type="date"
                                class="w-full bg-[#F3F4F6] rounded-lg px-5 py-3 outline-none focus:ring-2 focus:ring-[#4682B4]">
                        </div>
                        <div>
                            <label class="block text-[20px] font-regular mb-2">Time</label>
                            <input type="text" placeholder="Pick a time"
                                class="w-full bg-[#F3F4F6] rounded-lg px-5 py-3 outline-none focus:ring-2 focus:ring-[#4682B4]">
                        </div>
                    </div>

                    <button class="cta-btn w-full mb-4"
                            data-aos="zoom-in" data-aos-delay="400">
                        Find Gyms
                    </button>

                    <p class="text-center text-[18px] font-regular text-[var(--text-color)]"
                       data-aos="fade-up" data-aos-delay="500">
                        Private gyms near you. No memberships. No waiting.
                    </p>
                </div>
            </div>

            <!-- RIGHT: Content -->
            <div
                class="w-full lg:w-1/2 flex flex-col items-center lg:items-end "
                data-aos="fade-left">

                <h4
                    class="text-[30px] sm:text-[40px] font-bold leading-tight mb-8 max-w-[440px] mt-[50px] md:mt-[0px]"
                    data-aos="fade-up" data-aos-delay="150">
                    {{ $heroHeading }}
                </h4>

                <div class="flex flex-col sm:flex-row gap-4 w-full max-w-[440px]"
                     data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ $heroPrimaryCta['href'] }}" class="cta-btn">
                        {{ $heroPrimaryCta['label'] }}
                    </a>
                    <a href="{{ $heroSecondaryCta['href'] }}"
                        class="px-8 py-3 border-2 border-[#333333] rounded-full text-lg text-center hover:bg-black hover:text-white transition">
                        {{ $heroSecondaryCta['label'] }}
                    </a>
                </div>

            </div>

        </div>
    </div>

</section>
</div>


    <!-- How It Works Section (dynamic from admin frontend settings) -->
    @php
        $defaultHow = [
            'heading' => 'How Works',
            'emphasis' => 'SPOTMEE',
            'steps' => [
                [
                    'badge' => 'Step 1',
                    'title' => 'Find Home Gyms Near You',
                    'body' => 'Find verified home gyms nearby. Filter by equipment, workout type, ratings, and availability.',
                    'image_url' => asset('images/work-img-1.png'),
                ],
                [
                    'badge' => 'Step 2',
                    'title' => 'Select Date & Book Now',
                    'body' => 'Select a date and time, and book your session easily no commitments required.',
                    'image_url' => asset('images/work-img-2.png'),
                ],
                [
                    'badge' => 'Step 3',
                    'title' => 'Work Out Your Way',
                    'body' => 'Enjoy a clean, private workout. No crowds, no waiting—just you and your goals.',
                    'image_url' => asset('images/work-img-3.png'),
                ],
            ],
        ];
        $howSection = $settings?->homeHowSectionForView() ?? $defaultHow;
        $howHeading = (string) ($howSection['heading'] ?? $defaultHow['heading']);
        $howEmphasis = trim((string) ($howSection['emphasis'] ?? $defaultHow['emphasis']));
        $headingParts = ($howEmphasis !== '' && str_contains($howHeading, $howEmphasis))
            ? explode($howEmphasis, $howHeading, 2)
            : null;
        $howSteps = is_array($howSection['steps'] ?? null) ? $howSection['steps'] : $defaultHow['steps'];
        $defaultStepDelays = [100, 250, 400];
    @endphp
    <section class="container mx-auto px-4 py-16 sm:px-6">
        <h2 class="text-center text-[30px] md:text-[40px] font-bold text-[#333333] mb-16 leading-[1.1]" data-aos="fade-up">
            @if ($headingParts !== null)
                {{ $headingParts[0] }}<span class="text-[#4682B4]">{{ $howEmphasis }}</span>{{ $headingParts[1] ?? '' }}
            @else
                {{ $howHeading }} @if ($howEmphasis !== '')<span class="text-[#4682B4]">{{ $howEmphasis }}</span>@endif
            @endif
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            @foreach ($howSteps as $index => $step)
                @php
                    $fallback = $defaultHow['steps'][$index] ?? $defaultHow['steps'][0];
                    $stepBadge = trim((string) ($step['badge'] ?? $fallback['badge']));
                    $stepTitle = trim((string) ($step['title'] ?? $fallback['title']));
                    $stepBody = trim((string) ($step['body'] ?? $fallback['body']));
                    $stepImage = $step['image_url'] ?? $fallback['image_url'];
                    $stepDelay = $defaultStepDelays[$index] ?? 100;
                @endphp
                <div class="flex flex-col" data-aos="fade-up" data-aos-delay="{{ $stepDelay }}">
                    <div class="mb-4 w-full">
                        <img src="{{ $stepImage }}" alt="{{ $stepTitle }}" class="spotmee-card-img">
                    </div>
                    <div class="flex flex-col items-start">
                        @if ($index === 1)
                            <span class="inline-block bg-[#4682B4] text-[#FFFFFF] rounded-full px-4 py-1 mb-2 text-sm font-semibold">{{ $stepBadge }}</span>
                        @else
                            <span class="step">{{ $stepBadge }}</span>
                        @endif
                        <h3 class="md-title mb-2">{{ $stepTitle }}</h3>
                        <p class="md-para">{{ $stepBody }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Popular Gym Section -->
    <section class="container mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-end justify-between mb-8 md:mb-12 gap-6">
            <div data-aos="fade-up">
                <h2 class="heading">
                    Popular <span class="text-[#4682B4]">Gyms</span> Near You
                </h2>
                <p class="text-[#333333] text-base md:text-lg">
                    Handpicked private home gyms with top-tier equipment, <br class="md:block hidden"> great reviews, and instant booking options.
                </p>
            </div>
            
            <!-- Custom Navigation Arrows -->
            <div class="flex gap-3 hidden md:flex" data-aos="fade-left">
                <button class="gym-prev w-12 h-12 rounded-full border bg-[#A4A4A4] border-gray-300 flex items-center justify-center text-[#fff] hover:bg-[#4682B4] hover:text-white hover:border-[#4682B4] transition-all focus:outline-none">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gym-next w-12 h-12 rounded-full bg-[#4682B4] flex items-center justify-center text-white hover:bg-[#3a6d96] transition-all focus:outline-none shadow-md">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Slider -->
        <div class="gym-slider -mx-3" data-aos="fade-up" data-aos-delay="100">
            <!-- Card 1 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="150">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-001.png') }}" class="gym-card-img" alt="The Strength Studio">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title">The Strength Studio</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">4.9</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Private Garage Gym</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-[18px] mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Dallas, TX • 1.2 miles away</span>
                        </div>
                        
                         <div class="price">
                            $18/hour
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="250">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-002.png') }}" class="gym-card-img" alt="The Cardio Loft">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title">The Cardio Loft</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">4.8</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Modern Home Fitness Room</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Austin, TX • 3.4 miles away</span>
                        </div>
                        
                         <div class="price">
                            $15/hour
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="350">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-003.png') }}" class="gym-card-img" alt="Ironclad Elite Gym">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title">Ironclad Elite Gym</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">5.0</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Basement Training Space</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Houston, TX • 2.0 miles away</span>
                        </div>
                        
                             <div class="price">
                            $22/hour
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="450">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-004.png') }}" class="gym-card-img" alt="ZenFlex Pilates">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title truncate">ZenFlex Pilates Studio</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">4.9</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Core Studio</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Miami, FL • 0.9 miles away</span>
                        </div>
                        
                               <div class="price">
                            $20/hour
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="550">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-001.png') }}" class="gym-card-img" alt="Titan Fitness">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title">Titan Fitness</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">4.7</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Heavy Lifting Zone</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Chicago, IL • 2.5 miles away</span>
                        </div>
                        
                          <div class="price">
                            $16/hour
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="gym-card" data-aos="zoom-in" data-aos-delay="650">
                <div class="gym-card-main">
                    <div class="relative aspect-w-4 aspect-h-3 h-[300px]">
                        <img src="{{ asset('images/popular-gym-img-002.png') }}" class="gym-card-img" alt="Urban Yoga Space">
                    </div>
                    <div class="pt-5 pb-2">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="md-title">Urban Yoga Space</h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="rating">4.9</span>
                            </div>
                        </div>
                        <p class="md-para-slider">Peaceful Retreat</p>
                        
                        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                            <span class="text-[#333333] text-[18px] font-regular">Seattle, WA • 1.8 miles away</span>
                        </div>
                        
                        <div class="price">
                            $25/hour</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-8"  data-aos="fade-up" data-aos-delay="500">
            <a href="#" class="cta-btn">
                View All Gyms
            </a>
        </div>
    </section>

  <!-- Why People Love SPOTMEE (dynamic from admin frontend settings) -->
@php
    $defaultWhy = [
        'heading' => 'Why People Love',
        'emphasis' => 'SPOTMEE',
        'description' => 'Discover why thousands choose private home gyms over crowded public spaces.',
        'features' => [
            ['image_url' => asset('images/icon-1.png'), 'link_label' => 'Private & Safe', 'link_href' => null, 'text' => 'Work out alone and enjoy it!'],
            ['image_url' => asset('images/icon-2.png'), 'link_label' => 'Affordable & Flexible', 'link_href' => null, 'text' => 'Pay Only for the Time You Need.'],
            ['image_url' => asset('images/icon-3.png'), 'link_label' => 'Quality Equipment', 'link_href' => null, 'text' => 'Premium Equipment for Every Workout Style.'],
            ['image_url' => asset('images/icon-4.png'), 'link_label' => 'Verified Hosts', 'link_href' => null, 'text' => 'Trusted, Reviewed, and Screened Hosts.'],
        ],
        'cta' => ['label' => 'Get Started', 'href' => '#'],
    ];
    $whySection = $settings?->homeWhySectionForView() ?? $defaultWhy;
    $whyHeading = (string) ($whySection['heading'] ?? $defaultWhy['heading']);
    $whyEmphasis = trim((string) ($whySection['emphasis'] ?? $defaultWhy['emphasis']));
    $whyDescription = trim((string) ($whySection['description'] ?? $defaultWhy['description']));
    $whyParts = ($whyEmphasis !== '' && str_contains($whyHeading, $whyEmphasis))
        ? explode($whyEmphasis, $whyHeading, 2)
        : null;
    $whyFeatures = is_array($whySection['features'] ?? null) ? $whySection['features'] : $defaultWhy['features'];
    $whyCta = is_array($whySection['cta'] ?? null) ? $whySection['cta'] : $defaultWhy['cta'];
    $whyDelays = [100, 250, 400, 550];
@endphp
<section class="container mx-auto px-4 py-10 sm:px-6 lg:px-8 mb-16">
    <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="heading">
            @if ($whyParts !== null)
                {{ $whyParts[0] }}<br><span class="text-[#4682B4]">{{ $whyEmphasis }}</span>{{ $whyParts[1] ?? '' }}
            @else
                {{ $whyHeading }} @if ($whyEmphasis !== '')<br><span class="text-[#4682B4]">{{ $whyEmphasis }}</span>@endif
            @endif
        </h2>
        @if ($whyDescription !== '')
            <p class="text-[#333333] text-[20px] font-regular max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="150">
                {{ $whyDescription }}
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-center">
        @foreach ($whyFeatures as $index => $feature)
            @php
                $fallbackFeature = $defaultWhy['features'][$index] ?? $defaultWhy['features'][0];
                $featureImage = $feature['image_url'] ?? $fallbackFeature['image_url'];
                $featureLabel = trim((string) ($feature['link_label'] ?? $fallbackFeature['link_label']));
                $featureHref = $feature['link_href'] ?? null;
                $featureText = trim((string) ($feature['text'] ?? $fallbackFeature['text']));
                $featureDelay = $whyDelays[$index] ?? 100;
            @endphp
            <div class="spotmee-card" data-aos="zoom-in" data-aos-delay="{{ $featureDelay }}">
                <div class="spotmee-card-icon">
                    <img src="{{ $featureImage }}" class="w-12 h-12 object-contain" alt="{{ $featureLabel !== '' ? $featureLabel : 'Feature' }}">
                </div>
                @if ($featureLabel !== '')
                    <div class="mb-3">
                        @if (! empty($featureHref))
                            <a href="{{ $featureHref }}" class="spotmee-card-span">{{ $featureLabel }}</a>
                        @else
                            <span class="spotmee-card-span">{{ $featureLabel }}</span>
                        @endif
                    </div>
                @endif
                <h3 class="md-title mb-3 leading-[1.1]">{{ $featureText }}</h3>
            </div>
        @endforeach
    </div>

    @if (! empty($whyCta['label']) && ! empty($whyCta['href']))
        <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="700">
            <a href="{{ $whyCta['href'] }}" class="cta-btn">
                {{ $whyCta['label'] }}
            </a>
        </div>
    @endif
</section>


   <!-- Earn Money With Your Home Gym (dynamic from fifth section settings) -->
@php
    $defaultEarn = [
        'heading' => 'With Your Home Gym',
        'emphasis' => 'Earn Money',
        'points' => ['Share your space.', 'Support your community.', 'Unlock new income.'],
        'description' => 'List your fitness space with SPOTMEE. Add photos, set pricing, and accept bookings. We connect you with users seeking quality workout spaces.',
        'footnote' => 'Join hundreds of hosts earning monthly income with spaces they already own.',
        'cta' => ['label' => 'Start Hosting', 'href' => '#'],
        'image_url' => asset('images/earn-money-right-img.png'),
    ];
    $earnSection = $settings?->homeEarnSectionForView() ?? $defaultEarn;
    $earnHeading = (string) ($earnSection['heading'] ?? $defaultEarn['heading']);
    $earnEmphasis = trim((string) ($earnSection['emphasis'] ?? $defaultEarn['emphasis']));
    $earnPoints = is_array($earnSection['points'] ?? null) ? $earnSection['points'] : $defaultEarn['points'];
    $earnDescription = trim((string) ($earnSection['description'] ?? $defaultEarn['description']));
    $earnFootnote = trim((string) ($earnSection['footnote'] ?? $defaultEarn['footnote']));
    $earnCta = is_array($earnSection['cta'] ?? null) ? $earnSection['cta'] : $defaultEarn['cta'];
    $earnImageUrl = $earnSection['image_url'] ?? $defaultEarn['image_url'];
@endphp
<section class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        
        <!-- Left Side: Content -->
        <div class="flex flex-col items-start order-2 lg:order-1" data-aos="fade-right">
            <h2 class="heading" data-aos="fade-up" data-aos-delay="100">
                @if ($earnEmphasis !== '')
                    <span class="text-[#4682B4]">{{ $earnEmphasis }}</span>
                @endif
                {{ $earnHeading }}
            </h2>

            <div class="space-y-4 mb-8">
                @foreach ($earnPoints as $pointIndex => $pointText)
                    @if (trim((string) $pointText) !== '')
                        <div class="flex items-center gap-3" data-aos="fade-up" data-aos-delay="{{ 200 + ($pointIndex * 100) }}">
                            <div class="list-div">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="list-span">{{ $pointText }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @if ($earnDescription !== '')
                <p class="text-[#333333] text-[20px] font-regular leading-relaxed mb-6 max-w-md" data-aos="fade-up" data-aos-delay="500">
                    {{ $earnDescription }}
                </p>
            @endif

            @if (! empty($earnCta['label']) && ! empty($earnCta['href']))
                <a href="{{ $earnCta['href'] }}"
                   class="inline-block bg-[#4682B4] text-white font-regular text-[20px] px-8 py-3 rounded-full hover:bg-[#3a6d96] transition-all shadow-lg hover:shadow-blue-500/30 mb-6"
                   data-aos="zoom-in" data-aos-delay="600">
                    {{ $earnCta['label'] }}
                </a>
            @endif

            @if ($earnFootnote !== '')
                <p class="text-[#333333] text-[18px] max-w-xs leading-relaxed" data-aos="fade-up" data-aos-delay="700">
                    {{ $earnFootnote }}
                </p>
            @endif
        </div>

        <!-- Right Side: Image -->
        <div class="order-1 lg:order-2" data-aos="fade-left">
            <img src="{{ $earnImageUrl }}" alt="{{ trim($earnEmphasis.' '.$earnHeading) }}" class="w-full h-auto">
        </div>

    </div>
</section>


  <!-- Community & Discussions (dynamic from sixth section settings) -->
@php
    $defaultCommunity = [
        'heading' => 'Community &',
        'emphasis' => 'Discussions',
        'description' => 'Where fitness lovers, hosts, and users come together.',
        'cards' => [
            ['image_url' => asset('images/community-img-001.png'), 'title' => 'Real Conversations', 'body' => 'Join active discussions, share ideas, and talk to others on similar fitness journeys.'],
            ['image_url' => asset('images/community-img-002.png'), 'title' => 'Share Your Journey', 'body' => 'Post your workout stories, progress moments, and inspire others in the community.'],
            ['image_url' => asset('images/community-img-003.png'), 'title' => 'Expert Tips & Guides', 'body' => 'Explore fitness insights from hosts, trainers, and experienced athletes.'],
            ['image_url' => asset('images/community-img-004.png'), 'title' => 'Connect With Hosts', 'body' => 'Build real connections with home gym owners and local fitness enthusiasts.'],
        ],
        'cta' => ['label' => 'Visit Blog', 'href' => route('blog')],
    ];
    $communitySection = $settings?->homeCommunitySectionForView() ?? $defaultCommunity;
    $communityHeading = trim((string) ($communitySection['heading'] ?? $defaultCommunity['heading']));
    $communityEmphasis = trim((string) ($communitySection['emphasis'] ?? $defaultCommunity['emphasis']));
    $communityDescription = trim((string) ($communitySection['description'] ?? $defaultCommunity['description']));
    $communityCards = is_array($communitySection['cards'] ?? null) ? $communitySection['cards'] : $defaultCommunity['cards'];
    $communityCta = is_array($communitySection['cta'] ?? null) ? $communitySection['cta'] : $defaultCommunity['cta'];
    $communityDelays = [100, 250, 400, 550];
@endphp
<section class="container mx-auto px-4 py-16 sm:px-6 lg:px-8 mb-10">
    <div class="text-center mb-12" data-aos="fade-up">
        <h2 class="heading">
            {{ $communityHeading }} @if ($communityEmphasis !== '')<br><span class="text-[#4682B4]">{{ $communityEmphasis }}</span>@endif
        </h2>
        @if ($communityDescription !== '')
            <p class="text-[#333333] text-[20px]" data-aos="fade-up" data-aos-delay="150">
                {{ $communityDescription }}
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 mb-12">
        @foreach ($communityCards as $index => $card)
            @php
                $fallbackCard = $defaultCommunity['cards'][$index] ?? $defaultCommunity['cards'][0];
                $cardImage = $card['image_url'] ?? $fallbackCard['image_url'];
                $cardTitle = trim((string) ($card['title'] ?? $fallbackCard['title']));
                $cardBody = trim((string) ($card['body'] ?? $fallbackCard['body']));
                $cardDelay = $communityDelays[$index] ?? 100;
            @endphp
            <div class="flex flex-col" data-aos="fade-up" data-aos-delay="{{ $cardDelay }}">
                <div class="mb-4 aspect-w-16 aspect-h-10">
                    <img src="{{ $cardImage }}" class="community-img" alt="{{ $cardTitle }}">
                </div>
                <h3 class="md-title mb-2">{{ $cardTitle }}</h3>
                <p class="text-[#333333] text-[18px] leading-[1.4]">
                    {{ $cardBody }}
                </p>
            </div>
        @endforeach
    </div>

    @if (! empty($communityCta['label']) && ! empty($communityCta['href']))
        <div class="text-center" data-aos="fade-up" data-aos-delay="700">
            <a href="{{ $communityCta['href'] }}" class="cta-btn">
                {{ $communityCta['label'] }}
            </a>
        </div>
    @endif
</section>
<div class="px-5">
 <!-- Your Perfect Workout (dynamic from seventh section settings) -->
@php
    $defaultPromo = [
        'heading' => 'Your Space Is Just a Click Away.',
        'emphasis' => 'Perfect Workout',
        'cta' => ['label' => 'Get Started', 'href' => '#'],
        'image_url' => asset('images/perfect.png'),
        'on_image' => true,
    ];
    $promoSection = $settings?->homePromoBannerSectionForView() ?? $defaultPromo;
    $promoHeading = trim((string) ($promoSection['heading'] ?? $defaultPromo['heading']));
    $promoEmphasis = trim((string) ($promoSection['emphasis'] ?? $defaultPromo['emphasis']));
    $promoCta = is_array($promoSection['cta'] ?? null) ? $promoSection['cta'] : $defaultPromo['cta'];
    $promoImageUrl = $promoSection['image_url'] ?? $defaultPromo['image_url'];
@endphp
<section class="relative w-full h-[600px] bg-cover bg-center rounded-[15px]"
    style="background-image: url('{{ $promoImageUrl }}');">

    <div class="relative z-10 w-full h-full flex">
        <div class="absolute top-6 left-6 md:top-12 md:left-12"
             data-aos="fade-right">
            <h2 class="heading mb-6"
                data-aos="fade-up" data-aos-delay="100">
                @if ($promoEmphasis !== '' && str_contains($promoHeading, $promoEmphasis))
                    @php($promoParts = explode($promoEmphasis, $promoHeading, 2))
                    {{ $promoParts[0] }}<span class="text-[#4682B4]">{{ $promoEmphasis }}</span>{{ $promoParts[1] ?? '' }}
                @elseif ($promoEmphasis !== '')
                    {{ $promoHeading }}<br><span class="text-[#4682B4]">{{ $promoEmphasis }}</span>
                @else
                    {{ $promoHeading }}
                @endif
            </h2>

            @if (! empty($promoCta['label']) && ! empty($promoCta['href']))
                <div class="mt-10" data-aos="zoom-in" data-aos-delay="250">
                    <a href="{{ $promoCta['href'] }}" class="cta-btn">
                        {{ $promoCta['label'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
</div>



</main>
@endsection
 
@push('scripts')

@endpush