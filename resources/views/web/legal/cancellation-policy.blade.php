@extends('layouts.web.master')

@section('title', 'Cancellation Policy — SPOTMEE')

@section('content')
    @include('web.legal.partials.hero', [
        'heroPrefix'   => 'cancellation_policy',
        'heroEyebrow'  => __('Legal · Policy'),
        'heroSubtitle' => __('How cancellations, refunds, and no-shows are handled across every SPOTMEE booking — fair for Users, fair for Hosts.'),
        'lastUpdated'  => 'April 23, 2026',
    ])

    <section class="site-container py-14 sm:py-20">
        <div class="legal-layout">

            {{-- TOC --}}
            <aside class="legal-toc" data-aos="fade-right">
                <p class="legal-toc__title">{{ __('On this page') }}</p>
                <ul class="legal-toc__list" data-legal-toc>
                    <li><a href="#overview">{{ __('1. Overview') }}</a></li>
                    <li><a href="#user">{{ __('2. User cancellations') }}</a></li>
                    <li><a href="#no-show">{{ __('3. No-shows & late arrivals') }}</a></li>
                    <li><a href="#host">{{ __('4. Host cancellations') }}</a></li>
                    <li><a href="#refunds">{{ __('5. Refunds & processing') }}</a></li>
                    <li><a href="#fees">{{ __('6. Platform & payment fees') }}</a></li>
                    <li><a href="#force-majeure">{{ __('7. Extenuating circumstances') }}</a></li>
                    <li><a href="#modifications">{{ __('8. Modifications') }}</a></li>
                    <li><a href="#disputes">{{ __('9. Disputes') }}</a></li>
                    <li><a href="#contact">{{ __('10. Contact') }}</a></li>
                </ul>
            </aside>

            {{-- Content --}}
            <div class="legal-card" data-aos="fade-up">
                <div class="legal-meta-bar">
                    <span class="legal-meta-chip"><i class="fa-solid fa-calendar-xmark"></i> {{ __('Cancellations') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-rotate-left"></i> {{ __('Refunds') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-language"></i> {{ __('English (US)') }}</span>
                </div>

                <div class="legal-prose">

                    <section class="legal-section" id="overview">
                        <h2 class="legal-section__heading"><span class="legal-section__num">1</span> {{ __('Overview') }}</h2>
                        <p>
                            {{ __('SPOTMEE is committed to fair treatment for both Users and Hosts. This Cancellation Policy explains the timing, fees, and refund process that apply to every booking made through the platform.') }}
                        </p>
                        <p>
                            {{ __('By booking or accepting a session on SPOTMEE, you agree to this policy. Specific Hosts may publish stricter rules within their listing — where a listing\'s policy is stricter than this document, the listing policy controls to the extent permitted by law.') }}
                        </p>

                        <div class="legal-callout">
                            <span class="legal-callout__icon"><i class="fa-solid fa-lightbulb"></i></span>
                            <div class="legal-callout__body">
                                <p>
                                    <strong>{{ __('Tip.') }}</strong>
                                    {{ __('Always review the cancellation terms shown on the listing page before booking — they are displayed right above the booking form.') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="legal-section" id="user">
                        <h2 class="legal-section__heading"><span class="legal-section__num">2</span> {{ __('User cancellations') }}</h2>
                        <p>
                            {{ __('At SPOTMEE, each appointment is reserved exclusively for you. We ask for at least 24 hours\' notice for cancellations. If you need to cancel a booking, the following rules apply:') }}
                        </p>

                        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-[var(--color-brand-100)] bg-[color-mix(in_srgb,var(--color-brand-50)_40%,#ffffff)] p-4">
                                <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[var(--color-primary)]">{{ __('At least 24 hours before start') }}</p>
                                <p class="mt-2 text-[15px] font-bold text-[var(--color-ink-900)]">{{ __('Full refund') }}</p>
                                <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('100% of the session price is refunded to your original payment method when you cancel with at least 24 hours\' notice before the scheduled start time.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-red-200 bg-red-50/40 p-4">
                                <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-red-600">{{ __('Within 24 hours of start') }}</p>
                                <p class="mt-2 text-[15px] font-bold text-[var(--color-ink-900)]">{{ __('Non-refundable') }}</p>
                                <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Appointments cancelled within 24 hours of the scheduled time are non-refundable. Cancellations made after the cutoff are considered late cancellations.') }}</p>
                            </div>
                        </div>

                        <p class="mt-4 rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)]/60 p-4 text-[14px] text-[var(--color-ink-700)]">
                            <strong class="text-[var(--color-ink-900)]">{{ __('Example:') }}</strong>
                            {{ __('If your appointment is at 3:00 PM on Friday, cancellation must be completed before 3:00 PM on Thursday to receive a refund.') }}
                        </p>

                        <p class="mt-4">
                            {{ __('You can cancel an upcoming booking at any time from your account dashboard under "My bookings".') }}
                        </p>
                    </section>

                    <section class="legal-section" id="no-show">
                        <h2 class="legal-section__heading"><span class="legal-section__num">3</span> {{ __('No-shows & late arrivals') }}</h2>
                        <ul>
                            <li>{{ __('A session is marked as a "no-show" if you do not arrive within 15 minutes of the scheduled start time without prior communication with the Host.') }}</li>
                            <li>{{ __('No-shows are not eligible for a refund, and the Host will be paid in full.') }}</li>
                            <li>{{ __('Late arrivals shorten your session; the scheduled end time does not shift.') }}</li>
                            <li>{{ __('Repeated no-shows may result in account warnings or suspension.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="host">
                        <h2 class="legal-section__heading"><span class="legal-section__num">4</span> {{ __('Host cancellations') }}</h2>
                        <p>{{ __('We understand emergencies happen. If a Host cancels a confirmed booking:') }}</p>
                        <ul>
                            <li>{{ __('The User receives a 100% refund to their original payment method.') }}</li>
                            <li>{{ __('SPOTMEE will assist the User in finding an alternative slot or Host when possible.') }}</li>
                            <li>{{ __('Hosts who cancel confirmed bookings repeatedly may receive warnings, listing demotion, or account suspension.') }}</li>
                        </ul>
                        <p>{{ __('Hosts are expected to manage their availability carefully and only accept bookings they can honor.') }}</p>
                    </section>

                    <section class="legal-section" id="refunds">
                        <h2 class="legal-section__heading"><span class="legal-section__num">5</span> {{ __('Refunds & processing') }}</h2>
                        <ul>
                            <li>{{ __('Approved refunds are issued to the original payment method used for the booking.') }}</li>
                            <li>{{ __('Most refunds appear within 5–10 business days, depending on your bank or card issuer.') }}</li>
                            <li>{{ __('We cannot refund to a different card or payment method.') }}</li>
                            <li>{{ __('Promotional credits or gift balances are refunded as credit, not cash.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="fees">
                        <h2 class="legal-section__heading"><span class="legal-section__num">6</span> {{ __('Platform & payment fees') }}</h2>
                        <p>
                            {{ __('Unless otherwise noted, SPOTMEE does not charge a separate cancellation fee. Payment processing fees charged by third-party providers (e.g., Stripe) may be non-refundable on small partial refunds; where this applies, it will be disclosed on your receipt.') }}
                        </p>
                    </section>

                    <section class="legal-section" id="force-majeure">
                        <h2 class="legal-section__heading"><span class="legal-section__num">7</span> {{ __('Extenuating circumstances') }}</h2>
                        <p>{{ __('We may waive normal cancellation rules in rare, genuinely unforeseeable circumstances outside of your or the Host\'s control, such as:') }}</p>
                        <ul>
                            <li>{{ __('Declared natural disasters or severe weather at the session location.') }}</li>
                            <li>{{ __('Serious illness, injury, or bereavement with appropriate documentation.') }}</li>
                            <li>{{ __('Government-ordered closures or travel restrictions.') }}</li>
                            <li>{{ __('Significant utility failures at the booked location (power, water, HVAC).') }}</li>
                        </ul>
                        <p>{{ __('Requests must be submitted within 14 days of the scheduled session, with supporting documentation where applicable.') }}</p>
                    </section>

                    <section class="legal-section" id="modifications">
                        <h2 class="legal-section__heading"><span class="legal-section__num">8</span> {{ __('Modifications') }}</h2>
                        <p>
                            {{ __('Booking modifications (e.g., changing the time or date) are handled as a cancellation of the original booking followed by a new booking, and the standard cancellation rules above apply. Hosts are encouraged to accommodate reasonable reschedule requests when possible.') }}
                        </p>
                    </section>

                    <section class="legal-section" id="disputes">
                        <h2 class="legal-section__heading"><span class="legal-section__num">9</span> {{ __('Disputes') }}</h2>
                        <p>
                            {{ __('If you believe a cancellation or refund decision was made in error, please contact our support team within 14 days. We\'ll review your case and the Host\'s response before issuing a final decision.') }}
                        </p>
                    </section>

                    <section class="legal-section" id="contact">
                        <h2 class="legal-section__heading"><span class="legal-section__num">10</span> {{ __('Contact') }}</h2>
                        <p>
                            {{ __('Questions about this policy? Email us at') }}
                            <a href="mailto:support@spotmee.com" class="font-semibold text-[var(--color-primary)] hover:underline">support@spotmee.com</a>
                            {{ __('or use our') }}
                            <a href="{{ route('contact') }}" class="font-semibold text-[var(--color-primary)] hover:underline">{{ __('contact form') }}</a>.
                        </p>
                    </section>
                </div>
            </div>
        </div>

        @include('web.legal.partials.help-box')
    </section>

    @include('web.legal.partials.styles')
@endsection
@push('scripts')
    <script>
        (function () {
            const toc = document.querySelector('[data-legal-toc]');
            if (!toc) return;
            const links = Array.from(toc.querySelectorAll('a[href^="#"]'));
            const sections = links
                .map(l => document.getElementById(l.getAttribute('href').slice(1)))
                .filter(Boolean);
            if (!sections.length) return;

            const setActive = (id) => {
                links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href') === '#' + id));
            };

            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) setActive(e.target.id); });
            }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

            sections.forEach(s => io.observe(s));
        })();
    </script>
@endpush

