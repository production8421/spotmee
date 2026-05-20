@extends('layouts.web.master')
@section('title', 'Find a Gym — SPOTMEE')
@section('content')

@php
    $stateDirectory = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
        'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
        'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
        'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
        'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
        'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
        'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
        'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming',
    ];
    $serviceDirectory = [
        ['key' => 'boxing',            'label' => 'Boxing',            'icon_path' => asset('images/rent-your-jim/boxing.png')],
        ['key' => 'yoga',              'label' => 'Yoga',              'icon_path' => asset('images/rent-your-jim/yoga.png')],
        ['key' => 'crossfit',          'label' => 'CrossFit',          'icon_path' => asset('images/rent-your-jim/fitness.png')],
        ['key' => 'personal_training', 'label' => 'Personal Training', 'icon_path' => asset('images/rent-your-jim/personal-training.png')],
        ['key' => 'weightlifting',     'label' => 'Weightlifting',     'icon_path' => asset('images/rent-your-jim/weights_lifting.png')],
        ['key' => 'cardio',            'label' => 'Cardio',            'icon_path' => asset('images/rent-your-jim/cardio.png')],
        ['key' => 'group_classes',     'label' => 'Group Classes',     'icon_path' => asset('images/rent-your-jim/group_class.png')],
    ];
    $stateChunks = array_chunk($stateDirectory, 10, true);

    $sampleGyms = [
        ['title' => 'The Strength Studio', 'price' => '18', 'location' => 'Dallas, TX',  'rating' => '4.9', 'img' => 'popular-gym-img-001.png', 'type' => 'Private Garage Gym',  'distance' => '1.2 miles away'],
        ['title' => 'The Cardio Loft',     'price' => '15', 'location' => 'Austin, TX',  'rating' => '4.8', 'img' => 'popular-gym-img-002.png', 'type' => 'Modern Fitness Room', 'distance' => '3.4 miles away'],
        ['title' => 'Ironclad Elite',      'price' => '22', 'location' => 'Houston, TX', 'rating' => '5.0', 'img' => 'popular-gym-img-003.png', 'type' => 'Basement Training',   'distance' => '2.0 miles away'],
        ['title' => 'ZenFlex Pilates',     'price' => '20', 'location' => 'Miami, FL',   'rating' => '4.9', 'img' => 'popular-gym-img-004.png', 'type' => 'Pilates Core',        'distance' => '0.9 miles away'],
        ['title' => 'Titan Fitness',       'price' => '16', 'location' => 'Chicago, IL', 'rating' => '4.7', 'img' => 'popular-gym-img-001.png', 'type' => 'Heavy Lifting',       'distance' => '2.5 miles away'],
        ['title' => 'Urban Yoga Space',    'price' => '25', 'location' => 'Seattle, WA', 'rating' => '4.9', 'img' => 'popular-gym-img-002.png', 'type' => 'Peaceful Retreat',    'distance' => '1.5 miles away'],
    ];
@endphp

