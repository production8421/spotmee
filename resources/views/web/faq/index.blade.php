@extends('layouts.web.master')

@section('title', 'FAQ — SPOTMEE')

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
                    <i class="fa-solid fa-circle-question"></i>
                    {{ __('Help center') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('Frequently Asked') }}
                    <span class="text-[var(--color-brand-200)]">{{ __('Questions') }}</span>
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('Everything you need to know about training on SPOTMEE, hosting your space, personal training, group classes, and more.') }}
                </p>

                {{-- Quick search --}}
                <div class="mx-auto mt-7 flex w-full max-w-xl items-center gap-2 rounded-full bg-white/95 px-4 py-2 shadow-[var(--shadow-lg)] backdrop-blur"
                     data-aos="fade-up" data-aos-delay="300">
                    <i class="fa-solid fa-magnifying-glass text-[var(--color-primary)]"></i>
                    <input type="text" id="faq-search"
                           placeholder="{{ __('Search questions…') }}"
                           class="w-full border-0 bg-transparent p-2 text-[15px] text-[var(--color-ink-900)] placeholder:text-[var(--color-ink-400)] focus:outline-none focus:ring-0">
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         FAQ list
    ===================================================================== --}}
    <section class="site-container py-14 sm:py-20">
        <div class="mx-auto max-w-4xl">

            {{-- ============================================================
                 FAQ 1 — What is SPOTMEE?
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up" open>
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('What is SPOTMEE?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-lead">
                        {{ __('SPOTMEE is an online platform that was created to make fitness more accessible and affordable for everyone. We are not trying to compete with traditional gyms — we\'re building a marketplace where private fitness is accessible, affordable, and on your terms.') }}
                    </p>

                    <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div>
                            <h4 class="faq-subhead">
                                {{ __('We Remove') }}
                                <span class="text-[var(--color-primary)]">{{ __('The Biggest Barriers') }}</span>
                                {{ __('In Fitness') }}
                            </h4>
                            <ul class="faq-check-list">
                                <li>{{ __('Crowded gym floors — especially at peak hours') }}</li>
                                <li>{{ __('Expensive monthly memberships — whether you go or not') }}</li>
                                <li>{{ __('Long waits for shared equipment') }}</li>
                                <li>{{ __('Intimidating environments, noise, distractions') }}</li>
                                <li>{{ __('Rigid contracts') }}</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="faq-subhead">
                                {{ __('Who Is') }}
                                <span class="text-[var(--color-primary)]">SPOTMEE</span>
                                {{ __('For?') }}
                            </h4>
                            <ul class="faq-check-list">
                                <li>{{ __('Individuals who want privacy') }}</li>
                                <li>{{ __('Fitness enthusiasts who want uninterrupted training') }}</li>
                                <li>{{ __('Trainers and trainees who want a private space') }}</li>
                                <li>{{ __('Pay per session for private access') }}</li>
                                <li>{{ __('No waiting and no rush') }}</li>
                                <li>{{ __('Anyone tired of crowded environments') }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div>
                            <h4 class="faq-subhead">{{ __('Our Users') }}</h4>
                            <p class="text-[14px] italic text-[var(--color-ink-500)]">
                                {{ __('Individuals who book very affordable workout and/or personal training sessions.') }}
                            </p>
                            <ol class="faq-ordered-list">
                                <li>{{ __('Browse available private gyms or personal trainers in your area.') }}</li>
                                <li>{{ __('Book by the session (40 or 60 minutes), arrive at your scheduled time, and train uninterrupted.') }}</li>
                                <li>{{ __('You pay only for the time you reserve. No waiting for equipment. No pressure.') }}</li>
                                <li>{{ __('Users book appointments online that show up on the Host\'s calendar.') }}</li>
                                <li>{{ __('Lock in. Show up. Train with intention. Leave accomplished.') }}</li>
                            </ol>
                        </div>

                        <div>
                            <h4 class="faq-subhead">{{ __('Our Hosts') }}</h4>
                            <p class="text-[14px] italic text-[var(--color-ink-500)]">
                                {{ __('Individuals with home gyms, private workout spaces, and/or personal training ability.') }}
                            </p>
                            <ol class="faq-ordered-list">
                                <li>{{ __('Must apply and go through a vetting process to ensure consistency, safety, and trust for every User who books.') }}</li>
                                <li>{{ __('You set your availability, welcome your bookings, control your space.') }}</li>
                                <li>{{ __('We handle the platform infrastructure so you can focus on your business.') }}</li>
                                <li>{{ __('List your workout space, gym, or private training sessions and earn income on your schedule.') }}</li>
                                <li>{{ __('We don\'t make money unless you make money.') }}</li>
                            </ol>
                        </div>
                    </div>

                    @include('web.faq.partials.cta', ['kicker' => __('It\'s not a gym membership. It\'s access — on your terms.')])
                </div>
            </details>

            {{-- ============================================================
                 FAQ 2 — How much does it cost?
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up">
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('How much does it cost?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-hero-line">
                        {{ __('It is FREE to host on the SPOTMEE platform') }}
                    </p>

                    <p class="faq-lead">
                        {{ __('SPOTMEE is 100% free for you to use.') }}
                    </p>
                    <ol class="faq-ordered-list">
                        <li>{{ __('Sign up as a host to list your gym, workout space, or personal training sessions') }}</li>
                        <li>{{ __('Get bookings') }}</li>
                        <li>{{ __('Take your cut and expand your business while we handle everything else!') }}</li>
                    </ol>

                    <h4 class="faq-subhead mt-8">{{ __('Qualifying Tiers') }}</h4>
                    <p class="text-[14px] text-[var(--color-ink-500)]">
                        {{ __('From home gyms to professional studios, every host fits into one of three tiers (with a required minimum workout space of 125 square feet per person in every tier):') }}
                    </p>

                    <ul class="mt-4 space-y-3">
                        <li class="flex gap-3">
                            <span class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                <i class="fa-solid fa-medal text-[11px]"></i>
                            </span>
                            <p class="text-[14px] leading-relaxed text-[var(--color-ink-700)]">
                                <span class="font-bold text-[var(--color-primary)]">{{ __('Silver') }}</span>
                                {{ __('requires the Host to offer 1 type of workout in their space (like weights) — for one or more people.') }}
                            </p>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                <i class="fa-solid fa-trophy text-[11px]"></i>
                            </span>
                            <p class="text-[14px] leading-relaxed text-[var(--color-ink-700)]">
                                <span class="font-bold text-[var(--color-primary)]">{{ __('Gold') }}</span>
                                {{ __('requires the Host to offer 2 types of workouts in their space (like weights + cardio machine) — for one or more people.') }}
                            </p>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                <i class="fa-solid fa-gem text-[11px]"></i>
                            </span>
                            <p class="text-[14px] leading-relaxed text-[var(--color-ink-700)]">
                                <span class="font-bold text-[var(--color-primary)]">{{ __('Platinum') }}</span>
                                {{ __('requires the Host to offer a premium setup for multiple people with multiple workout options that has additional features as part of their space — like a restroom, shower, recovery area, etc.') }}
                            </p>
                        </li>
                    </ul>

                    {{-- Breakdown table --}}
                    <h4 class="faq-subhead mt-10 uppercase tracking-[0.1em]">
                        {{ __('The Breakdown') }}
                    </h4>

                    <div class="mt-4 overflow-hidden rounded-2xl border border-[var(--color-brand-100)]">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[780px] border-collapse text-left text-[13px]">
                                <thead>
                                    <tr class="bg-[var(--color-brand-50)] text-[var(--color-ink-900)]">
                                        <th class="px-4 py-4 font-bold uppercase tracking-[0.05em]">{{ __('Qualifying tiers') }}</th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">
                                            {{ __('Users book') }}<br>
                                            <span class="text-[var(--color-primary)]">{{ __('1 HOUR') }}</span><br>
                                            <span class="text-[11px] font-medium normal-case tracking-normal text-[var(--color-ink-500)]">{{ __('Per session, per person') }}</span>
                                        </th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">{{ __('Host receives') }}</th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">{{ __('SPOTMEE receives') }}</th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">
                                            {{ __('Users book') }}<br>
                                            <span class="text-[var(--color-primary)]">{{ __('40-MINUTE') }}</span><br>
                                            <span class="text-[11px] font-medium normal-case tracking-normal text-[var(--color-ink-500)]">{{ __('Per session, per person') }}</span>
                                        </th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">{{ __('Host receives') }}</th>
                                        <th class="px-4 py-4 text-center font-bold uppercase tracking-[0.05em]">{{ __('SPOTMEE receives') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-[var(--color-ink-700)]">
                                    <tr class="border-t border-[var(--color-brand-100)]">
                                        <td class="px-4 py-4 font-bold text-[var(--color-primary)]">{{ __('Silver') }}</td>
                                        <td class="px-4 py-4 text-center font-semibold">$12</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td rowspan="3" class="border-l border-r border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_50%,#ffffff)] px-4 py-4 text-center text-[13px] italic leading-relaxed text-[var(--color-ink-500)]">
                                            {{ __('The SPOTMEE commission/cut is only disclosed to Hosts who sign up and register') }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-semibold">$10</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                    </tr>
                                    <tr class="border-t border-[var(--color-brand-100)]">
                                        <td class="px-4 py-4 font-bold text-[var(--color-primary)]">{{ __('Gold') }}</td>
                                        <td class="px-4 py-4 text-center font-semibold">$16</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center font-semibold">$14</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                    </tr>
                                    <tr class="border-t border-[var(--color-brand-100)]">
                                        <td class="px-4 py-4 font-bold text-[var(--color-primary)]">{{ __('Platinum') }}</td>
                                        <td class="px-4 py-4 text-center font-semibold">$24</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center font-semibold">$20</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                    </tr>
                                    <tr class="border-t border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_30%,#ffffff)]">
                                        <td class="px-4 py-4 font-bold text-[var(--color-primary)]">
                                            {{ __('Personal training') }}
                                            <span class="block text-[11px] font-medium normal-case text-[var(--color-ink-500)]">{{ __('(must have credentials)') }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center font-semibold">$45</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td class="px-4 py-4 text-center text-[var(--color-ink-400)]">—</td>
                                        <td colspan="3" class="px-4 py-4 text-center text-[13px] italic text-[var(--color-ink-500)]">
                                            {{ __('Not an option') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include('web.faq.partials.cta', ['kicker' => __('Your fitness business is showcased across social spaces at no cost to you')])
                </div>
            </details>

            {{-- ============================================================
                 FAQ 3 — What types of spaces qualify?
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up">
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('What types of spaces qualify for the SPOTMEE platform?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-hero-line">
                        {{ __('Not every space qualifies, and that\'s intentional') }}
                    </p>

                    <h4 class="faq-subhead uppercase tracking-[0.1em]">{{ __('Our Goal Is Simple') }}</h4>
                    <p class="faq-lead">
                        {{ __('We require safe, professional, and distraction-free workout environments. Every Host space is reviewed before being approved on the platform.') }}
                    </p>

                    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_40%,#ffffff)] p-5">
                            <h4 class="flex items-center gap-2 text-[15px] font-bold text-[var(--color-ink-900)]">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                    <i class="fa-solid fa-thumbs-up text-[13px]"></i>
                                </span>
                                {{ __('Commonly Approved Spaces') }}
                            </h4>
                            <p class="mt-2 text-[13px] italic text-[var(--color-ink-500)]">
                                {{ __('(provided they meet our safety, size, cleanliness, and equipment standards)') }}
                            </p>
                            <ul class="faq-check-list mt-3">
                                <li>{{ __('Garage gyms') }}</li>
                                <li>{{ __('Private studios') }}</li>
                                <li>{{ __('Professionally converted sheds') }}</li>
                                <li>{{ __('Covered backyard fitness setups') }}</li>
                                <li>{{ __('In-law units or dedicated detached structures') }}</li>
                            </ul>
                        </div>

                        <div class="rounded-2xl border border-red-200 bg-red-50/40 p-5">
                            <h4 class="flex items-center gap-2 text-[15px] font-bold text-[var(--color-ink-900)]">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white">
                                    <i class="fa-solid fa-thumbs-down text-[13px]"></i>
                                </span>
                                {{ __('Spaces That Commonly Do Not Qualify') }}
                            </h4>
                            <ul class="faq-x-list mt-5">
                                <li>{{ __('Bedrooms or shared interior rooms') }}</li>
                                <li>{{ __('Apartment living rooms') }}</li>
                                <li>{{ __('Public parks or unstructured outdoor locations') }}</li>
                                <li>{{ __('Isolated "middle-of-nowhere" locations without safety standards') }}</li>
                            </ul>
                        </div>
                    </div>

                    <h4 class="faq-subhead mt-10 uppercase tracking-[0.1em]">
                        {{ __('Will I have the workout space to myself?') }}
                    </h4>
                    <p class="faq-lead">
                        {{ __('Yes. Your session is private where it matters most — the equipment and workout space you book are reserved exclusively for you. No sharing. No waiting.') }}
                    </p>
                    <p class="faq-lead">
                        {{ __('In larger locations, Hosts may use areas of their facility, but your reserved setup remains fully dedicated to you and uninterrupted. Choose the facility that works best for you.') }}
                    </p>

                    @include('web.faq.partials.cta', ['kicker' => __('We\'re building trust — and that starts with standards.')])
                </div>
            </details>

            {{-- ============================================================
                 FAQ 4 — Personal training
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up">
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('Personal training — Do you offer personal training?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-hero-line">{{ __('YES WE DO!') }}</p>

                    <p class="faq-lead">
                        {{ __('Get personal training sessions exclusively from our credentialed SPOTMEE Hosts — your certified experts.') }}
                    </p>
                    <p class="faq-lead">
                        {{ __('We believe safety and professionalism matter. That\'s why only verified Hosts with recognized fitness certifications can offer personal training through the platform.') }}
                    </p>

                    <p class="faq-lead mt-6">
                        <span class="font-semibold text-[var(--color-ink-900)]">{{ __('Personal Training sessions are only $45 per hour and include:') }}</span>
                    </p>
                    <ul class="faq-check-list">
                        <li>{{ __('A private workout space') }}</li>
                        <li>{{ __('A certified trainer') }}</li>
                        <li>{{ __('Focused, one-on-one attention') }}</li>
                        <li>{{ __('A structured path toward real results') }}</li>
                        <li>{{ __('A fitness journey they can count on with confidence and structure') }}</li>
                    </ul>

                    @include('web.faq.partials.cta', ['kicker' => __('Fitness. Performance. Potential — redefined.')])
                </div>
            </details>

            {{-- ============================================================
                 FAQ 5 — Group classes
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up">
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('Does SPOTMEE offer group classes?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-hero-line">{{ __('ABSOLUTELY!') }}</p>

                    <p class="faq-lead">
                        {{ __('Qualified Hosts have the opportunity and flexibility to offer group classes such as:') }}
                    </p>
                    <ul class="faq-check-list grid grid-cols-1 sm:grid-cols-2">
                        <li>{{ __('HIIT') }}</li>
                        <li>{{ __('Kickboxing') }}</li>
                        <li>{{ __('Boxing') }}</li>
                        <li>{{ __('Zumba') }}</li>
                        <li>{{ __('Strength & Conditioning') }}</li>
                        <li>{{ __('Yoga') }}</li>
                        <li>{{ __('And more') }}</li>
                    </ul>

                    <p class="faq-lead mt-6">
                        {{ __('To maintain safety and comfort, all group classes must meet SPOTMEE standards — including:') }}
                    </p>
                    <ul class="faq-check-list">
                        <li>{{ __('Minimum 125 square feet per participant') }}</li>
                        <li>{{ __('Proper ventilation and dust control') }}</li>
                        <li>{{ __('Clean, sanitized equipment') }}</li>
                        <li>{{ __('Clutter-free training environment') }}</li>
                        <li>{{ __('Clear, hazard-free layouts') }}</li>
                        <li>{{ __('Safe equipment and a safe layout') }}</li>
                    </ul>
                    <p class="faq-lead mt-4 italic">
                        {{ __('Following these guidelines helps Hosts shine and Users thrive!') }}
                    </p>

                    @include('web.faq.partials.cta', ['kicker' => __('Step in. Work Out. Level Up.')])
                </div>
            </details>

            {{-- ============================================================
                 FAQ 6 — Workout partner
            ============================================================= --}}
            <details class="faq-item group" data-aos="fade-up">
                <summary class="faq-summary">
                    <span class="faq-summary__label">
                        <i class="fa-solid fa-chevron-down faq-summary__chevron"></i>
                        {{ __('Can I bring a workout partner with me?') }}
                    </span>
                </summary>
                <div class="faq-body">
                    <p class="faq-hero-line">{{ __('ANYTIME!') }}</p>

                    <p class="faq-lead">
                        {{ __('SPOTMEE is about intentional training — whether solo or alongside someone pushing you to level up.') }}
                    </p>
                    <p class="faq-lead">
                        {{ __('Our standard is simple — Hosts must provide each User with their own dedicated equipment at each session.') }}
                    </p>

                    <p class="faq-lead mt-6 font-semibold text-[var(--color-ink-900)]">{{ __('Here is an example:') }}</p>
                    <ul class="mt-3 space-y-2">
                        <li class="flex items-center gap-3 rounded-xl bg-[var(--color-brand-50)] px-4 py-3 text-[14px]">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                <i class="fa-solid fa-user text-[11px]"></i>
                            </span>
                            <span>{{ __('1 person = 1 squat rack') }}</span>
                        </li>
                        <li class="flex items-center gap-3 rounded-xl bg-[var(--color-brand-50)] px-4 py-3 text-[14px]">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                <i class="fa-solid fa-user-group text-[11px]"></i>
                            </span>
                            <span>{{ __('2 people = 2 squat racks') }}</span>
                        </li>
                        <li class="flex items-center gap-3 rounded-xl bg-[var(--color-brand-50)] px-4 py-3 text-[14px]">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                                <i class="fa-solid fa-people-group text-[11px]"></i>
                            </span>
                            <span>{{ __('3 people = 3 squat racks, and so on') }}</span>
                        </li>
                    </ul>

                    <p class="faq-lead mt-6">
                        {{ __('This ensures Users enjoy NO wait, NO interruptions — just pure fitness!') }}
                    </p>
                    <p class="faq-lead italic">
                        {{ __('The cost is per workout session / per person.') }}
                    </p>

                    @include('web.faq.partials.cta', ['kicker' => __('Building brand loyalty — one workout at a time.')])
                </div>
            </details>

            {{-- "Still have questions?" card --}}
            <div class="mt-12 rounded-[24px] border border-[var(--color-brand-100)] bg-gradient-to-br from-[var(--color-brand-50)] to-white p-8 text-center sm:p-12"
                 data-aos="fade-up">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[var(--color-primary)] shadow-sm ring-1 ring-[var(--color-brand-100)]">
                    <i class="fa-solid fa-headset text-xl"></i>
                </span>
                <h3 class="mt-4 text-[22px] font-bold text-[var(--color-ink-900)] sm:text-[26px]">
                    {{ __('Still have questions?') }}
                </h3>
                <p class="mx-auto mt-2 max-w-xl text-[15px] text-[var(--color-ink-500)]">
                    {{ __('Our team is here to help. Reach out and we\'ll get back to you within one business day.') }}
                </p>
                <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg justify-center">
                        <i class="fa-solid fa-envelope text-[13px]"></i>
                        {{ __('Contact us') }}
                    </a>
                    <a href="{{ route('become-a-host') }}" class="btn btn-outline btn-lg justify-center">
                        {{ __('Become a Host') }}
                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ-specific styles (scoped via .faq-* classes) --}}
    <style>
        .faq-item {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid var(--color-brand-100);
            background: #ffffff;
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .faq-item[open] {
            border-color: var(--color-brand-200);
            box-shadow: 0 12px 28px rgba(0, 109, 119, 0.1);
        }

        .faq-summary {
            list-style: none;
            cursor: pointer;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-brand-500) 100%);
            color: #ffffff;
            user-select: none;
            transition: filter 0.15s ease;
            border-radius: 5px;
        }
        .faq-item[open] .faq-summary {
            border-radius: 5px;
        }
        .faq-summary::-webkit-details-marker { display: none; }
        .faq-summary:hover { filter: brightness(1.05); }

        .faq-summary__label {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        @media (min-width: 640px) {
            .faq-summary__label { font-size: 15px; }
        }

        .faq-summary__chevron {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            font-size: 11px;
            transition: transform 0.25s ease;
        }

        .faq-item[open] .faq-summary__chevron {
            transform: rotate(180deg);
        }

        .faq-body {
            padding: 28px 28px 32px;
        }
        @media (min-width: 640px) {
            .faq-body { padding: 32px 36px 36px; }
        }

        .faq-hero-line {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 20px;
        }

        .faq-lead {
            font-size: 15px;
            line-height: 1.7;
            color: var(--color-ink-700);
            margin-bottom: 14px;
        }

        .faq-subhead {
            font-size: 16px;
            font-weight: 800;
            color: var(--color-ink-900);
            margin-bottom: 12px;
            letter-spacing: -0.01em;
        }

        .faq-check-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .faq-check-list.grid { display: grid; gap: 8px 16px; }
        .faq-check-list > li {
            position: relative;
            padding-left: 28px;
            font-size: 14px;
            line-height: 1.55;
            color: var(--color-ink-700);
        }
        .faq-check-list > li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 1px;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--color-brand-50);
            color: var(--color-primary);
            font-size: 10px;
        }

        .faq-x-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .faq-x-list > li {
            position: relative;
            padding-left: 28px;
            font-size: 14px;
            line-height: 1.55;
            color: var(--color-ink-700);
        }
        .faq-x-list > li::before {
            content: '\f00d';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 1px;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #fee2e2;
            color: #dc2626;
            font-size: 10px;
        }

        .faq-ordered-list {
            list-style: none;
            padding: 0;
            margin: 12px 0 0;
            counter-reset: faq-counter;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .faq-ordered-list > li {
            counter-increment: faq-counter;
            position: relative;
            padding-left: 36px;
            font-size: 14px;
            line-height: 1.6;
            color: var(--color-ink-700);
        }
        .faq-ordered-list > li::before {
            content: counter(faq-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--color-primary);
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
        }

        .faq-cta {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px dashed var(--color-brand-200);
        }
        @media (min-width: 640px) {
            .faq-cta {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .faq-cta__kicker {
            font-size: 16px;
            font-weight: 700;
            color: var(--color-ink-900);
            letter-spacing: -0.01em;
        }
        .faq-cta__action { display: inline-flex; align-items: center; gap: 10px; }
        .faq-cta__host-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-primary);
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s ease;
        }
        .faq-cta__host-link:hover {
            color: var(--color-primary-hover, #005962);
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Simple FAQ search: filter <details> items by question text
        (function () {
            const input = document.getElementById('faq-search');
            if (!input) return;
            const items = Array.from(document.querySelectorAll('.faq-item'));
            input.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                items.forEach((item) => {
                    const label = item.querySelector('.faq-summary__label')?.innerText.toLowerCase() ?? '';
                    const body  = item.querySelector('.faq-body')?.innerText.toLowerCase() ?? '';
                    const show  = !q || label.includes(q) || body.includes(q);
                    item.style.display = show ? '' : 'none';
                    if (show && q) item.setAttribute('open', '');
                });
            });
        })();
    </script>
@endpush
