@extends('layouts.web.master')
@section('title', 'Gym Listings — SPOTMEE')
@section('content')
    @php
        $selectedState   = strtoupper((string) ($state ?? request('state', '')));
        $selectedService = (string) ($selectedService ?? request('service', ''));
        $searchBy        = (string) ($searchBy ?? request('searchby', ''));
        $searchCity      = (string) ($searchCity ?? request('city', ''));
        $stateLabel      = (string) ($stateLabel ?? config('gym_listing.states.'.$selectedState, $selectedState));
        $stateLabel      = $stateLabel !== '' ? $stateLabel : __('All States');

        $settings    = \App\Models\ApplicationSetting::instance();
        $totalGyms   = isset($listings) ? $listings->total() : 0;
        $countLabel  = trans_choice('{0} No gyms found|{1} 1 gym found|[2,*] :count gyms found', $totalGyms, ['count' => $totalGyms]);

        $serviceFilters = [
            ['key' => 'boxing',            'label' => 'Boxing',            'icon_path' => asset('images/rent-your-jim/boxing.png')],
            ['key' => 'yoga',              'label' => 'Yoga',              'icon_path' => asset('images/rent-your-jim/yoga.png')],
            ['key' => 'crossfit',          'label' => 'CrossFit',          'icon_path' => asset('images/rent-your-jim/fitness.png')],
            ['key' => 'personal_training', 'label' => 'Personal Training', 'icon_path' => asset('images/rent-your-jim/personal-training.png')],
            ['key' => 'cardio',            'label' => 'Cardio',            'icon_path' => asset('images/rent-your-jim/cardio.png')],
            ['key' => 'group_classes',     'label' => 'Group Classes',     'icon_path' => asset('images/rent-your-jim/group_class.png')],
        ];
        $activeServiceLabel = collect($serviceFilters)->firstWhere('key', $selectedService)['label'] ?? '';
    @endphp

    <main class="spotmee-main">

        {{-- ============================================================
             1 · HERO BANNER (state-aware)
             ============================================================ --}}
        <section class="site-container pt-6 sm:pt-10">
            <div class="inner-banner"
                 style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.9)] via-[rgba(0,109,119,0.6)] to-[rgba(131,197,190,0.25)]"></div>

                <div class="inner-banner__content">
                    <nav aria-label="Breadcrumb" class="mb-6 flex items-center justify-center gap-2 text-[13px] font-medium text-white/80" data-aos="fade-down">
                        <a href="{{ route('home') }}" class="hover:text-white">{{ __('Home') }}</a>
                        <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
                        <a href="{{ route('find-a-gym') }}" class="hover:text-white">{{ __('Find a Gym') }}</a>
                        @if ($selectedState !== '')
                            <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
                            <span class="text-white">{{ $stateLabel }}</span>
                        @endif
                    </nav>

                    <span class="inner-banner__eyebrow" data-aos="fade-down" data-aos-delay="50">
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $selectedState !== '' ? $stateLabel : __('All States') }}
                    </span>
                    <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                        {{ __('Private gyms in') }}
                        <span class="text-[var(--color-brand-200)]">{{ $stateLabel }}</span>
                    </h1>
                    <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                        {{ __('Browse verified hosts, pick a service you love, and book your session in minutes.') }}
                    </p>

                    {{-- Refine search --}}
                    <form action="{{ route('find-a-gym') }}" method="GET"
                          class="mx-auto mt-7 flex w-full max-w-2xl flex-col gap-2 rounded-2xl bg-white/95 p-2.5 shadow-[var(--shadow-lg)] backdrop-blur sm:flex-row sm:items-center"
                          data-aos="fade-up" data-aos-delay="300">
                        <label class="flex flex-1 items-center gap-3 rounded-xl bg-[var(--color-brand-50)] px-4 py-2.5">
                            <i class="fa-solid fa-magnifying-glass text-[var(--color-primary)]"></i>
                            <input type="text" name="searchby" value="{{ $searchBy }}"
                                   placeholder="{{ __('City, zip or keyword') }}"
                                   class="w-full border-0 bg-transparent p-0 text-[15px] text-[var(--color-ink-900)] placeholder:text-[var(--color-ink-400)] focus:outline-none focus:ring-0">
                            @if ($selectedService !== '')
                                <input type="hidden" name="service" value="{{ $selectedService }}">
                            @endif
                        </label>
                        <button type="submit" class="btn btn-primary justify-center sm:w-auto">
                            {{ __('Search') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>


        {{-- ============================================================
             2 · SERVICE FILTER CHIPS
             ============================================================ --}}
        <section class="site-container pt-10 sm:pt-14">
            <div class="rounded-[22px] border border-[var(--color-brand-100)] bg-white p-4 shadow-[var(--shadow-sm)] sm:p-5"
                 data-aos="fade-up">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="mr-2 hidden items-center gap-2 px-1 text-[13px] font-semibold uppercase tracking-[0.08em] text-[var(--color-ink-500)] sm:inline-flex">
                        <i class="fa-solid fa-filter text-[11px] text-[var(--color-primary)]"></i>
                        {{ __('Filter') }}
                    </span>

                    @php
                        $allGymsHref = $selectedState !== ''
                            ? route('find-a-gym.state', ['state' => $selectedState])
                            : route('find-a-gym', ['searchby' => $searchBy, 'city' => $searchCity]);
                        $allActive = $selectedService === '';
                    @endphp
                    <a href="{{ $allGymsHref }}"
                       @class([
                            'inline-flex items-center gap-2 rounded-full border px-4 py-2 text-[13px] font-semibold transition-all duration-200',
                            'border-[var(--color-primary)] bg-[var(--color-primary)] text-white shadow-[var(--shadow-sm)]' => $allActive,
                            'border-[var(--color-brand-100)] bg-white text-[var(--color-ink-700)] hover:border-[var(--color-primary)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-primary)]' => ! $allActive,
                       ])>
                        <i class="fa-solid fa-layer-group text-[12px]"></i>
                        {{ __('All Gyms') }}
                    </a>

                    @foreach ($serviceFilters as $service)
                        @php
                            $isActive = $selectedService === $service['key'];
                            $serviceHref = $selectedState !== ''
                                ? route('find-a-gym.state', ['state' => $selectedState, 'service' => $service['key']])
                                : route('find-a-gym', ['service' => $service['key'], 'searchby' => $searchBy, 'city' => $searchCity]);
                        @endphp
                        <a href="{{ $serviceHref }}"
                           @class([
                                'inline-flex items-center gap-2 rounded-full border px-4 py-2 text-[13px] font-semibold transition-all duration-200',
                                'border-[var(--color-primary)] bg-[var(--color-primary)] text-white shadow-[var(--shadow-sm)]' => $isActive,
                                'border-[var(--color-brand-100)] bg-white text-[var(--color-ink-700)] hover:border-[var(--color-primary)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-primary)]' => ! $isActive,
                           ])>
                            <img src="{{ $service['icon_path'] }}" alt="" class="h-5 w-5 object-contain shrink-0">
                            {{ $service['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- ============================================================
             3 · RESULTS HEADER + ACTIVE FILTERS
             ============================================================ --}}
        <section class="site-container pt-8">
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                        <i class="fa-solid fa-bullseye"></i>
                    </span>
                    <div>
                        <p class="text-[15px] font-semibold text-[var(--color-ink-900)]">{{ $countLabel }}</p>
                        <p class="text-[13px] text-[var(--color-ink-500)]">
                            {{ __('Showing listings in') }}
                            <span class="font-semibold text-[var(--color-ink-900)]">{{ $stateLabel }}</span>
                            @if ($activeServiceLabel !== '')
                                · {{ __('Service') }}:
                                <span class="font-semibold text-[var(--color-primary)]">{{ $activeServiceLabel }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($searchBy !== '' || $selectedService !== '' || $searchCity !== '')
                        <a href="{{ $selectedState !== '' ? route('find-a-gym.state', ['state' => $selectedState]) : route('find-a-gym') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-[var(--color-brand-100)] bg-white px-3 py-1.5 text-[12px] font-semibold text-[var(--color-ink-700)] transition-colors hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                            <i class="fa-solid fa-xmark text-[11px]"></i>
                            {{ __('Clear filters') }}
                        </a>
                    @endif

                    <label class="flex items-center gap-2 text-[13px] font-medium text-[var(--color-ink-500)]">
                        {{ __('Sort by') }}:
                        <div class="relative">
                            <select onchange="window.location.href = this.value"
                                    class="cursor-pointer appearance-none rounded-full border border-[var(--color-brand-100)] bg-white py-2 pl-4 pr-9 text-[13px] font-semibold text-[var(--color-ink-900)] focus:border-[var(--color-primary)] focus:outline-none">
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['sort' => 'recent'])) }}" @selected(request('sort', 'recent') === 'recent')>{{ __('Newest') }}</option>
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['sort' => 'name'])) }}" @selected(request('sort') === 'name')>{{ __('Name A–Z') }}</option>
                            </select>
                            <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-[var(--color-ink-500)]"></i>
                        </div>
                    </label>
                </div>
            </div>
        </section>


        {{-- ============================================================
             4 · RESULTS GRID
             ============================================================ --}}
        <section class="site-container pb-20 pt-6">
            @if ($totalGyms > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7">
                    @foreach ($listings as $listing)
                        @php
                            $services      = is_array($listing->service_options) ? $listing->service_options : [];
                            $areaLabel     = config('gym_listing.area_sizes.'.((string) $listing->area_size), (string) $listing->area_size);
                            $facilityLabel = config('gym_listing.facility_types.'.((string) $listing->facility_type), (string) $listing->facility_type);

                            $tierKey   = method_exists($listing, 'hostTierKey') ? $listing->hostTierKey() : 'silver';
                            $tierRates = $settings?->publicGuestTierRates($tierKey) ?? [];
                            $rate1hr   = $tierRates['rate_1hr'] ?? null;

                            $reviewsCount = (int) ($listing->reviews_count ?? 0);
                            $reviewsAvg   = $reviewsCount > 0 ? round((float) $listing->reviews_avg_rating, 1) : 0.0;
                        @endphp

                        <article class="group flex h-full flex-col overflow-hidden rounded-[24px] border border-[var(--color-brand-100)] bg-white shadow-[var(--shadow-sm)] transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
                                 data-aos="fade-up">
                            <a href="{{ route('gym.show', ['slug' => $listing->slug]) }}" class="block">
                                <div class="relative h-[220px] overflow-hidden bg-[var(--color-brand-50)]">
                                    @if ($listing->mainImageUrl())
                                        <img src="{{ $listing->mainImageUrl() }}"
                                             alt="{{ $listing->name }}"
                                             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                             loading="lazy" decoding="async">
                                    @else
                                        <div class="flex h-full items-center justify-center text-[var(--color-brand-300)]">
                                            <i class="fa-solid fa-dumbbell text-5xl"></i>
                                        </div>
                                    @endif

                                    {{-- Gradient overlay for contrast --}}
                                    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/40 to-transparent"></div>

                                    @if ($facilityLabel !== '')
                                        <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-[12px] font-bold text-[var(--color-primary)] shadow-[var(--shadow-sm)] backdrop-blur">
                                            {{ $facilityLabel }}
                                        </span>
                                    @endif

                                    <div class="absolute right-4 top-4 flex items-center gap-2">
                                        @if ($reviewsCount > 0)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1.5 text-[12px] font-bold text-[var(--color-ink-900)] shadow-[var(--shadow-sm)] backdrop-blur">
                                                <i class="fa-solid fa-star text-amber-400 text-[11px]"></i>
                                                {{ number_format($reviewsAvg, 1) }}
                                                <span class="font-semibold text-[var(--color-ink-500)]">({{ $reviewsCount }})</span>
                                            </span>
                                        @endif
                                        <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation();"
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-white/95 text-[var(--color-ink-400)] shadow-[var(--shadow-sm)] backdrop-blur transition-all hover:bg-white hover:text-rose-500"
                                                aria-label="{{ __('Save to favorites') }}">
                                            <i class="fa-solid fa-heart text-[13px]"></i>
                                        </button>
                                    </div>

                                    @if (! empty($rate1hr) && (float) $rate1hr > 0)
                                        <span class="absolute bottom-4 left-4 inline-flex items-center gap-1 rounded-full bg-[var(--color-primary)] px-3 py-1.5 text-[13px] font-bold text-white shadow-[var(--shadow-md)]">
                                            <span class="text-[11px] font-medium opacity-80">{{ __('From') }}</span>
                                            <span class="opacity-80">$</span>{{ number_format((float) $rate1hr, 0) }}
                                            <span class="text-[11px] font-medium opacity-80">/hr</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col p-6">
                                    <h3 class="text-[19px] font-bold leading-tight text-[var(--color-ink-900)] transition-colors group-hover:text-[var(--color-primary)]">
                                        {{ $listing->name }}
                                    </h3>

                                    <div class="mt-3 flex items-center gap-2 text-[14px] text-[var(--color-ink-500)]">
                                        <i class="fa-solid fa-location-dot text-[var(--color-primary)]"></i>
                                        <span class="truncate">{{ $listing->city }}, {{ strtoupper((string) $listing->state) }}</span>
                                        @if ($areaLabel !== '' && $listing->area_size)
                                            <span class="h-1 w-1 shrink-0 rounded-full bg-[var(--color-ink-300)]"></span>
                                            <span class="shrink-0"><i class="fa-solid fa-ruler-combined mr-1 text-[12px] text-[var(--color-ink-400)]"></i>{{ $areaLabel }} {{ __('sq ft') }}</span>
                                        @endif
                                    </div>

                                    @if (count($services) > 0)
                                        <div class="mt-4 flex flex-wrap gap-1.5">
                                            @foreach (array_slice($services, 0, 3) as $serviceTag)
                                                <span class="rounded-full bg-[var(--color-brand-50)] px-2.5 py-1 text-[11px] font-semibold text-[var(--color-primary)]">
                                                    {{ ucwords(str_replace(['_', '-'], ' ', (string) $serviceTag)) }}
                                                </span>
                                            @endforeach
                                            @if (count($services) > 3)
                                                <span class="rounded-full bg-[var(--color-ink-100)] px-2.5 py-1 text-[11px] font-semibold text-[var(--color-ink-500)]">
                                                    +{{ count($services) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if (filled($listing->description))
                                        <p class="mt-4 line-clamp-2 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                                            {{ \Illuminate\Support\Str::words((string) $listing->description, 22, '…') }}
                                        </p>
                                    @endif

                                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-[var(--color-brand-100)] pt-5">
                                        <span class="text-[13px] font-semibold text-[var(--color-ink-700)]">
                                            <i class="fa-solid fa-circle-check mr-1 text-[var(--color-primary)]"></i>
                                            {{ __('Verified host') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-50)] px-3 py-1.5 text-[13px] font-semibold text-[var(--color-primary)] transition-all group-hover:bg-[var(--color-primary)] group-hover:text-white">
                                            {{ __('View details') }}
                                            <i class="fa-solid fa-arrow-right text-[11px] transition-transform group-hover:translate-x-0.5"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if ($listings->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $listings->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="mx-auto max-w-xl rounded-[28px] border border-[var(--color-brand-100)] bg-white p-10 text-center shadow-[var(--shadow-sm)]"
                     data-aos="fade-up">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[var(--color-brand-50)]">
                        <i class="fa-solid fa-map-location-dot text-[26px] text-[var(--color-primary)]"></i>
                    </div>
                    <h3 class="text-[22px] font-bold text-[var(--color-ink-900)]">
                        {{ __('No hosts in your area yet') }}
                    </h3>
                    <p class="mt-3 text-[15px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __("We couldn't find any gyms matching these filters. Try clearing filters, picking a different state — or be the first to host in your area.") }}
                    </p>
                    <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('find-a-gym') }}" class="btn btn-outline">
                            <i class="fa-solid fa-rotate-left text-[12px]"></i>
                            {{ __('Browse all states') }}
                        </a>
                        <a href="{{ route('become-a-host') }}" class="btn btn-primary">
                            {{ __('Become a Host') }}
                            <i class="fa-solid fa-arrow-right text-[13px]"></i>
                        </a>
                    </div>
                </div>
            @endif
        </section>

    </main>
@endsection
