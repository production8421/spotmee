@php
    /**
     * Shared legal-page hero (solid colored band).
     *
     * Variables:
     *  - $heroPrefix : 'waiver_liability_host' | 'waiver_liability_user' | 'cancellation_policy'
     *  - $heroEyebrow (optional)
     *  - $heroSubtitle (optional)
     *  - $lastUpdated (optional, e.g. 'April 23, 2026')
     */
    $legalSettings = $settings ?? \App\Models\ApplicationSetting::instance();
    $heroTitle     = $legalSettings->frontendPageHeroTitleDisplay($heroPrefix);
    $heroBg        = $legalSettings->frontendPageHeroBackgroundColorCss($heroPrefix);

    // If admin left the default blue, fall back to the SPOTMEE brand color.
    if (strtolower($heroBg) === '#2563eb') {
        $heroBg = '#006d77';
    }

    $heroEyebrow  = $heroEyebrow ?? __('Legal');
    $heroSubtitle = $heroSubtitle ?? '';
    $lastUpdated  = $lastUpdated ?? null;
@endphp

<section class="site-container pt-6 sm:pt-10">
    <div class="relative overflow-hidden rounded-[28px] px-6 py-14 text-center text-white shadow-[var(--shadow-lg)] sm:px-12 sm:py-20"
         style="background: linear-gradient(135deg, {{ $heroBg }} 0%, {{ $heroBg }} 60%, color-mix(in srgb, {{ $heroBg }} 75%, #ffffff) 100%);">
        {{-- Decorative orbs --}}
        <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-white/5"></div>

        <div class="relative">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-[12px] font-semibold uppercase tracking-[0.14em] backdrop-blur"
                  data-aos="fade-down">
                <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                {{ $heroEyebrow }}
            </span>

            <h1 class="mt-5 font-bold leading-[1.1]"
                style="font-size: var(--text-h1);"
                data-aos="fade-down" data-aos-delay="100">
                {{ $heroTitle }}
            </h1>

            @if ($heroSubtitle !== '')
                <p class="mx-auto mt-5 max-w-2xl text-[15px] leading-relaxed text-white/90 sm:text-[17px]"
                   data-aos="fade-up" data-aos-delay="200">
                    {{ $heroSubtitle }}
                </p>
            @endif

            @if ($lastUpdated)
                <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-[12px] font-medium tracking-wide"
                   data-aos="fade-up" data-aos-delay="300">
                    <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
                    {{ __('Last updated') }}: {{ $lastUpdated }}
                </p>
            @endif
        </div>
    </div>
</section>
