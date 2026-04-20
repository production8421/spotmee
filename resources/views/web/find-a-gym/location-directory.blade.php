@extends('layouts.web.master')
@section('title', 'Find a Gym - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Header/Search Section -->
    <div class="px-5">
        <section class="bg-[#F8FAFC] rounded-[24px] p-8 md:p-12 relative overflow-hidden" 
                 style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center; min-height: 400px;">
            <div class="absolute inset-0 bg-black/50"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto text-center ">
                <h1 class="inner-heading" data-aos="fade-down">
                    Find Your <span class="text-(--primary-color)">Perfect Space</span>
                </h1>
                
                <!-- Modern Search Bar -->
                <div class="find-a-gym-search" data-aos="fade-up" data-aos-delay="200">
                    <div class="search-input">
                        <i class="fas fa-map-marker-alt search-input-icon"></i>
                        <input type="text" placeholder="Location (e.g., Dallas, TX)" class="search-input-input">
                    </div>
                    <div class="search-input">
                        <i class="fas fa-calendar-alt search-input-icon"></i>
                        <input type="text" id="datetime-picker" placeholder="Select Date & Time" class="search-input-input cursor-pointer">
                    </div>
                    <button class="cta-btn">Search</button>
                </div>
            </div>
        </section>
    </div>

    <!-- Location Directory Section (inspired by WP shortcode layout) -->
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
            ['key' => 'boxing', 'label' => 'Boxing', 'icon_path' => asset('images/rent-your-jim/boxing.png')],
            ['key' => 'yoga', 'label' => 'Yoga', 'icon_path' => asset('images/rent-your-jim/yoga.png')],
            ['key' => 'crossfit', 'label' => 'CrossFit', 'icon_path' => asset('images/rent-your-jim/fitness.png')],
            ['key' => 'personal_training', 'label' => 'Personal Training', 'icon_path' => asset('images/rent-your-jim/personal-training.png')],
            ['key' => 'weightlifting', 'label' => 'Weightlifting', 'icon_path' => asset('images/rent-your-jim/weights_lifting.png')],
            ['key' => 'cardio', 'label' => 'Cardio', 'icon_path' => asset('images/rent-your-jim/cardio.png')],
            ['key' => 'group_classes', 'label' => 'Group Classes', 'icon_path' => asset('images/rent-your-jim/group_class.png')],
        ];
        $stateChunks = array_chunk($stateDirectory, 10, true);
    @endphp
    <section class="container mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[22px] bg-gradient-to-r from-[#4682B4] via-[#36648B] to-[#27496d] p-[2px] shadow-lg" data-aos="fade-up">
            <div class="rounded-[20px] bg-white px-6 py-10 md:px-10 md:py-12">
                <div class="mb-10 text-center">
                    <p class="text-sm text-gray-500">Find your perfect workout space</p>
                    <h2 class="mt-3 text-[36px] font-bold text-[#4682B4] leading-[1.1]">Rent a Gym Near You</h2>
                </div>

                <div class="mb-10">
                    <h3 class="mb-6 text-[38px] font-bold text-[#333333] relative inline-block">
                        Browse by State
                        <span class="absolute left-0 -bottom-2 h-[3px] w-12 rounded bg-[#4682B4]"></span>
                    </h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        @foreach ($stateChunks as $stateChunk)
                            <ul class="space-y-2">
                                @foreach ($stateChunk as $abbr => $stateName)
                                    <li>
                                        <a
                                            href="{{ route('find-a-gym.state', ['state' => $abbr]) }}"
                                            class="inline-flex items-center rounded-md border-l-[3px] border-transparent px-3 py-1 text-[15px] font-medium text-gray-600 transition hover:translate-x-1 hover:border-[#4682B4] hover:bg-[#e8f4fc] hover:text-[#4682B4]"
                                        >
                                            {{ $stateName }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="mb-6 text-[32px] font-bold text-[#333333] relative inline-block">
                        Browse by Service Type
                        <span class="absolute left-0 -bottom-2 h-[3px] w-12 rounded bg-[#4682B4]"></span>
                    </h3>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($serviceDirectory as $serviceItem)
                            <a
                                href="{{ route('find-a-gym', ['service' => $serviceItem['key']]) }}"
                                class="inline-flex items-center gap-2 rounded-md border-l-[3px] border-transparent px-3 py-2 text-[15px] font-medium text-gray-600 transition hover:translate-x-1 hover:border-[#4682B4] hover:bg-[#e8f4fc] hover:text-[#4682B4]"
                            >
                                <img src="{{ $serviceItem['icon_path'] }}" alt="{{ $serviceItem['label'] }}" class="h-[28px] w-[28px] object-contain shrink-0">
                                {{ $serviceItem['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Modern Filters Sidebar -->
            <aside class="w-full lg:w-1/4" data-aos="fade-right">
                <div class="bg-white border border-gray-100 rounded-[30px] p-8 sticky top-24 shadow-lg shadow-gray-100/50">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-(--text-color)">Filters</h3>
                        <button class="text-(--primary-color) text-sm font-bold hover:underline">Reset</button>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-10">
                        <h4 class="sidebar-title">
                            <i class="fas fa-tag text-(--primary-color)"></i> Price Range
                        </h4>
                        <div class="px-2 pt-4">
                            <div id="price-slider" class="mb-5"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-500">Min: <span id="price-min" class="text-(--text-color)">$10</span></span>
                                <span class="text-sm font-bold text-gray-500">Max: <span id="price-max" class="text-(--text-color)">$100</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Gym Type -->
                    <div class="mb-10">
                        <h4 class="sidebar-title">
                            <i class="fas fa-dumbbell text-(--primary-color)"></i> Gym Type
                        </h4>
                        <div class="space-y-3">
                            <label class="gym-type-label group">
                                <input type="checkbox" class="type-checkbox">
                                <span class="type-checkbox-label">Garage Gym</span>
                            </label>
                            <label class="gym-type-label group">
                                <input type="checkbox" class="type-checkbox">
                                <span class="type-checkbox-label">Home Studio</span>
                            </label>
                            <label class="gym-type-label group">
                                <input type="checkbox" class="type-checkbox">
                                <span class="type-checkbox-label">Backyard Setup</span>
                            </label>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-8">
                        <h4 class="sidebar-title">
                            <i class="fas fa-star text-(--primary-color)"></i> Amenities
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="amenities-label group">
                                <input type="checkbox" class="amenities-checkbox">
                                <span class="type-checkbox-label">WiFi</span>
                            </label>
                            <label class="amenities-label group">
                                <input type="checkbox" class="amenities-checkbox">
                                <span class="type-checkbox-label">AC</span>
                            </label>
                            <label class="amenities-label group">
                                <input type="checkbox" class="amenities-checkbox">
                                <span class="type-checkbox-label">Shower</span>
                            </label>
                            <label class="amenities-label group">
                                <input type="checkbox" class="amenities-checkbox">
                                <span class="type-checkbox-label">Parking</span>
                            </label>
                        </div>
                    </div>

                    <button class="apply-filters-btn">
                        <span>Apply Filters</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>
                </div>
            </aside>

            <!-- Gym Listings Grid -->
            <div class="w-full lg:w-3/4">
                <!-- Sort & Count Bar -->
                <div class="sort-bar">
                    <p class="text-gray-600 font-medium"><span class="font-bold text-(--text-color) text-lg">24</span> Gyms found</p>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 font-medium">Sort by:</span>
                        <div class="relative">
                            <select class="appearance-none bg-gray-50 border border-gray-200 text-(--text-color) py-2 pl-4 pr-10 rounded-lg font-bold focus:outline-none focus:border-(--primary-color) cursor-pointer">
                                <option>Recommended</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Top Rated</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @php
                        $gyms = [
                            ['title' => 'The Strength Studio', 'price' => '18', 'location' => 'Dallas, TX', 'rating' => '4.9', 'img' => 'popular-gym-img-001.png', 'type' => 'Private Garage Gym', 'distance' => '1.2 miles away'],
                            ['title' => 'The Cardio Loft', 'price' => '15', 'location' => 'Austin, TX', 'rating' => '4.8', 'img' => 'popular-gym-img-002.png', 'type' => 'Modern Fitness Room', 'distance' => '3.4 miles away'],
                            ['title' => 'Ironclad Elite', 'price' => '22', 'location' => 'Houston, TX', 'rating' => '5.0', 'img' => 'popular-gym-img-003.png', 'type' => 'Basement Training', 'distance' => '2.0 miles away'],
                            ['title' => 'ZenFlex Pilates', 'price' => '20', 'location' => 'Miami, FL', 'rating' => '4.9', 'img' => 'popular-gym-img-004.png', 'type' => 'Pilates Core', 'distance' => '0.9 miles away'],
                            ['title' => 'Titan Fitness', 'price' => '16', 'location' => 'Chicago, IL', 'rating' => '4.7', 'img' => 'popular-gym-img-001.png', 'type' => 'Heavy Lifting', 'distance' => '2.5 miles away'],
                            ['title' => 'Urban Yoga Space', 'price' => '25', 'location' => 'Seattle, WA', 'rating' => '4.9', 'img' => 'popular-gym-img-002.png', 'type' => 'Peaceful Retreat', 'distance' => '1.5 miles away'],
                        ];
                    @endphp

                    @foreach($gyms as $gym)
                    <div class="group bg-white border border-gray-100 rounded-[24px] overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up">
                        <div class="relative h-[250px] overflow-hidden">
                            <img src="{{ asset('images/' . $gym['img']) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $gym['title'] }}">
                            <!-- Floating Badges -->
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-md">
                                <i class="fas fa-star text-yellow-500 text-xs"></i>
                                <span class="text-sm font-bold text-(--text-color)">{{ $gym['rating'] }}</span>
                            </div>
                            <button class="absolute top-4 right-4 w-10 h-10 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-white transition-all shadow-md">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-(--text-color) mb-1 group-hover:text-(--primary-color) transition-colors">{{ $gym['title'] }}</h3>
                                    <p class="text-sm text-gray-500 font-medium bg-gray-50 inline-block px-2 py-1 rounded-md">{{ $gym['type'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black text-(--primary-color)">${{ $gym['price'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 text-gray-500 text-sm mb-6 border-b border-gray-50 pb-4">
                                <i class="fas fa-map-marker-alt text-gray-300"></i>
                                <span>{{ $gym['location'] }}</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $gym['distance'] }}</span>
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="#" class="flex-1 text-center py-3.5 bg-(--text-color) text-white font-bold rounded-xl hover:bg-(--primary-color) transition-all shadow-lg shadow-gray-200">Book Session</a>
                              
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modern Pagination -->
                <div class="mt-16 flex justify-center items-center gap-3">
                    <button class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-200 text-gray-400 hover:border-(--primary-color) hover:text-(--primary-color) transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="w-12 h-12 flex items-center justify-center rounded-2xl bg-(--primary-color) text-white font-bold shadow-lg shadow-blue-500/30 transform scale-110">1</button>
                    <button class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-200 text-gray-600 hover:border-(--primary-color) hover:text-(--primary-color) transition-all">2</button>
                    <button class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-200 text-gray-600 hover:border-(--primary-color) hover:text-(--primary-color) transition-all">3</button>
                    
                    <button class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-200 text-gray-400 hover:border-(--primary-color) hover:text-(--primary-color) transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">
<style>
    /* Custom Price Slider Styles */
    .noUi-target {
        background: #f3f4f6;
        border: none;
        box-shadow: none;
        height: 8px;
        border-radius: 4px;
    }
    .noUi-connect {
        background: var(--primary-color) !important;
    }
    .noUi-handle {
        background: white;
        border: 2px solid var(--primary-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 50%;
        width: 10px;
        height: 10px;
        right: -10px;
        top: 14px;
        cursor: grab;
    }
    .noUi-handle:before, .noUi-handle:after {
        display: none;
    }
    .noUi-handle:active {
        cursor: grabbing;
        transform: scale(1.1);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
<script>
    // Flatpickr Init
    flatpickr("#datetime-picker", {
        enableTime: true,
        dateFormat: "F j, Y - h:i K",
        minDate: "today",
        time_24hr: false,
        disableMobile: "true",
        theme: "light" 
    });

    // Price Slider Init
    var slider = document.getElementById('price-slider');
    var minPriceDisplay = document.getElementById('price-min');
    var maxPriceDisplay = document.getElementById('price-max');

    noUiSlider.create(slider, {
        start: [15, 60],
        connect: true,
        range: {
            'min': 0,
            'max': 100
        },
        step: 1,
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return value;
            }
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        if (handle === 0) {
            minPriceDisplay.innerHTML = '$' + values[0];
        } else {
            maxPriceDisplay.innerHTML = '$' + values[1];
        }
    });
</script>
@endpush
