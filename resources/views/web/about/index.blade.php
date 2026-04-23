@extends('layouts.web.master')

@section('title', 'About Us — SPOTMEE')

@section('content')
    {{-- =====================================================================
         Hero banner
    ===================================================================== --}}
    <section class="site-container pt-6 sm:pt-10">
        <div class="inner-banner"
             style="background-image: url('{{ asset('images/banner-img.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-br from-[rgba(0,69,77,0.88)] via-[rgba(0,109,119,0.6)] to-[rgba(131,197,190,0.3)]"></div>

            <div class="inner-banner__content">
                <span class="inner-banner__eyebrow" data-aos="fade-down">
                    <i class="fa-solid fa-book-open"></i>
                    {{ __('Our story') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('Private gyms, booked') }}
                    <span class="text-[var(--color-brand-200)]">{{ __('by the hour.') }}</span>
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('SPOTMEE is a community marketplace connecting people who want a private, focused workout space with hosts who love to share theirs — no memberships, no crowds, just your session on your time.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Our story — two paragraphs + stat row + image
    ===================================================================== --}}
    <section class="site-container py-16 sm:py-24">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-16">

            <div data-aos="fade-right">
                <span class="inline-flex items-center gap-2 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)]">
                    <i class="fa-solid fa-compass"></i>
                    {{ __('Where it started') }}
                </span>

                <h2 class="mt-4 text-[32px] font-bold leading-[1.1] tracking-tight text-[var(--color-ink-900)] sm:text-[40px]">
                    {{ __('A better way to') }}
                    <span class="block text-[var(--color-primary)]">{{ __('train on your terms.') }}</span>
                </h2>

                <p class="mt-6 text-[16px] leading-relaxed text-[var(--color-ink-500)]">
                    {{ __('SPOTMEE started with a simple frustration: public gyms are crowded, memberships are long, and finding a quiet, well-equipped space to actually train — when you need it — is harder than it should be. We wanted an Airbnb-style alternative where anyone could book a private gym by the hour, near them, without signing a 12-month contract.') }}
                </p>
                <p class="mt-4 text-[16px] leading-relaxed text-[var(--color-ink-500)]">
                    {{ __('Today, SPOTMEE connects fitness enthusiasts, personal trainers, and athletes with a growing network of home gyms, private studios, and specialty spaces across the country. Hosts earn from their unused hours. Guests get the equipment, privacy, and flexibility they actually need.') }}
                </p>

                {{-- Stat row --}}
                <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
                    <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white px-5 py-4">
                        <h4 class="text-[28px] font-extrabold leading-none text-[var(--color-primary)]">500+</h4>
                        <p class="mt-2 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-ink-500)]">
                            {{ __('Verified hosts') }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white px-5 py-4">
                        <h4 class="text-[28px] font-extrabold leading-none text-[var(--color-primary)]">10k+</h4>
                        <p class="mt-2 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-ink-500)]">
                            {{ __('Sessions booked') }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white px-5 py-4">
                        <h4 class="text-[28px] font-extrabold leading-none text-[var(--color-primary)]">40+</h4>
                        <p class="mt-2 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-ink-500)]">
                            {{ __('US states') }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-[var(--color-brand-100)] bg-white px-5 py-4">
                        <h4 class="text-[28px] font-extrabold leading-none text-[var(--color-primary)]">4.9</h4>
                        <p class="mt-2 text-[12px] font-semibold uppercase tracking-wider text-[var(--color-ink-500)]">
                            {{ __('Avg. rating') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Image --}}
            <div class="relative" data-aos="fade-left">
                <div class="absolute -left-10 -top-10 h-48 w-48 rounded-full bg-[var(--color-brand-200)]/30 blur-3xl"></div>
                <div class="absolute -bottom-12 -right-10 h-56 w-56 rounded-full bg-[var(--color-primary)]/20 blur-3xl"></div>

                <div class="relative overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] bg-white shadow-[var(--shadow-lg)]">
                    <img src="{{ asset('images/work-img-3.png') }}"
                         alt="{{ __('Private gym space on SPOTMEE') }}"
                         class="h-full w-full object-cover">
                </div>

                <div class="absolute -bottom-6 right-6 hidden items-center gap-3 rounded-2xl border border-[var(--color-brand-100)] bg-white px-5 py-4 shadow-[var(--shadow-lg)] sm:flex">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                        <i class="fa-solid fa-shield-halved text-[14px]"></i>
                    </span>
                    <div>
                        <p class="text-[14px] font-bold text-[var(--color-ink-900)]">{{ __('Verified hosts') }}</p>
                        <p class="text-[12px] text-[var(--color-ink-500)]">{{ __('Every gym is reviewed') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         What we do — 3 pillars
    ===================================================================== --}}
    <section class="relative bg-[var(--color-brand-50)] py-20 sm:py-24">
        <div class="site-container">

            <div class="section-head">
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)] ring-1 ring-[var(--color-brand-100)]"
                      data-aos="fade-down">
                    <i class="fa-solid fa-dumbbell"></i>
                    {{ __('What we do') }}
                </span>
                <h2 class="section-head__title" data-aos="fade-up">
                    {{ __('One marketplace, three') }}
                    <span class="text-[var(--color-primary)]">{{ __('better outcomes.') }}</span>
                </h2>
                <p class="section-head__subtitle" data-aos="fade-up" data-aos-delay="100">
                    {{ __('SPOTMEE makes private workout spaces available to anyone who needs them — and profitable for the people who own them.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:gap-7 md:grid-cols-3">

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="100">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-magnifying-glass-dollar text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Find a private gym') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('Search by city, state, or service type, see photos and equipment up front, and book a 40-minute or 1-hour slot instantly. No membership required.') }}
                    </p>
                </div>

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="200">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-house-chimney-user text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Host your space') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('Turn the idle hours in your home gym, private studio, or training facility into income. You set the schedule, prices, and who trains in your space.') }}
                    </p>
                </div>

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="300">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Train with a pro') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('Add a certified personal trainer to any session — or claim your free first trial. Perfect for first timers and anyone pushing for a new PR.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         What makes SPOTMEE different
    ===================================================================== --}}
    <section class="site-container py-20 sm:py-24">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-16">

            <div data-aos="fade-right">
                <span class="inline-flex items-center gap-2 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)]">
                    <i class="fa-solid fa-sparkles"></i>
                    {{ __('Why SPOTMEE') }}
                </span>

                <h2 class="mt-4 text-[32px] font-bold leading-[1.1] tracking-tight text-[var(--color-ink-900)] sm:text-[40px]">
                    {{ __('Built differently, on') }}
                    <span class="block text-[var(--color-primary)]">{{ __('purpose.') }}</span>
                </h2>

                <p class="mt-5 text-[16px] leading-relaxed text-[var(--color-ink-500)]">
                    {{ __('Every feature on SPOTMEE exists because we\'ve felt the pain it solves — whether as a guest who couldn\'t find an open squat rack, or as a host with a gorgeous garage gym sitting empty all day.') }}
                </p>

                <ul class="mt-7 space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </span>
                        <p class="text-[15px] leading-relaxed text-[var(--color-ink-700)]">
                            <span class="font-semibold text-[var(--color-ink-900)]">{{ __('Pay only for what you use.') }}</span>
                            {{ __('Book a single 40-minute slot or a full hour. Cancel anytime before your session starts.') }}
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </span>
                        <p class="text-[15px] leading-relaxed text-[var(--color-ink-700)]">
                            <span class="font-semibold text-[var(--color-ink-900)]">{{ __('Every listing is reviewed.') }}</span>
                            {{ __('Our team approves each host before they go live, so the photos, equipment list, and address you see are the real deal.') }}
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </span>
                        <p class="text-[15px] leading-relaxed text-[var(--color-ink-700)]">
                            <span class="font-semibold text-[var(--color-ink-900)]">{{ __('Secure payments & payouts.') }}</span>
                            {{ __('Guests pay safely with Stripe. Hosts receive direct payouts to their bank — no chasing, no awkward handoffs.') }}
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </span>
                        <p class="text-[15px] leading-relaxed text-[var(--color-ink-700)]">
                            <span class="font-semibold text-[var(--color-ink-900)]">{{ __('Coaching on demand.') }}</span>
                            {{ __('Pair any session with a personal trainer from the host\'s roster, or start with a free first trial to see if you like it.') }}
                        </p>
                    </li>
                </ul>
            </div>

            <div class="relative" data-aos="fade-left">
                <div class="absolute -top-10 -right-10 h-48 w-48 rounded-full bg-[var(--color-primary)]/20 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] shadow-[var(--shadow-lg)]">
                    <img src="{{ asset('images/work-img-2.png') }}"
                         alt="{{ __('Member training in a private gym') }}"
                         class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Our values — 3 cards
    ===================================================================== --}}
    <section class="relative bg-[var(--color-brand-50)] py-20 sm:py-24">
        <div class="site-container">

            <div class="section-head">
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)] ring-1 ring-[var(--color-brand-100)]"
                      data-aos="fade-down">
                    <i class="fa-solid fa-heart"></i>
                    {{ __('What we stand for') }}
                </span>
                <h2 class="section-head__title" data-aos="fade-up">
                    {{ __('The values that') }}
                    <span class="text-[var(--color-primary)]">{{ __('drive us') }}</span>
                </h2>
                <p class="section-head__subtitle" data-aos="fade-up" data-aos-delay="100">
                    {{ __('Built on trust, accessibility, and a genuine love for training.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:gap-7 md:grid-cols-2 lg:grid-cols-3">

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="100">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-fire text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Passion for training') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('We\'re lifters, runners, and coaches ourselves. Every decision we make starts with "would we actually want to use this?"') }}
                    </p>
                </div>

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="200">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-shield-halved text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Trust & safety') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('Verified hosts, encrypted payments, transparent cancellation rules, and a team that actually responds. Nothing about your session should feel sketchy.') }}
                    </p>
                </div>

                <div class="group rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)] hover:shadow-[var(--shadow-lg)]"
                     data-aos="fade-up" data-aos-delay="300">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                        <i class="fa-solid fa-people-group text-xl"></i>
                    </span>
                    <h3 class="mt-5 text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Local community') }}</h3>
                    <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                        {{ __('Every session on SPOTMEE is a small win for a local host and a local athlete. That\'s the fitness economy we want to build.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Founder note
    ===================================================================== --}}
    <section class="site-container py-20 sm:py-24">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-16">

            <div class="relative order-2 lg:order-1" data-aos="fade-right">
                <div class="absolute -bottom-10 -left-10 h-48 w-48 rounded-full bg-[var(--color-brand-200)]/30 blur-3xl"></div>

                <div class="relative overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] shadow-[var(--shadow-lg)]">
                    <img src="{{ asset('images/community-img-001.png') }}"
                         alt="{{ __('Founder & CEO') }}"
                         class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(0,69,77,0.85)] via-[rgba(0,69,77,0.2)] to-transparent"></div>

                    <div class="absolute bottom-7 left-7 text-white">
                        <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] backdrop-blur-sm">
                            <i class="fa-solid fa-circle text-[7px] text-[var(--color-brand-200)]"></i>
                            {{ __('Founder & CEO') }}
                        </p>
                        <h4 class="mt-3 text-[26px] font-bold leading-tight">John Doe</h4>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2" data-aos="fade-left">
                <span class="inline-flex items-center gap-2 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)]">
                    <i class="fa-solid fa-quote-left"></i>
                    {{ __('A note from') }}
                </span>

                <h2 class="mt-4 text-[32px] font-bold leading-[1.1] tracking-tight text-[var(--color-ink-900)] sm:text-[40px]">
                    {{ __('A note from') }}
                    <span class="block text-[var(--color-primary)]">{{ __('our founder') }}</span>
                </h2>

                <div class="relative mt-7 rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-9">
                    <i class="fa-solid fa-quote-left absolute -top-4 left-7 flex h-9 w-9 items-center justify-center rounded-full bg-[var(--color-primary)] text-[14px] text-white shadow-md"></i>

                    <p class="text-[17px] italic leading-relaxed text-[var(--color-ink-700)] sm:text-[18px]">
                        {{ __('I built SPOTMEE because my own gym routine kept falling apart. Public gyms were packed, the squat racks were always taken, and a proper membership cost more than I trained in a month. Meanwhile, my neighbor\'s garage had every piece of equipment I needed — just sitting there empty. SPOTMEE is the bridge between those two realities: a platform where your workout space is five minutes away, costs exactly what you use, and feels like your own.') }}
                    </p>

                    <div class="mt-6 flex items-center gap-3">
                        <span class="h-px flex-1 bg-[var(--color-brand-100)]"></span>
                        <span class="text-[13px] font-semibold uppercase tracking-[0.08em] text-[var(--color-ink-500)]">
                            — John Doe, {{ __('Founder & CEO') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Final CTA
    ===================================================================== --}}
    <section class="site-container pb-16 sm:pb-24">
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[var(--color-primary)] via-[var(--color-brand-500)] to-[var(--color-brand-200)] px-6 py-14 text-center sm:px-12 sm:py-20">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.22),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(0,69,77,0.35),transparent_55%)]"></div>

            <div class="relative z-10 mx-auto max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[12px] font-semibold uppercase tracking-[0.1em] text-white backdrop-blur-sm"
                      data-aos="fade-down">
                    <i class="fa-solid fa-sparkles"></i>
                    {{ __('Join the movement') }}
                </span>

                <h2 class="mt-5 text-[32px] font-extrabold leading-[1.1] tracking-tight text-white sm:text-[48px]"
                    data-aos="fade-up">
                    {{ __('Ready to join the') }}
                    <span class="block text-[var(--color-brand-200)]">{{ __('SPOTMEE community?') }}</span>
                </h2>

                <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4"
                     data-aos="fade-up" data-aos-delay="150">
                    <a href="{{ route('find-a-gym') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-8 py-3.5 text-[15px] font-bold text-[var(--color-primary)] shadow-lg transition-all hover:-translate-y-0.5 hover:shadow-xl">
                        <i class="fa-solid fa-magnifying-glass text-[13px]"></i>
                        {{ __('Find a Gym') }}
                    </a>
                    <a href="{{ route('become-a-host') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full border border-white/60 bg-white/10 px-8 py-3.5 text-[15px] font-bold text-white backdrop-blur-sm transition-all hover:bg-white/20">
                        {{ __('Become a Host') }}
                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
