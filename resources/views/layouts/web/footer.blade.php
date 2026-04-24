@php
    /**
     * Footer — Spotmee
     * Social links are driven by Application Settings (admin → Settings).
     * Column contents live in the $footerColumns array below so they can be
     * moved to a dedicated admin screen later without touching the markup.
     */
    $settings      = $settings ?? \App\Models\ApplicationSetting::instance();
    $footerLogoUrl = $settings->displayFooterLogoUrl();
    $socialLinks   = collect($settings->footerSocialLinksForPublic())->keyBy('platform');

    $socialIconMap = [
        'instagram' => 'fa-instagram',
        'facebook'  => 'fa-facebook-f',
        'snapchat'  => 'fa-snapchat',
        'linkedin'  => 'fa-linkedin-in',
        'tiktok'    => 'fa-tiktok',
        'twitter'   => 'fa-x-twitter',
        'youtube'   => 'fa-youtube',
    ];

    $footerColumns = [
        [
            'heading' => __('Explore'),
            'links'   => [
                ['label' => __('Find a Gym'),     'href' => route('find-a-gym')],
                ['label' => __('Become a Host'),  'href' => route('become-a-host')],
                ['label' => __('How It Works'),   'href' => route('how-it-works')],
                ['label' => __('About Us'),       'href' => route('about')],
                ['label' => __('FAQ'),            'href' => route('faq')],
            ],
        ],
        [
            'heading' => __('Support'),
            'links'   => [
                ['label' => __('Contact Us'), 'href' => route('contact')],
                ['label' => __('FAQs'),       'href' => route('faq')],
            ],
        ],
        [
            'heading' => __('Legal'),
            'links'   => [
                ['label' => __('Waiver of Liability (Host)'), 'href' => route('legal.waiver-host')],
                ['label' => __('Waiver of Liability (User)'), 'href' => route('legal.waiver-user')],
                ['label' => __('Cancellation Policy'),        'href' => route('legal.cancellation-policy')],
            ],
        ],
    ];
@endphp

<footer class="site-footer" role="contentinfo" data-aos="fade-up" data-aos-duration="700">
    <div class="site-container py-6 sm:py-10">
        <div class="site-footer__card">

            <div class="grid grid-cols-1 gap-10 px-6 py-12 md:grid-cols-12 md:gap-8 md:px-10 md:py-14 lg:px-14">

                {{-- Brand --}}
                <div class="md:col-span-4">
                    <a href="{{ route('home') }}" class="inline-block" aria-label="{{ config('app.name') }}">
                        <img src="{{ $footerLogoUrl }}"
                             alt="{{ config('app.name') }}"
                             class="site-footer-logo">
                    </a>
                    <p class="footer-brand-tagline">
                        {{ __('Connecting you to private, high-quality home gyms anytime, anywhere.') }}
                    </p>

                    {{-- Socials --}}
                    @if ($socialLinks->isNotEmpty())
                        <div class="mt-7">
                            <p class="footer-heading">{{ __('Follow us') }}</p>
                            <div class="mt-4 flex flex-wrap gap-2.5">
                                @foreach ($socialLinks as $platform => $link)
                                    <a href="{{ $link['href'] }}"
                                       class="footer-social"
                                       target="_blank" rel="noopener noreferrer"
                                       aria-label="{{ ucfirst($platform) }}">
                                        <i class="fa-brands {{ $socialIconMap[$platform] ?? 'fa-globe' }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Columns --}}
                <div class="md:col-span-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                        @foreach ($footerColumns as $column)
                            <div>
                                <h3 class="footer-heading">{{ $column['heading'] }}</h3>
                                <ul class="mt-5 space-y-3">
                                    @foreach ($column['links'] as $item)
                                        <li>
                                            <a href="{{ $item['href'] }}" class="footer-link">{{ $item['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="footer-bar">
                <p>&copy; {{ now()->year }} SPOTMEE. {{ __('All rights reserved.') }}</p>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <a href="{{ route('contact') }}"
                       class="footer-bar-link"
                       title="{{ __('Contact us about cookies, privacy, and your data') }}">{{ __('Cookies') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
