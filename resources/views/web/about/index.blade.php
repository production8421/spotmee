@extends('layouts.web.master')
@section('title', 'About Us - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Inner Banner -->
    <div class="px-5">
        <section class="relative w-full py-20 bg-cover bg-center rounded-[15px] flex items-center justify-center overflow-hidden" 
                 style="background-image: url('{{ asset('images/banner-img.png') }}'); min-height: 400px;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="inner-heading" data-aos="fade-down">
                    Our <span class="text-(--primary-color)">Story</span>
                </h1>
                <p class="text-white text-[20px] md:text-[24px] max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="200">
                    Revolutionizing the way people workout by making private gyms accessible to everyone.
                </p>
            </div>
        </section>
    </div>

    <!-- Mission & Vision -->
    <section class="container mx-auto px-4 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <h2 class="heading mb-8">Redefining the <br> <span class="text-(--primary-color)">Fitness Experience</span></h2>
                <p class="md-para !mb-6">
                    At SPOTMEE, we believe that everyone deserves a focused, private, and high-quality workout environment. Our journey began with a simple observation: public gyms are often overcrowded, intimidating, and require long-term commitments that don't fit everyone's lifestyle.
                </p>
                <p class="md-para">
                    We've created a community-driven marketplace that connects fitness enthusiasts with unique private home gyms. Whether you're a trainer looking for a professional space for your clients or someone who simply prefers training in peace, SPOTMEE is your gateway to the perfect workout.
                </p>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-3xl font-bold text-(--primary-color) mb-1">500+</h4>
                        <p class="text-gray-500 font-medium">Active Hosts</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-bold text-(--primary-color) mb-1">10k+</h4>
                        <p class="text-gray-500 font-medium">Happy Users</p>
                    </div>
                </div>
            </div>
            <div class="relative" data-aos="fade-left">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-(--primary-color)/10 rounded-full blur-3xl"></div>
                <img src="{{ asset('images/work-img-3.png') }}" alt="Our Gym Community" class="relative z-10 w-full rounded-[40px] shadow-2xl">
                <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-[24px] shadow-xl z-20 hidden md:block">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-bold text-(--text-color)">Verified Quality</p>
                            <p class="text-xs text-gray-400">Premium Standards</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="bg-[#F8FAFC] py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="heading">The Values That <br> <span class="text-(--primary-color)">Drive Us</span></h2>
                <p class="md-para max-w-2xl mx-auto">Built on trust, accessibility, and a passion for fitness.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="value-card group" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-icon">
                        <i class="fas fa-heart text-2xl text-(--primary-color) group-hover:text-white"></i>
                    </div>
                    <h3 class="vission-title">Passion</h3>
                    <p class="text-gray-500 leading-relaxed">We are passionate about helping people achieve their fitness goals in an environment that empowers them.</p>
                </div>

                <!-- Value 2 -->
                <div class="value-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt text-2xl text-(--primary-color) group-hover:text-white"></i>
                    </div>
                    <h3 class="vission-title">Trust & Safety</h3>
                    <p class="text-gray-500 leading-relaxed">Security is our priority. From verified hosts to secure payments, we ensure a safe experience for everyone.</p>
                </div>

                <!-- Value 3 -->
                <div class="value-card group" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-icon">
                        <i class="fas fa-users text-2xl text-(--primary-color) group-hover:text-white"></i>
                    </div>
                    <h3 class="vission-title">Community</h3>
                    <p class="text-gray-500 leading-relaxed">We're building a network of hosts and users who support each other in their fitness journeys.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet the Vision -->
    <section class="container mx-auto px-4 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div class="order-2 lg:order-1" data-aos="fade-right">
                <div class="relative">
                    <img src="{{ asset('images/community-img-001.png') }}" alt="Founder" class="w-full rounded-[40px] shadow-2xl">
                    <div class="absolute inset-0 bg-linear-to-t from-(--text-color)/60 to-transparent rounded-[40px]"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <h4 class="text-2xl font-bold">John Doe</h4>
                        <p class="text-white/80">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2" data-aos="fade-left">
                <h2 class="heading mb-8">A Note From <br> <span class="text-(--primary-color)">Our Founder</span></h2>
                <p class="text-xl italic text-gray-500 mb-8 leading-relaxed">
                    "SPOTMEE was born out of a personal frustration with the traditional gym model. I wanted a space where I could focus, use the equipment I needed, and not feel the pressure of a crowded environment. By opening up private gyms to the community, we're not just providing a place to workout—we're creating opportunities for people to own their fitness journey."
                </p>
               
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <div class="px-5 mb-24">
        <section class="relative w-full py-20 bg-(--primary-color) rounded-[40px] overflow-hidden text-center">
            <div class="relative z-10 px-4">
                <h2 class="text-white text-[35px] md:text-[55px] font-extrabold mb-8 leading-tight" data-aos="fade-up">
                    Ready to join the <br> SPOTMEE community?
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="zoom-in" data-aos-delay="200">
                    <a href="{{ route('how-it-works') }}" class="px-12 py-4 bg-white text-(--primary-color) font-bold rounded-full hover:bg-gray-100 transition-all shadow-xl">Find a Gym</a>
                    <a href="{{ route('become-a-host') }}" class="px-12 py-4 bg-(--text-color) text-white font-bold rounded-full hover:bg-black transition-all shadow-xl">Become a Host</a>
                </div>
            </div>
        </section>
    </div>
</main>

@endsection

@push('scripts')
<script>
    // AOS is already initialized
</script>
@endpush