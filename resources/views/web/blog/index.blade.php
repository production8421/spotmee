@extends('layouts.web.master')
@section('title', 'Blog - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Inner Banner -->
    <div class="px-5">
        <section class="relative w-full py-20 bg-cover bg-center rounded-[15px] flex items-center justify-center overflow-hidden" 
                 style="background-image: url('{{ asset('images/perfect.png') }}'); min-height: 400px;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="text-white text-[45px] md:text-[65px] font-bold mb-4 leading-tight" data-aos="fade-down">
                    SPOTMEE <span class="text-(--primary-color)">Blog</span>
                </h1>
                <p class="text-white text-[20px] md:text-[24px] max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="200">
                    Tips, stories, and insights from the private fitness community.
                </p>
            </div>
        </section>
    </div>

    <!-- Featured Post -->
    <section class="container mx-auto px-4 py-20">
        <div class="bg-white rounded-[40px] overflow-hidden shadow-xl border border-gray-50 flex flex-col lg:flex-row" data-aos="fade-up">
            <div class="w-full lg:w-1/2 h-[400px] lg:h-auto">
                <img src="{{ asset('images/community-img-001.png') }}" class="w-full h-full object-cover" alt="Featured Post">
            </div>
            <div class="w-full lg:w-1/2 p-10 md:p-16 flex flex-col justify-center">
                <div class="flex items-center gap-4 mb-6">
                    <span class="px-4 py-1.5 bg-(--primary-color)/10 text-(--primary-color) text-xs font-bold rounded-full uppercase tracking-wider">Featured</span>
                    <span class="text-gray-400 text-sm">January 14, 2026</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-(--text-color) hover:text-(--primary-color) transition-colors cursor-pointer leading-tight">
                    How to Turn Your Spare Garage into a Profitable Private Gym
                </h2>
                <p class="text-gray-500 text-lg mb-8 leading-relaxed">
                    Ever wondered if your home workout space could be earning you money? We break down the essentials of becoming a top-rated host on SPOTMEE.
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full"></div>
                    <div>
                        <p class="font-bold text-(--text-color)">By Admin</p>
                        <p class="text-xs text-gray-400">SPOTMEE Hosting Expert</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="container mx-auto px-4 py-10 mb-20">
        <div class="flex items-center justify-between mb-12">
            <h3 class="text-3xl font-bold text-(--text-color)">Latest <span class="text-(--primary-color)">Articles</span></h3>
            <div class="flex gap-4">
                <button class="p-3 border border-gray-100 rounded-full text-gray-400 hover:text-(--primary-color) hover:border-(--primary-color) transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <!-- Post 1 -->
            <div class="group" data-aos="fade-up" data-aos-delay="100">
                <div class="relative h-[280px] rounded-[30px] overflow-hidden mb-6">
                    <img src="{{ asset('images/community-img-002.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Blog Post">
                    <div class="absolute bottom-4 left-4">
                        <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-(--text-color) text-xs font-bold rounded-full uppercase">Training</span>
                    </div>
                </div>
                <div class="px-2">
                    <p class="text-gray-400 text-sm mb-3">January 10, 2026</p>
                    <h4 class="text-2xl font-bold mb-4 text-(--text-color) group-hover:text-(--primary-color) transition-colors cursor-pointer leading-tight">
                        The Psychology of Training Alone: Why Privacy Boosts Focus
                    </h4>
                    <p class="text-gray-500 leading-relaxed line-clamp-2">
                        Discover the mental benefits of a distraction-free workout environment and how it impacts your performance.
                    </p>
                </div>
            </div>

            <!-- Post 2 -->
            <div class="group" data-aos="fade-up" data-aos-delay="200">
                <div class="relative h-[280px] rounded-[30px] overflow-hidden mb-6">
                    <img src="{{ asset('images/community-img-003.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Blog Post">
                    <div class="absolute bottom-4 left-4">
                        <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-(--text-color) text-xs font-bold rounded-full uppercase">Lifestyle</span>
                    </div>
                </div>
                <div class="px-2">
                    <p class="text-gray-400 text-sm mb-3">January 08, 2026</p>
                    <h4 class="text-2xl font-bold mb-4 text-(--text-color) group-hover:text-(--primary-color) transition-colors cursor-pointer leading-tight">
                        Top 5 Equipments Every Private Gym Seekers Look For
                    </h4>
                    <p class="text-gray-500 leading-relaxed line-clamp-2">
                        If you're a host, make sure you have these 5 essential pieces of equipment to attract more bookings.
                    </p>
                </div>
            </div>

            <!-- Post 3 -->
            <div class="group" data-aos="fade-up" data-aos-delay="300">
                <div class="relative h-[280px] rounded-[30px] overflow-hidden mb-6">
                    <img src="{{ asset('images/community-img-004.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Blog Post">
                    <div class="absolute bottom-4 left-4">
                        <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-(--text-color) text-xs font-bold rounded-full uppercase">Community</span>
                    </div>
                </div>
                <div class="px-2">
                    <p class="text-gray-400 text-sm mb-3">January 05, 2026</p>
                    <h4 class="text-2xl font-bold mb-4 text-(--text-color) group-hover:text-(--primary-color) transition-colors cursor-pointer leading-tight">
                        Staying Consistent on a Busy Schedule with Short Gym Rents
                    </h4>
                    <p class="text-gray-500 leading-relaxed line-clamp-2">
                        How our hourly booking model helps professionals maintain their fitness goals between meetings.
                    </p>
                </div>
            </div>
        </div>

        <!-- Load More -->
        <div class="mt-16 text-center">
            <button class="px-10 py-4 border-2 border-gray-100 rounded-full font-bold text-(--text-color) hover:bg-(--primary-color) hover:text-white hover:border-(--primary-color) transition-all">
                Load More Articles
            </button>
        </div>
    </section>

    <!-- Newsletters -->
    <div class="px-5 mb-20">
        <section class="relative w-full py-16 bg-[#F8FAFC] rounded-[40px] overflow-hidden border border-gray-100">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="heading mb-6">Never Miss a <span class="text-(--primary-color)">Story</span></h2>
                <p class="md-para mb-10 text-gray-500">Subscribe to our newsletter and get fitness tips and special hosting offers delivered to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                    <input type="email" placeholder="Enter your email" class="flex-1 px-6 py-4 rounded-full bg-white border border-gray-100 outline-none focus:ring-2 focus:ring-(--primary-color) text-lg shadow-sm">
                    <button type="submit" class="cta-btn py-4! whitespace-nowrap">Subscribe Now</button>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection

@push('scripts')
<script>
    // Blog specific scripts
</script>
@endpush
