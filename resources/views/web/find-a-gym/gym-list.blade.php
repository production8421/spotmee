@extends('layouts.web.master')
@section('title', 'Gym Listings - SPOTMEE')
@section('content')
    @php
        $selectedState = strtoupper((string) ($state ?? request('state', '')));
        $selectedService = (string) ($selectedService ?? request('service', ''));
        $searchBy = (string) ($searchBy ?? request('searchby', ''));
        $searchCity = (string) ($searchCity ?? request('city', ''));
        $stateLabel = config('gym_listing.states.'.$selectedState, $selectedState);

        $serviceFilters = [
            ['key' => 'boxing', 'label' => 'Boxing', 'icon_path' => asset('images/rent-your-jim/boxing.png')],
            ['key' => 'yoga', 'label' => 'Yoga', 'icon_path' => asset('images/rent-your-jim/yoga.png')],
            ['key' => 'crossfit', 'label' => 'CrossFit', 'icon_path' => asset('images/rent-your-jim/fitness.png')],
            ['key' => 'personal_training', 'label' => 'Personal Training', 'icon_path' => asset('images/rent-your-jim/personal-training.png')],
            ['key' => 'cardio', 'label' => 'Cardio', 'icon_path' => asset('images/rent-your-jim/cardio.png')],
            ['key' => 'group_classes', 'label' => 'Group Classes', 'icon_path' => asset('images/rent-your-jim/group_class.png')],
        ];
    @endphp

    <main class="spotmee-main py-10">
        <section class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-[1200px] mx-auto rounded-[20px] border border-gray-200 bg-white p-5 shadow-sm md:p-8" data-aos="fade-up">
                <div class="mb-6 text-center">
                    <p class="text-sm text-gray-500">Find your perfect workout space</p>
                    <h1 class="mt-3 text-[40px] font-bold text-[#4682B4] leading-[1.1]">Rent a Gym Near You</h1>
                    <p class="mt-2 text-gray-600">
                        Showing listings in
                        <span class="font-semibold text-[#333]">{{ $stateLabel }}</span>
                    </p>
                </div>

                <div class="mb-10 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
                    @foreach ($serviceFilters as $service)
                        <a
                            href="{{ route('find-a-gym.state', ['state' => $selectedState, 'service' => $service['key']]) }}"
                            class="flex flex-col items-center justify-center gap-2 rounded-lg border px-3 py-4 text-center transition {{ $selectedService === $service['key'] ? 'border-[#4682B4] bg-[#4682B4] text-white shadow-md' : 'border-gray-200 bg-white text-[#333] hover:-translate-y-0.5 hover:border-[#4682B4] hover:bg-[#e8f4fc] hover:text-[#4682B4]' }}"
                        >
                            <img src="{{ $service['icon_path'] }}" alt="{{ $service['label'] }}" class="h-[28px] w-[28px] object-contain shrink-0">
                            <span class="text-[12px] font-semibold leading-tight">{{ $service['label'] }}</span>
                        </a>
                    @endforeach
                    <a
                        href="{{ route('find-a-gym.state', ['state' => $selectedState]) }}"
                        class="flex flex-col items-center justify-center gap-2 rounded-lg border px-3 py-4 text-center transition {{ $selectedService === '' ? 'border-[#4682B4] bg-[#4682B4] text-white shadow-md' : 'border-gray-200 bg-white text-[#333] hover:-translate-y-0.5 hover:border-[#4682B4] hover:bg-[#e8f4fc] hover:text-[#4682B4]' }}"
                    >
                        <img src="{{ asset('images/rent-your-jim/gym.png') }}" alt="All Gyms" class="h-[28px] w-[28px] object-contain shrink-0">
                        <span class="text-[12px] font-semibold leading-tight">All Gyms</span>
                    </a>
                </div>

                @if ($listings->count() > 0)
                    <div class="mb-4 text-sm text-gray-500">
                        <span class="font-semibold text-[#333]">{{ $listings->total() }}</span> gyms found
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($listings as $listing)
                            @php
                                $services = is_array($listing->service_options) ? $listing->service_options : [];
                                $areaLabel = config('gym_listing.area_sizes.'.((string) $listing->area_size), (string) $listing->area_size);
                                $facilityLabel = config('gym_listing.facility_types.'.((string) $listing->facility_type), (string) $listing->facility_type);
                            @endphp
                            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                <a href="{{ route('gym.show', ['slug' => $listing->slug]) }}" class="block">
                                    <div class="relative h-[220px] bg-[#e8f4fc]">
                                        @if ($listing->mainImageUrl())
                                            <img
                                                src="{{ $listing->mainImageUrl() }}"
                                                alt="{{ $listing->name }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                        @else
                                            <div class="flex h-full items-center justify-center text-4xl">🏋️</div>
                                        @endif
                                        @if ($facilityLabel !== '')
                                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-[#4682B4]">
                                                {{ $facilityLabel }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-[#333]">{{ $listing->name }}</h3>
                                        <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt text-[#4682B4]"></i>
                                            <span>{{ $listing->city }}, {{ strtoupper((string) $listing->state) }}</span>
                                        </div>
                                        <div class="mt-1 flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-ruler-combined text-[#4682B4]"></i>
                                            <span>{{ $areaLabel }} sq ft</span>
                                        </div>
                                        @if (count($services) > 0)
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach (array_slice($services, 0, 3) as $serviceTag)
                                                    <span class="rounded-full bg-[#e8f4fc] px-3 py-1 text-xs font-medium text-[#4682B4]">
                                                        {{ ucwords(str_replace(['_', '-'], ' ', (string) $serviceTag)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if (filled($listing->description))
                                            <p class="mt-3 text-sm leading-6 text-gray-600">
                                                {{ \Illuminate\Support\Str::words((string) $listing->description, 20, '...') }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if ($listings->hasPages())
                        <div class="mt-8">
                            {{ $listings->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-8 text-center text-[#555]">
                        <p>
                            NO Hosts in your area. Check back later.
                            <a href="{{ route('host.apply') }}" class="font-semibold text-[#4682B4] underline hover:text-[#2c5282]">
                                Be the first in your area to become a Host!
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
