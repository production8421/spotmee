<footer class="w-full py-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
    <div class="mx-auto px-4">
        <div class="bg-[#F5F5F5] rounded-[20px] overflow-hidden shadow-sm">
            <div class="px-4 py-8 ">
                <div class="flex flex-col lg:flex-row justify-between gap-12 lg:gap-24">
                    <!-- Brand Section -->
                    <div class="max-w-sm">
                        <a href="{{ route('home') }}" class="block mb-8">

                            <img src="{{ asset('images/header-logo.png') }}" alt="Spotmee Find Your Space"
                                class="h-32 w-auto object-contain">
                        </a>
                        <p class="text-[#333333] text-[20px] font-bold leading-[22px] max-w-[280px]">
                            Connecting you to private, high-quality home gyms anytime, anywhere.
                        </p>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-12">
                        <!-- Explore -->
                        <div class="space-y-6">
                            <h3 class="footer-h3">Explore</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ route('find-a-gym') }}" class="footer-link">Find a Gym</a></li>
                                <li><a href="{{ route('become-a-host') }}" class="footer-link">Become a Host</a></li>
                                <li><a href="{{ route('how-it-works') }}" class="footer-link">How It Works</a></li>
                                <li><a href="#" class="footer-link">Community Hub</a></li>
                                <li><a href="{{ route('blog') }}" class="footer-link">Blog</a></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div class="space-y-6">
                            <h3 class="footer-h3">Support</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="footer-link">Help Center</a></li>
                                <li><a href="{{ route('contact') }}" class="footer-link">Contact Us</a></li>
                                <li><a href="#" class="footer-link">Safety Guidelines</a></li>
                                <li><a href="#" class="footer-link">FAQs</a></li>
                                <li><a href="#" class="footer-link">Hosting Tips</a></li>
                            </ul>
                        </div>

                        <!-- Legal -->
                        <div class="space-y-6">
                            <h3 class="footer-h3">Legal</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="footer-link">Terms & Conditions</a></li>
                                <li><a href="#" class="footer-link">Privacy Policy</a></li>
                                <li><a href="#" class="footer-link">Refund Policy</a></li>
                                <li><a href="#" class="footer-link">Host Agreement</a></li>
                            </ul>
                        </div>

                        <!-- Socials -->
                        @php
                            $settings = $settings ?? \App\Models\ApplicationSetting::instance();
                            $socialLinks = collect($settings->footerSocialLinksForPublic())->keyBy('platform');
                        @endphp
                        <div class="space-y-6">
                            <h3 class="footer-h3">Socials</h3>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 md:flex md:items-center md:gap-4">
                                @if ($socialLinks->has('instagram'))
                                    <a href="{{ $socialLinks['instagram']['href'] }}" class="social-icon" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                @endif
                                @if ($socialLinks->has('facebook'))
                                    <a href="{{ $socialLinks['facebook']['href'] }}" class="social-icon" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if ($socialLinks->has('snapchat'))
                                    <a href="{{ $socialLinks['snapchat']['href'] }}" class="social-icon" aria-label="Snapchat" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-snapchat"></i>
                                    </a>
                                @endif
                                @if ($socialLinks->has('linkedin'))
                                    <a href="{{ $socialLinks['linkedin']['href'] }}" class="social-icon" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                @endif
                                @if ($socialLinks->has('tiktok'))
                                    <a href="{{ $socialLinks['tiktok']['href'] }}" class="social-icon" aria-label="TikTok" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Copyright Bar -->
            <div class="bg-[#4682B4] py-4 w-full mb-5">
                <p class="text-center text-[#FFFFFF] text-[20px] font-regular">
                    © 2026 SPOTMEE. All rights reserved.
                </p>
            </div>
        </div>

    </div>
</footer>
