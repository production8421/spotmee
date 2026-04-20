@extends('layouts.web.master')
@section('title', 'Contact Us - SPOTMEE')
@section('content')

<main class="spotmee-main">
    <!-- Inner Banner -->
    <div class="px-5">
        <section class="relative w-full py-20 bg-cover bg-center rounded-[15px] flex items-center justify-center overflow-hidden" 
                 style="background-image: url('{{ asset('images/banner-img.png') }}'); min-height: 400px;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="inner-heading" data-aos="fade-down">
                    Get In <span class="text-(--primary-color)">Touch</span>
                </h1>
                <p class="text-white text-[20px] md:text-[24px] max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="200">
                    We're here to help you get the most out of your SPOTMEE experience.
                </p>
            </div>
        </section>
    </div>

    <!-- Contact Content -->
    <section class="container mx-auto px-4 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">
            <!-- Left Side - Info -->
            <div data-aos="fade-right">
                <h2 class="heading mb-8">Let's <span class="text-(--primary-color)">Talk</span></h2>
                <p class="md-para mb-12 text-gray-600 leading-relaxed">
                    Have questions about finding a gym, hosting your space, or just want to say hello? Our team is ready to assist you.
                </p>

                <div class="space-y-8">
                    <!-- Email -->
                    <div class="flex items-start gap-6 group">
                        <div class="contact-icon">
                            <i class="fas fa-envelope text-2xl text-(--primary-color) group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h4 class="email-title">Email Us</h4>
                            <p class="md-para mb-1">General Inquiries</p>
                            <a href="mailto:support@spotmee.com" class="text-lg font-bold text-(--primary-color) hover:underline">support@spotmee.com</a>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-6 group">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt text-2xl text-(--primary-color) group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h4 class="email-title">Call Us</h4>
                            <p class="md-para mb-1">Mon-Fri from 8am to 5pm</p>
                            <a href="tel:+15551234567" class="text-lg font-bold text-(--primary-color) hover:underline">+1 (555) 123-4567</a>
                        </div>
                    </div>

                    <!-- Office -->
                    <div class="flex items-start gap-6 group">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt text-2xl text-(--primary-color) group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h4 class="email-title">Visit Us</h4>
                            <p class="md-para leading-relaxed max-w-xs">
                                123 Fitness Blvd, Suite 100<br>Austin, TX 78701
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Socials -->
                <div class="mt-12">
                    <h5 class="font-bold text-(--text-color) mb-6">Follow Us</h5>
                    <div class="flex gap-4">
                        <a href="#" class="contact-social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="contact-social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="contact-social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="contact-social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div data-aos="fade-left">
                <div class="bg-white rounded-[40px] p-10 shadow-2xl border border-gray-50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-(--primary-color)/5 rounded-bl-[100px] -mr-10 -mt-10 pointer-events-none"></div>
                    
                    <h3 class="text-2xl font-bold text-(--text-color) mb-8">Send us a Message</h3>
                    
                    <form action="#" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="contact-form-label">First Name</label>
                                <input type="text" placeholder="John" class="contact-input">
                            </div>
                            <div class="space-y-2">
                                <label class="contact-form-label">Last Name</label>
                                <input type="text" placeholder="Doe" class="contact-input">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="contact-form-label">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="contact-input">
                        </div>

                        <div class="space-y-2">
                            <label class="contact-form-label">Subject</label>
                            <select class="contact-input appearance-none cursor-pointer">
                                <option>General Inquiry</option>
                                <option>Support</option>
                                <option>Billing</option>
                                <option>Partnership</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="contact-form-label">Message</label>
                            <textarea rows="5" placeholder="How can we help you?" class="contact-input resize-none"></textarea>
                        </div>

                        <button class="w-full py-4 bg-(--primary-color) text-[#ffffff] font-bold rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 transition-all">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-[#F8FAFC] py-24 mb-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="heading">Frequently Asked <span class="text-(--primary-color)">Questions</span></h2>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ 1 -->
                <div class="faq-item">
                    <div class="faq-header group">
                        <h4 class="faq-question">How do I become a host?</h4>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-body hidden pt-4 border-t border-gray-100 mt-4">
                        <p class="text-[var(--text-color)] leading-relaxed">
                            It's simple! Just sign up for an account, click on "Become a Host," and follow the guided steps. You'll need to upload photos of your gym, list your equipment, set your availability, and choose your hourly price. Once your listing is verified by our team, you'll be ready to accept bookings!
                        </p>
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-header group">
                        <h4 class="faq-question">Is my payment secure?</h4>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-body hidden pt-4 border-t border-gray-100 mt-4">
                        <p class="text-[var(--text-color)] leading-relaxed">
                            Absolutely. We use Stripe, a globally trusted payment processor, to handle all transactions. Your financial information is never stored on our servers. Funds are held securely until the booking completes, ensuring protection for both hosts and users.
                        </p>
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-header group">
                        <h4 class="faq-question">Can I cancel a booking?</h4>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-body hidden pt-4 border-t border-gray-100 mt-4">
                        <p class="text-[var(--text-color)] leading-relaxed">
                            Yes, you can cancel a booking based on the host's cancellation policy. Most hosts offer free cancellation up to 24 hours before the scheduled session. You can view the specific policy on the gym's listing page and manage your bookings from your dashboard.
                        </p>
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-header group">
                        <h4 class="faq-question">What if I damage equipment?</h4>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-body hidden pt-4 border-t border-gray-100 mt-4">
                        <p class="text-[var(--text-color)] leading-relaxed">
                            Accidents happen. We encourage users to report any damage immediately. Review our Terms of Service for details on liability. We also offer insurance options for hosts to protect their equipment and space during bookings.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.faq-header').forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const body = item.querySelector('.faq-body');
            const icon = header.querySelector('i');

            // Close other open FAQs
            document.querySelectorAll('.faq-item').forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.querySelector('.faq-body').classList.add('hidden');
                    otherItem.querySelector('i').classList.remove('rotate-180');
                }
            });

            // Toggle current FAQ
            body.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
</script>
@endpush
