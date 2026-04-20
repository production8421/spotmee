@extends('layouts.web.master')
@section('title', 'Become a Host - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Hero Section -->
    <div class="px-5">
        <section class="relative w-full py-24 bg-cover bg-center rounded-[30px] overflow-hidden flex items-center" 
                 style="background-image: url('{{ asset('images/earn-money-right-img.png') }}'); min-height: 600px;">
            <div class="absolute inset-0 bg-linear-to-r from-black/80 to-transparent"></div>
            <div class="relative z-10 container mx-auto px-6 md:px-12">
                <div class="max-w-2xl">
                    <h1 class="inner-heading" data-aos="fade-right">
                        Your Gym, <br><span class="text-(--primary-color)">Your Income.</span>
                    </h1>
                    <p class="text-white/90 text-xl md:text-2xl mb-10 font-light leading-relaxed" data-aos="fade-right" data-aos-delay="200">
                        Join thousands of hosts earning extra money by sharing their private workout spaces with fitness enthusiasts in their community.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ route('register') }}" class="cta-btn">Start Hosting Now</a>
                        <a href="#how-it-works-host" class="px-10 py-3 bg-white/10 backdrop-blur-md text-white border border-white/20 rounded-full font-bold hover:bg-white hover:text-(--text-color) transition-all text-center">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Earnings Calculator Concept -->
    <section class="container mx-auto px-4 py-20">
        <div class="bg-white border border-gray-100 rounded-[40px] p-8 md:p-16 shadow-xl -mt-32 relative z-20" data-aos="zoom-in">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="heading mb-6">How much could you <span class="text-(--primary-color)">earn?</span></h2>
                    <p class="md-para mb-8 text-gray-600">Earnings depend on your equipment quality, location, and availability. Most hosts earn enough to upgrade their gym every month!</p>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-(--primary-color)/10 rounded-full flex items-center justify-center text-(--primary-color)">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Top hosts earn $1,500+/mo</h4>
                                <p class="text-sm text-gray-500">Based on 20 hours of booking per week.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-[30px] p-8 text-center border border-dashed border-gray-200">
                    <p class="text-gray-500 font-medium mb-2 uppercase tracking-wider text-sm">Potential Monthly Earnings</p>
                    <h3 class="text-6xl font-black text-(--text-color) mb-6">$850 - $2,100</h3>
                    <p class="text-gray-400 italic text-sm">*ESTIMATED BASED ON SIMILAR GYMS IN YOUR AREA</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Host Section -->
    <section id="how-it-works-host" class="container mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2 class="heading" data-aos="fade-up">Why Host on <span class="text-(--primary-color)">SPOTMEE?</span></h2>
            <p class="md-para max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">We provide the platform, you provide the space. Together we build a healthier community.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Benefit 1 -->
            <div class="group benefit-card" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-icon">
                    <i class="fas fa-user-shield text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Verified Users</h3>
                <p class="text-gray-500 leading-relaxed">Every guest goes through a verification process. You can see ratings and reviews before they ever step into your gym.</p>
            </div>

            <!-- Benefit 2 -->
            <div class="group benefit-card" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-icon">
                    <i class="fas fa-calendar-check text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Ultimate Flexibility</h3>
                <p class="text-gray-500 leading-relaxed">Your gym, your rules. Block out times whenever you need the space for yourself. Set your own pricing and rules.</p>
            </div>

            <!-- Benefit 3 -->
            <div class="group benefit-card" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-icon">
                    <i class="fas fa-wallet text-3xl text-(--primary-color) group-hover:text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Direct Payouts</h3>
                <p class="text-gray-500 leading-relaxed">No chasing payments. We handle the transactions securely and deposit earnings directly to your bank account.</p>
            </div>
        </div>
    </section>

    <!-- How to Start -->
    <section class="bg-[#F8FAFC] py-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <img src="{{ asset('images/work-img-2.png') }}" alt="Set Up Gym" class="w-full rounded-[40px] shadow-2xl">
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <h2 class="heading mb-10">Starting is <span class="text-(--primary-color)">Easy</span></h2>
                    <div class="space-y-10">
                        <div class="flex gap-6">
                            <div class="step-number">1</div>
                            <div>
                                <h4 class="step-title">Create Your Listing</h4>
                                <p class="text-gray-600">Upload photos of your gym, list your equipment, and set your availability calendar.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="step-number">2</div>
                            <div>
                                <h4 class="step-title">Set Your Price</h4>
                                <p class="text-gray-600">Choose an hourly rate that works for you. You can change it at any time.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="step-number">3</div>
                            <div>
                                <h4 class="step-title">Host & Earn</h4>
                                <p class="text-gray-600">Accept bookings, welcome users (or use smart access), and watch your earnings grow.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="container mx-auto px-4 py-24">
        <div class="max-w-4xl mx-auto bg-(--primary-color) rounded-[40px] p-10 md:p-16 text-center text-white relative overflow-hidden" data-aos="fade-up">
            <div class="absolute top-0 right-0 p-10 opacity-10">
                <i class="fas fa-quote-right text-9xl"></i>
            </div>
            <div class="relative z-10">
                <img src="{{ asset('images/popular-gym-img-001.png') }}" class="w-24 h-24 rounded-full border-4 border-white/20 mx-auto mb-8 object-cover" alt="Host Profile">
                <p class="text-2xl md:text-3xl font-light italic mb-8 leading-relaxed">
                    "Hosting on SPOTMEE has been a game-changer. I've met awesome local athletes and my gym actually pays for itself now. The platform makes everything seamless!"
                </p>
                <h4 class="text-xl font-bold">— Mark Thompson, Garage Gym Host</h4>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="px-5 mb-24">
        <section class="relative w-full py-20 bg-(--text-color) rounded-[40px] overflow-hidden text-center">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            <div class="relative z-10 px-4">
                <h2 class="text-white text-[35px] md:text-[55px] font-extrabold mb-8 leading-tight" data-aos="fade-up">
                    Ready to turn your space <br> into a powerhouse?
                </h2>
                <a href="{{ route('register') }}" class="cta-btn" data-aos="zoom-in" data-aos-delay="200">List Your Gym Today</a>
                <p class="text-white/50 mt-8 text-sm uppercase tracking-widest">Join over 500+ Active Hosts Nationwide</p>
            </div>
        </section>
    </div>
</main>

@endsection

@push('scripts')
<script>
    // Smooth scroll for anchor link
    document.querySelector('a[href="#how-it-works-host"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
</script>
@endpush
