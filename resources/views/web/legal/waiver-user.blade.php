@extends('layouts.web.master')

@section('title', 'Waiver of Liability (User) — SPOTMEE')

@section('content')
    @include('web.legal.partials.hero', [
        'heroPrefix'   => 'waiver_liability_user',
        'heroEyebrow'  => __('Legal · Users'),
        'heroSubtitle' => __('The risks you acknowledge and the commitments you make whenever you book a session through SPOTMEE.'),
        'lastUpdated'  => 'April 23, 2026',
    ])

    <section class="site-container py-14 sm:py-20">
        <div class="legal-layout">

            {{-- TOC --}}
            <aside class="legal-toc" data-aos="fade-right">
                <p class="legal-toc__title">{{ __('On this page') }}</p>
                <ul class="legal-toc__list" data-legal-toc>
                    <li><a href="#user-terms">{{ __('1. User waiver terms') }}</a></li>
                    <li><a href="#booking-rules">{{ __('2. Booking & cancellation rules') }}</a></li>
                    <li><a href="#property-conduct">{{ __('3. Property & conduct rules') }}</a></li>
                    <li><a href="#guest-liability">{{ __('4. Guest & damage liability') }}</a></li>
                    <li><a href="#platform-release">{{ __('5. Platform liability release') }}</a></li>
                    <li><a href="#nda">{{ __('6. NDA summary (PDF)') }}</a></li>
                    <li><a href="#non-compete">{{ __('7. Non-compete summary (PDF)') }}</a></li>
                    <li><a href="#contact">{{ __('8. Contact') }}</a></li>
                </ul>
            </aside>

            {{-- Content --}}
            <div class="legal-card" data-aos="fade-up">
                <div class="legal-meta-bar">
                    <span class="legal-meta-chip"><i class="fa-solid fa-file-signature"></i> {{ __('User agreement') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-shield-halved"></i> {{ __('Binding') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-language"></i> {{ __('English (US)') }}</span>
                </div>

                <div class="legal-prose">

                    <section class="legal-section" id="user-terms">
                        <h2 class="legal-section__heading"><span class="legal-section__num">1</span> {{ __('User waiver terms') }}</h2>
                        <p>
                            {{ __('All users agree to the following conditions while using SPOTMEE and any host-provided workout space:') }}
                        </p>
                        <ol>
                            <li>{{ __('All users agree that they will not take any legal action, nor hold SPOTMEE responsible for any injury, incident, death, or negative experience while using SPOTMEE, while working out, or at any time after.') }}</li>
                            <li>{{ __('All users agree that their booked appointment is an agreement between them and the host to treat all equipment, space, home, location, and property belonging to the host responsibly and courteously.') }}</li>
                            <li>{{ __('Users agree to take full responsibility and not take any legal action before, during, or after against SPOTMEE for any injury, incident, death, or negative experience while using SPOTMEE.') }}</li>
                            <li>{{ __('All users agree that all hosts may use the user’s name and likeness to leave reviews and ratings about the user, based on experience, cleanliness, courtesy, communication, use of equipment, use of space, conditions of space before and after, and anything else related to use of the host space.') }}</li>
                        </ol>

                        <div class="legal-callout">
                            <span class="legal-callout__icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                            <div class="legal-callout__body">
                                <p>
                                    <strong>{{ __('Binding acknowledgment.') }}</strong>
                                    {{ __('By booking or using SPOTMEE, users confirm that these waiver terms are accepted and enforceable.') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="legal-section" id="booking-rules">
                        <h2 class="legal-section__heading"><span class="legal-section__num">2</span> {{ __('Booking & cancellation rules') }}</h2>
                        <p>
                            {{ __('All users agree that their booked time slot will be held only for the amount of time booked. No user may stay longer or start earlier than their scheduled booking.') }}
                        </p>
                        <p>
                            {{ __('If any user refuses, for any reason, to leave the workout space provided by the host, this will result in a permanent ban from SPOTMEE.') }}
                        </p>
                        <p>
                            {{ __('All users agree that if they do not cancel their scheduled booking at least 24 hours before their booked session, they will be locked in and unable to cancel or receive a refund.') }}
                        </p>
                    </section>

                    <section class="legal-section" id="property-conduct">
                        <h2 class="legal-section__heading"><span class="legal-section__num">3</span> {{ __('Property & conduct rules') }}</h2>
                        <ul>
                            <li>{{ __('All users agree that while using SPOTMEE they will not bring any unauthorized guests, including any person or animal who has not booked the appointment or is not authorized to use the space.') }}</li>
                            <li>{{ __('Users agree to use host equipment and property responsibly and courteously at all times.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="guest-liability">
                        <h2 class="legal-section__heading"><span class="legal-section__num">4</span> {{ __('Guest & damage liability') }}</h2>
                        <p>
                            {{ __('All users agree that when booking the pairs option and using the space with another person, the user is legally and financially responsible for any damages, destruction of property, injuries, or experiences of themselves and their guest.') }}
                        </p>
                        <ul>
                            <li>{{ __('The user will take full legal and financial responsibility for their guest and will not take legal action toward SPOTMEE before, during, or after using SPOTMEE or while occupying the host’s space.') }}</li>
                            <li>{{ __('All users agree that they will be held liable for any damages caused by themselves or anyone they bring, authorized or not.') }}</li>
                            <li>{{ __('The user agrees they will take full legal and financial responsibility for any incidents, injuries, death, or experiences that lead to compensation of any kind, and SPOTMEE will not be held legally or financially responsible.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="platform-release">
                        <h2 class="legal-section__heading"><span class="legal-section__num">5</span> {{ __('Platform liability release') }}</h2>
                        <p>
                            {{ __('All users agree that SPOTMEE will not be held liable or responsible for any reason, and all legal or financial action is hereby forfeited by the subscribed user for any reason, at any time before, during, or after using or being subscribed to SPOTMEE, for any incidents, injuries, complaints, or death.') }}
                        </p>
                    </section>

                    <section class="legal-section" id="nda">
                        <h2 class="legal-section__heading"><span class="legal-section__num">6</span> {{ __('Standard Non-disclosure Agreement (PDF content)') }}</h2>
                        <p>
                            {{ __('The Standard NDA identifies SPOTMEE and the signer, defines confidential information, and requires non-disclosure of private company and operational details.') }}
                        </p>
                        <ul>
                            <li>{{ __('Confidential information includes non-public business information such as vendors, pricing, products, and technology.') }}</li>
                            <li>{{ __('Disclosure to third parties requires prior written consent from SPOTMEE.') }}</li>
                            <li>{{ __('Unauthorized disclosure permits SPOTMEE to seek legal and equitable remedies.') }}</li>
                            <li>{{ __('Agreement includes non-circumvention language and return/destruction of confidential materials after termination.') }}</li>
                            <li>{{ __('Governing law is California; indemnification obligations are included.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="non-compete">
                        <h2 class="legal-section__heading"><span class="legal-section__num">7</span> {{ __('Non-compete Agreement (PDF content)') }}</h2>
                        <p>
                            {{ __('The Non-compete Agreement outlines restrictions against competitive activity and non-solicitation, alongside enforcement rights and governing law.') }}
                        </p>
                        <ul>
                            <li>{{ __('Employee/contracted party agrees not to engage in competing business activity during the agreement term and defined non-compete period.') }}</li>
                            <li>{{ __('Includes non-solicitation restrictions related to SPOTMEE personnel.') }}</li>
                            <li>{{ __('Company may seek injunctive relief where monetary damages are inadequate.') }}</li>
                            <li>{{ __('Includes severability, amendment, waiver, and entire agreement clauses.') }}</li>
                            <li>{{ __('Governing law and jurisdiction are California.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="contact">
                        <h2 class="legal-section__heading"><span class="legal-section__num">8</span> {{ __('Contact') }}</h2>
                        <p>
                            {{ __('Questions about this Waiver? Email us at') }}
                            <a href="mailto:legal@spotmee.com" class="font-semibold text-[var(--color-primary)] hover:underline">legal@spotmee.com</a>
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
