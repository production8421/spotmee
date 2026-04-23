@extends('layouts.web.master')

@section('title', $listing->name.' — SPOTMEE')

@section('content')
    @php
        use App\Support\RyjOptionIcon;

        $facilityKey   = (string) ($listing->facility_type ?? '');
        $petsKey       = (string) ($listing->pets_policy ?? '');
        $checkKey      = (string) ($listing->check_in_method ?? '');
        $areaLabel     = config('gym_listing.area_sizes.'.((string) $listing->area_size), (string) $listing->area_size);
        $facilityLabel = config('gym_listing.facility_types.'.$facilityKey, $facilityKey);
        $petsLabel     = config('gym_listing.pets_policies.'.$petsKey, $petsKey);
        $checkLabel    = config('gym_listing.check_in_methods.'.$checkKey, $checkKey);

        $services  = is_array($listing->service_options) ? $listing->service_options : [];
        $amenities = is_array($listing->amenities) ? $listing->amenities : [];
        $equipment = is_array($listing->equipment) ? $listing->equipment : [];

        $photos           = array_values(array_filter($photos ?? []));
        $photoCount       = count($photos);
        $mainPhoto        = $photos[0] ?? null;
        $thumbPhotos      = array_slice($photos, 1, 4);
        $thumbCount       = count($thumbPhotos);
        $hasGalleryThumbs = $thumbCount > 0;

        $stateCode  = strtoupper((string) $listing->state);
        $stateLabel = $stateLabel ?? config('gym_listing.states.'.$stateCode, $stateCode);

        $tier               = $pricing['tier'] ?? 'silver';
        $tierLabel          = ucfirst((string) $tier);
        $tierIconsFallback  = ['silver' => 'fa-medal', 'gold' => 'fa-trophy', 'platinum' => 'fa-gem'];
        $tierFaIcon         = $tierIconsFallback[$tier] ?? 'fa-medal';
        $tierIconUrl        = RyjOptionIcon::publicUrl($tier);
        $has40min           = ($slotOffers['offers_40min'] ?? false) && ($pricing['rate_40min'] ?? null) !== null;
        $has1hr             = ($slotOffers['offers_1hr']   ?? false) && ($pricing['rate_1hr']   ?? null) !== null;
        $showPricingSection = $has40min || $has1hr;

        $fullAddress = trim(collect([$listing->address, $listing->city, $stateCode, $listing->postal_code])->filter()->implode(', '));

        // Generic icon fallbacks
        $serviceIconMap = [
            'boxing' => 'fa-hand-fist',
            'yoga' => 'fa-spa',
            'crossfit' => 'fa-bolt',
            'personal_training' => 'fa-user-gear',
            'weightlifting' => 'fa-dumbbell',
            'cardio' => 'fa-heart-pulse',
            'group_classes' => 'fa-users',
        ];
        $amenityIconMap = [
            'wifi' => 'fa-wifi',
            'ac' => 'fa-snowflake',
            'air_conditioning' => 'fa-snowflake',
            'shower' => 'fa-shower',
            'parking' => 'fa-square-parking',
            'towels' => 'fa-layer-group',
            'water' => 'fa-bottle-water',
            'music' => 'fa-music',
            'tv' => 'fa-tv',
            'lockers' => 'fa-lock',
            'restroom' => 'fa-restroom',
            'heating' => 'fa-fire',
            'mirrors' => 'fa-square',
            'first_aid' => 'fa-briefcase-medical',
        ];
        $petsFaIcon = match ($petsKey) {
            'allowed', 'yes' => 'fa-paw',
            'not_allowed', 'no' => 'fa-ban',
            default => 'fa-paw',
        };
        $checkFaIcon = match ($checkKey) {
            'self_checkin', 'smart_lock' => 'fa-key',
            'host_greets', 'in_person' => 'fa-user-check',
            default => 'fa-door-open',
        };
    @endphp

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/gym-main-ryj.css') }}?v=2">
    @endpush

    <main class="spotmee-main spotmee-gym-page">

        {{-- ============================================================
             1 · BREADCRUMB + TITLE
             ============================================================ --}}
        <section class="site-container pt-6 sm:pt-10">
            <nav aria-label="Breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-[13px] font-medium text-[var(--color-ink-500)]">
                <a href="{{ route('home') }}" class="hover:text-[var(--color-primary)]">{{ __('Home') }}</a>
                <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
                <a href="{{ route('find-a-gym') }}" class="hover:text-[var(--color-primary)]">{{ __('Gyms') }}</a>
                <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
                <a href="{{ route('find-a-gym.state', ['state' => $stateCode]) }}" class="hover:text-[var(--color-primary)]">{{ $stateLabel }}</a>
                <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
                <span class="text-[var(--color-ink-900)]">{{ $listing->name }}</span>
            </nav>

            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end" data-aos="fade-up">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($facilityLabel !== '')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-bold uppercase tracking-wide text-[var(--color-primary)]">
                                <i class="fa-solid fa-building-shield text-[10px]"></i>
                                {{ $facilityLabel }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-bold uppercase tracking-wide text-[var(--color-primary)]">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            {{ __('Verified host') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-bold uppercase tracking-wide text-[var(--color-primary)]">
                            <i class="fa-solid {{ $tierFaIcon }} text-[10px]"></i>
                            {{ $tierLabel }} {{ __('host') }}
                        </span>
                    </div>
                    <h1 class="mt-3 text-[28px] font-bold leading-tight text-[var(--color-ink-900)] sm:text-[36px]">
                        {{ $listing->name }}
                    </h1>
                    <div class="mt-2 flex items-center gap-2 text-[15px] text-[var(--color-ink-500)]">
                        <i class="fa-solid fa-location-dot text-[var(--color-primary)]"></i>
                        <span class="truncate">{{ $fullAddress !== '' ? $fullAddress : ($listing->city.', '.$stateCode) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button"
                            class="inline-flex h-11 items-center gap-2 rounded-full border border-[var(--color-ink-100)] bg-white px-4 text-[14px] font-semibold text-[var(--color-ink-700)] transition-all hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]"
                            onclick="navigator.share ? navigator.share({title: '{{ addslashes($listing->name) }}', url: location.href}) : (navigator.clipboard?.writeText(location.href), this.querySelector('span').textContent='{{ __('Copied!') }}')">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        <span>{{ __('Share') }}</span>
                    </button>
                    <button type="button"
                            class="inline-flex h-11 items-center gap-2 rounded-full border border-[var(--color-ink-100)] bg-white px-4 text-[14px] font-semibold text-[var(--color-ink-700)] transition-all hover:border-rose-400 hover:text-rose-500">
                        <i class="fa-solid fa-heart"></i>
                        <span>{{ __('Save') }}</span>
                    </button>
                </div>
            </div>
        </section>


        {{-- ============================================================
             2 · PHOTO GALLERY
             ============================================================ --}}
        <section class="site-container pt-6">
            <div class="grid grid-cols-1 gap-3 overflow-hidden rounded-[24px] sm:grid-cols-2 sm:h-[480px]"
                 data-aos="fade-up">

                {{-- Main --}}
                <div class="group relative col-span-1 h-[280px] cursor-pointer overflow-hidden rounded-[20px] bg-[var(--color-brand-50)] sm:h-full sm:rounded-[24px]"
                     role="button" tabindex="0"
                     onclick="openPhotoModal(0)" onkeydown="if(event.key==='Enter')openPhotoModal(0)">
                    @if ($mainPhoto)
                        <img src="{{ $mainPhoto }}" alt="{{ $listing->name }}"
                             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="flex h-full items-center justify-center text-[var(--color-brand-300)]">
                            <i class="fa-solid fa-dumbbell text-6xl"></i>
                        </div>
                    @endif
                    @if ($photoCount > 1)
                        <button type="button"
                                onclick="event.stopPropagation(); openPhotoModal(0)"
                                class="absolute bottom-4 right-4 inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-[13px] font-bold text-[var(--color-ink-900)] shadow-[var(--shadow-md)] backdrop-blur transition-all hover:bg-white">
                            <i class="fa-solid fa-images text-[var(--color-primary)]"></i>
                            {{ __('View all :count photos', ['count' => $photoCount]) }}
                        </button>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if ($hasGalleryThumbs)
                    <div class="col-span-1 grid h-[260px] grid-cols-2 gap-3 sm:h-full">
                        @foreach ($thumbPhotos as $idx => $thumbUrl)
                            <div class="group relative cursor-pointer overflow-hidden rounded-[16px] bg-[var(--color-brand-50)] sm:rounded-[20px]"
                                 role="button" tabindex="0"
                                 onclick="openPhotoModal({{ $idx + 1 }})"
                                 onkeydown="if(event.key==='Enter')openPhotoModal({{ $idx + 1 }})">
                                <img src="{{ $thumbUrl }}" alt="{{ __('Gallery photo :n', ['n' => $idx + 2]) }}"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy" decoding="async">
                                @if ($idx === 3 && $photoCount > 5)
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/45 text-[14px] font-bold text-white backdrop-blur-[1px]">
                                        +{{ $photoCount - 5 }} {{ __('more') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @for ($i = $thumbCount; $i < 4; $i++)
                            <div class="rounded-[16px] bg-[var(--color-brand-50)] sm:rounded-[20px]"></div>
                        @endfor
                    </div>
                @endif
            </div>
        </section>


        {{-- ============================================================
             3 · MAIN CONTENT · 2-COLUMN (LEFT = details, RIGHT = sticky pricing)
             ============================================================ --}}
        <section class="site-container py-12 pb-28 sm:py-16 lg:pb-16">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-3 lg:gap-12">

                <div class="lg:col-span-2 space-y-10">

                    {{-- About --}}
                    @if (filled($listing->description))
                        <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                             data-aos="fade-up">
                            <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                                <i class="fa-solid fa-circle-info text-[var(--color-primary)]"></i>
                                {{ __('About this space') }}
                            </h2>
                            <div class="mt-4 text-[15px] leading-relaxed text-[var(--color-ink-700)]">
                                {!! nl2br(e($listing->description)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Facility details --}}
                    <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                         data-aos="fade-up">
                        <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                            <i class="fa-solid fa-list-check text-[var(--color-primary)]"></i>
                            {{ __('Facility details') }}
                        </h2>
                        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-6">
                            @php
                                $facilityItems = [
                                    ['label' => __('Room type'), 'value' => $facilityLabel, 'icon' => RyjOptionIcon::publicUrl($facilityKey), 'fa' => 'fa-door-open'],
                                    ['label' => __('Area size'), 'value' => $areaLabel !== '' ? $areaLabel.' '.__('sq ft') : null, 'icon' => RyjOptionIcon::publicUrl('area_size'), 'fa' => 'fa-ruler-combined'],
                                    ['label' => __('Pets'),      'value' => $petsLabel,     'icon' => RyjOptionIcon::publicUrl($petsKey),     'fa' => $petsFaIcon],
                                    ['label' => __('Check-in'),  'value' => $checkLabel,    'icon' => RyjOptionIcon::publicUrl($checkKey),    'fa' => $checkFaIcon],
                                ];
                            @endphp
                            @foreach ($facilityItems as $item)
                                <div class="group flex flex-col items-center rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)]/40 p-5 text-center transition-all hover:-translate-y-0.5 hover:border-[var(--color-brand-200)] hover:bg-[var(--color-brand-50)]">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[var(--color-primary)] shadow-[var(--shadow-sm)] transition-all group-hover:bg-[var(--color-primary)] group-hover:text-white">
                                        @if (! empty($item['icon']))
                                            <img src="{{ $item['icon'] }}" alt="" class="h-6 w-6 object-contain">
                                        @else
                                            <i class="fa-solid {{ $item['fa'] }} text-[18px]"></i>
                                        @endif
                                    </div>
                                    <span class="mt-3 text-[11px] font-semibold uppercase tracking-wide text-[var(--color-ink-500)]">{{ $item['label'] }}</span>
                                    <span class="mt-1 text-[14px] font-bold text-[var(--color-ink-900)]">{{ ! empty($item['value']) ? $item['value'] : '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Services --}}
                    @if ($services !== [])
                        <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                             data-aos="fade-up">
                            <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                                <i class="fa-solid fa-dumbbell text-[var(--color-primary)]"></i>
                                {{ __('Services offered') }}
                            </h2>
                            <div class="mt-5 flex flex-wrap gap-2.5">
                                @foreach ($services as $svc)
                                    @php
                                        $svcKey   = is_string($svc) ? $svc : '';
                                        $svcIcon  = RyjOptionIcon::publicUrl($svcKey);
                                        $svcFa    = $serviceIconMap[$svcKey] ?? 'fa-dumbbell';
                                        $svcLabel = ucwords(str_replace(['_', '-'], ' ', $svcKey));
                                    @endphp
                                    <span class="inline-flex items-center gap-2 rounded-full border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] px-4 py-2 text-[13px] font-semibold text-[var(--color-primary)]">
                                        @if ($svcIcon)
                                            <img src="{{ $svcIcon }}" alt="" class="h-4 w-4 object-contain">
                                        @else
                                            <i class="fa-solid {{ $svcFa }} text-[12px]"></i>
                                        @endif
                                        {{ $svcLabel }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Equipment --}}
                    @if ($equipment !== [])
                        <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                             data-aos="fade-up">
                            <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                                <i class="fa-solid fa-toolbox text-[var(--color-primary)]"></i>
                                {{ __('Equipment available') }}
                            </h2>
                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($equipment as $item)
                                    @php
                                        $eqName = is_array($item) ? (string) ($item['name'] ?? '') : (string) $item;
                                        $qty    = is_array($item) ? (int) ($item['quantity'] ?? $item['count'] ?? 1) : 1;
                                        $eqIcon = RyjOptionIcon::publicUrl($eqName);
                                    @endphp
                                    @if ($eqName !== '')
                                        <div class="flex items-center gap-3 rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                                @if ($eqIcon)
                                                    <img src="{{ $eqIcon }}" alt="" class="h-5 w-5 object-contain">
                                                @else
                                                    <i class="fa-solid fa-dumbbell text-[14px]"></i>
                                                @endif
                                            </div>
                                            <span class="flex-1 text-[14px] font-semibold text-[var(--color-ink-900)]">
                                                {{ ucwords(str_replace('_', ' ', $eqName)) }}
                                            </span>
                                            <span class="rounded-full bg-[var(--color-brand-50)] px-2.5 py-0.5 text-[12px] font-bold text-[var(--color-primary)]">
                                                × {{ $qty }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Amenities --}}
                    @if ($amenities !== [])
                        <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                             data-aos="fade-up">
                            <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                                <i class="fa-solid fa-star text-[var(--color-primary)]"></i>
                                {{ __('Amenities') }}
                            </h2>
                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($amenities as $am)
                                    @php
                                        $amKey   = is_string($am) ? $am : '';
                                        $amIcon  = RyjOptionIcon::publicUrl($amKey);
                                        $amFa    = $amenityIconMap[$amKey] ?? 'fa-circle-check';
                                        $amLabel = ucwords(str_replace('_', ' ', $amKey));
                                    @endphp
                                    <div class="flex items-center gap-3 rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 transition-all hover:border-[var(--color-brand-200)] hover:bg-[var(--color-brand-50)]">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                            @if ($amIcon)
                                                <img src="{{ $amIcon }}" alt="" class="h-5 w-5 object-contain">
                                            @else
                                                <i class="fa-solid {{ $amFa }} text-[14px]"></i>
                                            @endif
                                        </div>
                                        <span class="text-[14px] font-semibold text-[var(--color-ink-900)]">{{ $amLabel }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Location --}}
                    <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
                         data-aos="fade-up">
                        <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
                            <i class="fa-solid fa-map-location-dot text-[var(--color-primary)]"></i>
                            {{ __('Location') }}
                        </h2>
                        <div class="mt-5 overflow-hidden rounded-2xl border border-[var(--color-brand-100)]">
                            <div class="flex h-[260px] flex-col items-center justify-center gap-3 bg-gradient-to-br from-[var(--color-brand-50)] to-white text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-[var(--shadow-md)]">
                                    <i class="fa-solid fa-map-pin text-[20px]"></i>
                                </div>
                                <p class="max-w-md px-6 text-[15px] font-semibold text-[var(--color-ink-900)]">
                                    {{ $fullAddress !== '' ? $fullAddress : $listing->city.', '.$stateCode }}
                                </p>
                                @if ($fullAddress !== '')
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($fullAddress) }}"
                                       target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 text-[13px] font-semibold text-[var(--color-primary)] hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                                        {{ __('Open in Google Maps') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ============================================================
                     RIGHT · STICKY PRICING / QUICK-RESERVE
                     ============================================================ --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-5">
                        @if ($showPricingSection)
                            <div class="overflow-hidden rounded-[24px] border border-[var(--color-brand-100)] bg-white shadow-[var(--shadow-md)]"
                                 data-aos="fade-left">

                                {{-- Tier header --}}
                                <div class="flex items-center justify-between bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-brand-500)] px-6 py-4 text-white">
                                    <span class="inline-flex items-center gap-2 text-[13px] font-bold uppercase tracking-wide">
                                        @if ($tierIconUrl)
                                            <img src="{{ $tierIconUrl }}" alt="" class="h-5 w-5 object-contain">
                                        @else
                                            <i class="fa-solid {{ $tierFaIcon }}"></i>
                                        @endif
                                        {{ $tierLabel }} {{ __('host') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold opacity-90">
                                        <i class="fa-solid fa-circle-check"></i>
                                        {{ __('Verified') }}
                                    </span>
                                </div>

                                <div class="p-6">
                                    <p class="text-[13px] font-semibold uppercase tracking-wide text-[var(--color-ink-500)]">
                                        {{ __('Session pricing') }}
                                    </p>

                                    <div class="mt-4 space-y-3">
                                        @if ($has40min)
                                            <div class="flex items-center justify-between rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)]/60 px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[var(--color-primary)]">
                                                        <i class="fa-solid fa-stopwatch"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[13px] font-semibold text-[var(--color-ink-900)]">{{ __('Standard') }}</p>
                                                        <p class="text-[12px] text-[var(--color-ink-500)]">{{ __('40 minute session') }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[20px] font-bold leading-none text-[var(--color-primary)]">
                                                        ${{ number_format((float) $pricing['rate_40min'], 2) }}
                                                    </p>
                                                    <p class="mt-0.5 text-[11px] font-medium text-[var(--color-ink-500)]">/ 40 min</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($has1hr)
                                            <div class="flex items-center justify-between rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)]/60 px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[var(--color-primary)]">
                                                        <i class="fa-solid fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[13px] font-semibold text-[var(--color-ink-900)]">{{ __('Extended') }}</p>
                                                        <p class="text-[12px] text-[var(--color-ink-500)]">{{ __('1 hour session') }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[20px] font-bold leading-none text-[var(--color-primary)]">
                                                        ${{ number_format((float) $pricing['rate_1hr'], 2) }}
                                                    </p>
                                                    <p class="mt-0.5 text-[11px] font-medium text-[var(--color-ink-500)]">/ hour</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="button" onclick="openBookingModal()"
                                            class="btn btn-primary btn-lg mt-5 w-full justify-center">
                                        {{ __('Reserve now') }}
                                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                                    </button>

                                    <p class="mt-3 text-center text-[12px] text-[var(--color-ink-500)]">
                                        <i class="fa-solid fa-shield-halved mr-1 text-[var(--color-primary)]"></i>
                                        {{ __('Secure checkout powered by Stripe') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Trust highlights --}}
                        <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)]">
                            @php
                                $trust = [
                                    ['fa' => 'fa-circle-check',  'title' => __('Verified host'),     'body' => __('Every host is identity-checked.')],
                                    ['fa' => 'fa-lock',          'title' => __('Secure payments'),   'body' => __('Cards are processed via Stripe.')],
                                    ['fa' => 'fa-headset',       'title' => __('Dedicated support'), 'body' => __('We\'re here if anything goes wrong.')],
                                ];
                            @endphp
                            <ul class="space-y-4">
                                @foreach ($trust as $t)
                                    <li class="flex gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                            <i class="fa-solid {{ $t['fa'] }} text-[13px]"></i>
                                        </span>
                                        <div>
                                            <p class="text-[14px] font-bold text-[var(--color-ink-900)]">{{ $t['title'] }}</p>
                                            <p class="text-[13px] text-[var(--color-ink-500)]">{{ $t['body'] }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </section>


        {{-- ============================================================
             4 · BOOKING FORM (React) · RENDERED INSIDE A MODAL
             ============================================================ --}}
        <div id="spotmee-booking-modal"
             class="fixed inset-0 z-[110] hidden items-end justify-center sm:items-center"
             role="dialog" aria-modal="true" aria-labelledby="spotmee-booking-modal-title" aria-hidden="true">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBookingModal()"></div>

            <div class="relative z-10 flex max-h-[94vh] w-full flex-col overflow-hidden rounded-t-[28px] border border-[var(--color-brand-100)] bg-white shadow-2xl sm:max-h-[90vh] sm:w-[min(760px,94vw)] sm:rounded-[28px]">

                {{-- Modal header --}}
                <div class="flex shrink-0 items-center justify-between border-b border-[var(--color-brand-100)] bg-gradient-to-r from-[var(--color-brand-50)] to-white px-5 py-4 sm:px-7 sm:py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-[var(--color-primary)] shadow-[var(--shadow-sm)]">
                            <i class="fa-solid fa-calendar-check"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 id="spotmee-booking-modal-title" class="truncate text-[18px] font-bold text-[var(--color-ink-900)] sm:text-[20px]">
                                {{ __('Book your session') }}
                            </h2>
                            <p class="truncate text-[13px] text-[var(--color-ink-500)]">
                                {{ $listing->name }} — {{ __('Pick date, time & session length') }}
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeBookingModal()" aria-label="{{ __('Close') }}"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[var(--color-ink-100)] text-[var(--color-ink-700)] transition-colors hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Modal body (scrollable) --}}
                <div class="flex-1 overflow-y-auto px-4 py-5 sm:px-7 sm:py-7">
                    @include('web.find-a-gym.partials.gym-booking-form', ['bookingBootstrap' => $bookingBootstrap])
                </div>
            </div>
        </div>

        {{-- Mobile floating Reserve CTA (shown below lg, hidden when the modal is already open) --}}
        <div id="spotmee-booking-floating-bar"
             class="fixed inset-x-0 bottom-0 z-[90] border-t border-[var(--color-brand-100)] bg-white/95 p-3 shadow-[0_-6px_24px_rgba(0,109,119,0.08)] backdrop-blur lg:hidden">
            <div class="site-container flex items-center justify-between gap-3">
                @if ($has1hr || $has40min)
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[var(--color-ink-500)]">{{ __('From') }}</p>
                        <p class="text-[16px] font-bold text-[var(--color-primary)]">
                            ${{ number_format((float) ($has40min ? $pricing['rate_40min'] : $pricing['rate_1hr']), 2) }}
                            <span class="text-[11px] font-medium text-[var(--color-ink-500)]">/ {{ $has40min ? __('40 min') : __('hour') }}</span>
                        </p>
                    </div>
                @else
                    <div></div>
                @endif
                <button type="button" onclick="openBookingModal()" class="btn btn-primary">
                    {{ __('Reserve now') }}
                    <i class="fa-solid fa-arrow-right text-[13px]"></i>
                </button>
            </div>
        </div>


        {{-- ============================================================
             5 · PHOTO MODAL
             ============================================================ --}}
        @if ($photoCount > 0)
            <div id="ryj-photo-modal" class="fixed inset-0 z-[100] hidden items-center justify-center"
                 role="dialog" aria-modal="true" aria-labelledby="ryj-photo-modal-title">
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closePhotoModal()"></div>

                <div class="relative z-10 flex h-[90vh] w-[min(1100px,95vw)] flex-col overflow-hidden rounded-[24px] bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-[var(--color-brand-100)] px-6 py-4">
                        <h3 id="ryj-photo-modal-title" class="text-[16px] font-bold text-[var(--color-ink-900)]">
                            {{ $listing->name }} — {{ __('Gallery') }}
                        </h3>
                        <button type="button" onclick="closePhotoModal()" aria-label="{{ __('Close') }}"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--color-ink-100)] text-[var(--color-ink-700)] transition-colors hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="relative flex-1 bg-black">
                        <button type="button" onclick="navigatePhoto(-1)" aria-label="{{ __('Previous') }}"
                                class="absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-[var(--color-ink-900)] shadow-lg backdrop-blur transition-all hover:bg-white">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <img id="ryj-modal-main-img" src="" alt="" class="h-full w-full object-contain">
                        <button type="button" onclick="navigatePhoto(1)" aria-label="{{ __('Next') }}"
                                class="absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-[var(--color-ink-900)] shadow-lg backdrop-blur transition-all hover:bg-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-black/60 px-4 py-1.5 text-[13px] font-semibold text-white backdrop-blur">
                            <span id="ryj-current-photo">1</span> / <span id="ryj-total-photos">{{ $photoCount }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 overflow-x-auto border-t border-[var(--color-brand-100)] bg-white p-3">
                        @foreach ($photos as $index => $photoUrl)
                            <div class="ryj-modal-thumb h-16 w-24 shrink-0 cursor-pointer overflow-hidden rounded-lg border-2 border-transparent transition-all data-[active=true]:border-[var(--color-primary)] data-[active=true]:shadow-md"
                                 data-index="{{ $index }}"
                                 onclick="showModalPhoto({{ $index }})">
                                <img src="{{ $photoUrl }}" alt="{{ __('Photo :n', ['n' => $index + 1]) }}"
                                     class="h-full w-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </main>
@endsection

@push('scripts')
    <script>
        function openBookingModal() {
            const modal = document.getElementById('spotmee-booking-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            // Let React/flatpickr recompute layout once the modal is visible
            window.dispatchEvent(new Event('resize'));
        }

        function closeBookingModal() {
            const modal = document.getElementById('spotmee-booking-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            const modal = document.getElementById('spotmee-booking-modal');
            if (!modal || modal.classList.contains('hidden')) return;
            if (e.key === 'Escape') closeBookingModal();
        });

        // Auto-open on #book hash (e.g. mail links can deep-link the reservation modal)
        if (window.location.hash === '#book') {
            window.addEventListener('DOMContentLoaded', openBookingModal);
        }
    </script>
@endpush

@if ($photoCount > 0)
    @push('scripts')
        <script>
            let currentPhotoIndex = 0;
            let allPhotos = [];

            function openPhotoModal(photoIndex) {
                const thumbs = document.querySelectorAll('.ryj-modal-thumb');
                allPhotos = Array.from(thumbs).map(function (thumb) {
                    return thumb.querySelector('img').src;
                });
                currentPhotoIndex = photoIndex;
                showModalPhoto(currentPhotoIndex);
                const modal = document.getElementById('ryj-photo-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closePhotoModal() {
                const modal = document.getElementById('ryj-photo-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            function showModalPhoto(index) {
                if (allPhotos.length === 0) return;
                currentPhotoIndex = index;
                document.getElementById('ryj-modal-main-img').src = allPhotos[index];
                document.getElementById('ryj-current-photo').textContent = String(index + 1);
                document.querySelectorAll('.ryj-modal-thumb').forEach(function (thumb, i) {
                    thumb.dataset.active = (i === index) ? 'true' : 'false';
                });
                const activeThumb = document.querySelector('.ryj-modal-thumb[data-active="true"]');
                if (activeThumb) {
                    activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }

            function navigatePhoto(direction) {
                if (allPhotos.length === 0) return;
                currentPhotoIndex += direction;
                if (currentPhotoIndex < 0) {
                    currentPhotoIndex = allPhotos.length - 1;
                } else if (currentPhotoIndex >= allPhotos.length) {
                    currentPhotoIndex = 0;
                }
                showModalPhoto(currentPhotoIndex);
            }

            document.addEventListener('keydown', function (e) {
                const modal = document.getElementById('ryj-photo-modal');
                if (!modal || modal.classList.contains('hidden')) return;
                if (e.key === 'Escape') closePhotoModal();
                else if (e.key === 'ArrowLeft') navigatePhoto(-1);
                else if (e.key === 'ArrowRight') navigatePhoto(1);
            });
        </script>
    @endpush
@endif