<main class="spotmee-main">

    {{-- ============================================================
         1 · HERO · SEARCH
         ============================================================ --}}
    <section class="site-container pt-6 sm:pt-10">
        <div class="inner-banner"
             style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.85)] via-[rgba(0,109,119,0.55)] to-[rgba(131,197,190,0.25)]"></div>

            <div class="inner-banner__content">
                <span class="inner-banner__eyebrow" data-aos="fade-down">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ __('Find a gym') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('Find Your') }} <span class="text-[var(--color-brand-200)]">{{ __('Perfect Space') }}</span>
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('Search private gyms by location and availability. Book instantly and train on your own terms.') }}
                </p>

                <form action="{{ route('find-a-gym') }}" method="GET"
                      class="mx-auto mt-8 flex w-full max-w-2xl flex-col gap-3 rounded-2xl bg-white/95 p-3 shadow-[var(--shadow-lg)] backdrop-blur sm:flex-row sm:items-center"
                      data-aos="fade-up" data-aos-delay="300">
                    <label class="flex flex-1 items-center gap-3 rounded-xl bg-[var(--color-brand-50)] px-4 py-3">
                        <i class="fa-solid fa-location-dot text-[var(--color-primary)]"></i>
                        <input type="text" name="searchby" value="{{ request('searchby', request('city', '')) }}"
                               placeholder="{{ __('City, state, zip or neighbourhood') }}"
                               class="w-full border-0 bg-transparent p-0 text-[15px] text-[var(--color-ink-900)] placeholder:text-[var(--color-ink-400)] focus:outline-none focus:ring-0">
                    </label>
                    <button type="submit" class="btn btn-primary btn-lg justify-center sm:w-auto">
                        <i class="fa-solid fa-magnifying-glass text-[13px]"></i>
                        {{ __('Find Gyms') }}
                    </button>
                </form>
                <p class="mt-4 text-[14px] text-white/85" data-aos="fade-up" data-aos-delay="350">
                    {{ __('Private gyms near you. No memberships. No waiting.') }}
                </p>
            </div>
        </div>
    </section>


    {{-- ============================================================
         2 · RENT A GYM NEAR YOU · STATE + SERVICE DIRECTORY
         ============================================================ --}}
    <section class="site-container py-16 sm:py-20">
        <div class="rounded-[28px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] sm:p-10"
             data-aos="fade-up">
            <div class="section-head mb-12">
                <span class="eyebrow">{{ __('Find your perfect workout space') }}</span>
                <h2 class="section-head__title">
                    {{ __('Rent a Gym') }} <span class="text-[var(--color-primary)]">{{ __('Near You') }}</span>
                </h2>
            </div>

            {{-- States --}}
            <div class="mb-12">
                <div class="mb-6 flex items-end justify-between gap-4">
                    <h3 class="relative inline-block text-[22px] font-bold text-[var(--color-ink-900)] sm:text-[26px]">
                        {{ __('Browse by State') }}
                        <span class="absolute -bottom-2 left-0 h-[3px] w-12 rounded-full bg-[var(--color-primary)]"></span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($stateChunks as $stateChunk)
                        <ul class="space-y-1.5">
                            @foreach ($stateChunk as $abbr => $stateName)
                                <li>
                                    <a href="{{ route('find-a-gym.state', ['state' => $abbr]) }}"
                                       class="group inline-flex w-full items-center gap-2 rounded-lg border-l-[3px] border-transparent px-3 py-1.5 text-[15px] font-medium text-[var(--color-ink-500)] transition-all duration-200 hover:translate-x-1 hover:border-[var(--color-primary)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-primary)]">
                                        <i class="fa-solid fa-chevron-right text-[10px] text-[var(--color-ink-300)] transition-colors group-hover:text-[var(--color-primary)]"></i>
                                        {{ $stateName }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>

            {{-- Services --}}
            <div>
                <div class="mb-6">
                    <h3 class="relative inline-block text-[22px] font-bold text-[var(--color-ink-900)] sm:text-[26px]">
                        {{ __('Browse by Service Type') }}
                        <span class="absolute -bottom-2 left-0 h-[3px] w-12 rounded-full bg-[var(--color-primary)]"></span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($serviceDirectory as $serviceItem)
                        <a href="{{ route('find-a-gym', ['service' => $serviceItem['key']]) }}"
                           class="group inline-flex items-center gap-3 rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 text-[15px] font-semibold text-[var(--color-ink-700)] transition-all duration-200 hover:-translate-y-0.5 hover:border-[var(--color-primary)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-primary)] hover:shadow-[var(--shadow-sm)]">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[var(--color-brand-50)] transition-colors group-hover:bg-white">
                                <img src="{{ $serviceItem['icon_path'] }}" alt="{{ $serviceItem['label'] }}"
                                     class="h-6 w-6 object-contain">
                            </span>
                            {{ $serviceItem['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         3 · FEATURED LISTINGS (example) + FILTER SIDEBAR
         ============================================================ --}}

</main>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">
<style>
    .noUi-target {
        background: var(--color-brand-50);
        border: none;
        box-shadow: none;
        height: 6px;
        border-radius: 999px;
    }
    .noUi-connect {
        background: var(--color-primary) !important;
    }
    .noUi-handle {
        background: #ffffff;
        border: 2px solid var(--color-primary);
        box-shadow: var(--shadow-sm);
        border-radius: 9999px;
        width: 18px;
        height: 18px;
        right: -9px;
        top: -7px;
        cursor: grab;
    }
    .noUi-handle:before, .noUi-handle:after { display: none; }
    .noUi-handle:active { cursor: grabbing; transform: scale(1.08); }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
<script>
    (function () {
        var slider = document.getElementById('price-slider');
        if (!slider) return;
        var minDisp = document.getElementById('price-min');
        var maxDisp = document.getElementById('price-max');
        noUiSlider.create(slider, {
            start: [15, 60],
            connect: true,
            range: { 'min': 0, 'max': 100 },
            step: 1,
            format: {
                to: function (v) { return Math.round(v); },
                from: function (v) { return v; }
            }
        });
        slider.noUiSlider.on('update', function (values, handle) {
            if (handle === 0) { minDisp.textContent = '$' + values[0]; }
            else { maxDisp.textContent = '$' + values[1]; }
        });
    })();
</script>
@endpush
