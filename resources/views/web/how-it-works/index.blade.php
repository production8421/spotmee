@extends('layouts.web.master')
@section('title', 'How It Works - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Inner Banner -->
    <div class="px-5">
        <section class="how-it-works-banner" 
                 style="background-image: url('{{ asset('images/banner-img.png') }}'); min-height: 400px;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="inner-heading" data-aos="fade-down">
                    How <span class="text-(--primary-color)">SPOTMEE</span> Works
                </h1>
                <p class="text-[#ffffff] text-[20px] md:text-[24px] max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="200">
                    Your guide to finding the perfect private workout space or sharing your own gym with the community.
                </p>
            </div>
        </section>
    </div>

    <!-- For Trainers/Gym Seekers Section -->
    <section class="container mx-auto px-4 py-20 lg:py-32">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="heading">For <span class="text-(--primary-color)">Gym Seekers</span></h2>
            <p class="md-para max-w-2xl mx-auto">Skip the crowds and commitments. Here's how you can start your private fitness journey.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Step 1 -->
            <div class="seeker-card group" data-aos="fade-right" data-aos-delay="100">
                <div class="seeker-icon">
                    <i class="fas fa-search text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <div class="step mb-4">Step 01</div>
                <h3 class="md-title mb-4">Discover Spaces</h3>
                <p class="md-para-slider px-4">Enter your location and browse through a variety of unique, local private gyms. Filter by equipment and price.</p>
            </div>

            <!-- Step 2 -->
            <div class="seeker-card group" data-aos="fade-up" data-aos-delay="200">
                <div class="seeker-icon">
                    <i class="fas fa-calendar-alt text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <div class="step mb-4">Step 02</div>
                <h3 class="md-title mb-4">Book Your Session</h3>
                <p class="md-para-slider px-4">Choose a date and time that fits your schedule. Pay securely and receive instant confirmation with access details.</p>
            </div>

            <!-- Step 3 -->
            <div class="seeker-card group" data-aos="fade-left" data-aos-delay="300">
                <div class="seeker-icon">
                    <i class="fas fa-dumbbell text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <div class="step mb-4">Step 03</div>
                <h3 class="md-title mb-4">Work Out In Peace</h3>
                <p class="md-para-slider px-4">Arrive at your private gym and enjoy a focused workout without any interruptions. No memberships required.</p>
            </div>
        </div>
    </section>

    <!-- Why Host Section (Alternating Layout) -->
    <div class="bg-[#F9FAFB] py-20">
        <section class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1" data-aos="fade-right">
                    <img src="{{ asset('images/work-img-1.png') }}" alt="Host Your Gym" class="w-full rounded-[30px] shadow-2xl">
                </div>
                <div class="order-1 lg:order-2" data-aos="fade-left">
                    <h2 class="heading mb-6">Become a <span class="text-(--primary-color)">Host</span></h2>
                    <p class="md-para mb-8">Turn your home gym into a source of income. Join a community of fitness enthusiasts and help others reach their goals.</p>
                    
                    <ul class="space-y-6 mb-10">
                        <li class="flex gap-4">
                            <div class="list-div mt-1"><i class="fas fa-check"></i></div>
                            <div>
                                <h4 class="host-title">Full Control</h4>
                                <p class="text-gray-600">You decide when your gym is available and set your own hourly rates.</p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div class="list-div mt-1"><i class="fas fa-check"></i></div>
                            <div>
                                <h4 class="host-title">Safe & Secure</h4>
                                <p class="text-gray-600">All guests are verified. You are protected by our community guidelines and insurance support.</p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div class="list-div mt-1"><i class="fas fa-check"></i></div>
                            <div>
                                <h4 class="host-title">Easy Payments</h4>
                                <p class="text-gray-600">Receive automatic payouts directly to your bank account twice a month.</p>
                            </div>
                        </li>
                    </ul>

                    <a href="#" class="cta-btn">Start Hosting Now</a>
                </div>
            </div>
        </section>
    </div>

    <!-- Features Grid -->
    <section class="container mx-auto px-4 py-20">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="heading">Everything You Need To <span class="text-(--primary-color)">Succeed</span></h2>
            <p class="md-para max-w-2xl mx-auto">We've built the tools to make private gym rental seamless for everyone.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt text-2xl text-(--primary-color)"></i>
                </div>
                <h3 class="feature-title">Secure Access</h3>
                <p class="text-gray-600">Digital key integration and smart access for seamless entry.</p>
            </div>
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-star text-2xl text-(--primary-color)"></i>
                </div>
                <h3 class="feature-title">Review System</h3>
                <p class="text-gray-600">Honest feedback from our community ensures high quality standards.</p>
            </div>
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="fas fa-comments text-2xl text-(--primary-color)"></i>
                </div>
                <h3 class="feature-title">In-App Messaging</h3>
                <p class="text-gray-600">Communicate directly with hosts or guests through our secure portal.</p>
            </div>
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt text-2xl text-(--primary-color)"></i>
                </div>
                <h3 class="feature-title">App Support</h3>
                <p class="text-gray-600">Manage your bookings and gym listings on the go with our mobile app.</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <div class="px-5 mb-20">
        <section class="cta-section" 
                 style="background-image: url('{{ asset('images/perfect.png') }}');">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative z-10 text-center px-4">
                <h2 class="text-white text-[30px] md:text-[40px] font-bold mb-8 leading-tight" data-aos="fade-up">
                    Ready to Start Your <br> Private Workout Journey?
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="zoom-in" data-aos-delay="200">
                    <a href="#" class="cta-btn">Find a Gym</a>
                    <a href="#" class="px-8 py-3 bg-white text-(--text-color) font-normal text-[20px] rounded-full hover:bg-gray-100 transition-all">List Your Space</a>
                </div>
            </div>
        </section>
    </div>
</main>

@endsection

@push('scripts')
<script>
    // AOS is already initialized in master.blade.php
</script>
@endpush
