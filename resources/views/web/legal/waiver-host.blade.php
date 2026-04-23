@extends('layouts.web.master')

@section('title', 'Waiver of Liability (Host) — SPOTMEE')

@section('content')
    @include('web.legal.partials.hero', [
        'heroPrefix'   => 'waiver_liability_host',
        'heroEyebrow'  => __('Legal · Hosts'),
        'heroSubtitle' => __('The commitments, risks, and protections every Host agrees to when listing a space on the SPOTMEE platform.'),
        'lastUpdated'  => 'April 23, 2026',
    ])

    <section class="site-container py-14 sm:py-20">
        <div class="legal-layout">

            {{-- TOC --}}
            <aside class="legal-toc" data-aos="fade-right">
                <p class="legal-toc__title">{{ __('On this page') }}</p>
                <ul class="legal-toc__list" data-legal-toc>
                    <li><a href="#host-terms">{{ __('1. Host waiver terms') }}</a></li>
                    <li><a href="#service-standards">{{ __('2. Service standards') }}</a></li>
                    <li><a href="#space-standards">{{ __('3. Space standards') }}</a></li>
                    <li><a href="#booking-standards">{{ __('4. Booking standards') }}</a></li>
                    <li><a href="#liability-release">{{ __('5. Liability release') }}</a></li>
                    <li><a href="#nda">{{ __('6. NDA summary (PDF)') }}</a></li>
                    <li><a href="#contractor">{{ __('7. Contractor agreement (PDF)') }}</a></li>
                    <li><a href="#non-compete">{{ __('8. Non-compete summary (PDF)') }}</a></li>
                    <li><a href="#contact">{{ __('9. Contact') }}</a></li>
                </ul>
            </aside>

            {{-- Content --}}
            <div class="legal-card" data-aos="fade-up">
                <div class="legal-meta-bar">
                    <span class="legal-meta-chip"><i class="fa-solid fa-file-signature"></i> {{ __('Host agreement') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-shield-halved"></i> {{ __('Binding') }}</span>
                    <span class="legal-meta-chip"><i class="fa-solid fa-language"></i> {{ __('English (US)') }}</span>
                </div>

                <div class="legal-prose">

                    <section class="legal-section" id="host-terms">
                        <h2 class="legal-section__heading"><span class="legal-section__num">1</span> {{ __('Host waiver terms') }}</h2>
                        <p>
                            {{ __('All hosts agree to the following conditions as part of their SPOTMEE host waiver and service obligations:') }}
                        </p>
                        <ol>
                            <li>{{ __('All hosts agree to take full responsibility for any injury that takes place while users are at their gym.') }}</li>
                            <li>{{ __('All hosts agree to provide a safe space free from clutter, sharp objects, trash, hazardous chemicals, gases and smells, rodents, bugs, insects, dogs, cats, and anything that may interrupt, harm, or disrupt users.') }}</li>
                            <li>{{ __('All hosts agree that if a user is interrupted or unable to work out for any reason caused by the host, a full refund will be given at the host’s expense. This will not affect the user or SPOTMEE, only the host.') }}</li>
                            <li>{{ __('All hosts agree they will not hold SPOTMEE liable and will take full responsibility for any injuries, incidents, death, or negative experiences.') }}</li>
                            <li>{{ __('All hosts agree that no legal action will be taken against SPOTMEE for any injuries, incidents, death, or negative experiences.') }}</li>
                            <li>{{ __('All hosts agree that SPOTMEE will not be held liable, and all legal or financial action is forfeited by the host at any time before, during, or after being subscribed.') }}</li>
                        </ol>

                        <div class="legal-callout">
                            <span class="legal-callout__icon"><i class="fa-solid fa-circle-info"></i></span>
                            <div class="legal-callout__body">
                                <p>
                                    <strong>{{ __('Binding acknowledgment.') }}</strong>
                                    {{ __('By continuing as a Host on SPOTMEE, the Host confirms acceptance of these waiver terms and related platform standards.') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="legal-section" id="service-standards">
                        <h2 class="legal-section__heading"><span class="legal-section__num">2</span> {{ __('Service standards') }}</h2>
                        <p>
                            {{ __('All hosts agree to be courteous, friendly, and accommodating. This includes but is not limited to: giving users access to their workout space, sending a friendly message before and after workouts, having 100% of listed equipment working, and providing a towel and bottled water for each user at the start of each visit.') }}
                        </p>
                        <ul>
                            <li>{{ __('Hosts agree to a rating system where users may rate the host from 1–5 and leave reviews about cleanliness, equipment, access, communication, safety, and experience.') }}</li>
                            <li>{{ __('Hosts agree to a no-contact model and will not approach, harass, talk to, or touch users unless in an emergency.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="space-standards">
                        <h2 class="legal-section__heading"><span class="legal-section__num">3</span> {{ __('Space standards') }}</h2>
                        <p>
                            {{ __('All hosts agree to have temperature control in the workout space, including heaters, central heating and air, A/C units, fans, or anything safe that does not cause harm.') }}
                        </p>
                        <ul>
                            <li>{{ __('All hosts agree to set up their space to resemble a gym, including clean floors, dust-free space, and no boxes, storage items, or clutter in or around the workout area.') }}</li>
                            <li>{{ __('All hosts agree that the workout area must be at least 300 sq ft with clear, hazard-free access at all times.') }}</li>
                            <li>{{ __('All hosts agree that for pairs bookings, the workout space must be at least 400 sq ft with clear, hazard-free access.') }}</li>
                            <li>{{ __('All hosts agree that for group bookings, the space must provide at least 150 sq ft per person for up to 6 people, with hazard-free entry and exit, no pets, children, clutter, or extra people.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="booking-standards">
                        <h2 class="legal-section__heading"><span class="legal-section__num">4</span> {{ __('Booking standards') }}</h2>
                        <ul>
                            <li>{{ __('All hosts agree that users may book as many time slots as they choose, including consecutive bookings with upfront payment.') }}</li>
                            <li>{{ __('All hosts agree that for all pairs bookings, each guest will receive a water bottle and towel. If not provided, a 50% refund will be given to each guest.') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="liability-release">
                        <h2 class="legal-section__heading"><span class="legal-section__num">5</span> {{ __('Liability release') }}</h2>
                        <p>
                            {{ __('All hosts agree they are solely responsible for incidents connected to their space and operation. Hosts expressly waive claims against SPOTMEE relating to injury, incidents, death, or negative experiences before, during, or after subscription.') }}
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

                    <section class="legal-section" id="contractor">
                        <h2 class="legal-section__heading"><span class="legal-section__num">7</span> {{ __('Independent Contractor Agreement (PDF content)') }}</h2>
                        <p>
                            {{ __('The Independent Contractor Agreement defines the host/contractor relationship and confirms that the host provides private workout space services as an independent contractor, not as an employee.') }}
                        </p>
                        <ul>
                            <li>{{ __('Contractor must perform services safely, with adequate equipment, and in compliance with applicable law.') }}</li>
                            <li>{{ __('Contractor is responsible for taxes, tools, ordinary expenses, and independent insurance.') }}</li>
                            <li>{{ __('Company inventions/work product clauses assign service-created work rights to SPOTMEE.') }}</li>
                            <li>{{ __('Confidential information, return of property, and indemnification terms are included.') }}</li>
                            <li>{{ __('Governing law and forum are California (including Sacramento County courts).') }}</li>
                        </ul>
                    </section>

                    <section class="legal-section" id="non-compete">
                        <h2 class="legal-section__heading"><span class="legal-section__num">8</span> {{ __('Non-compete Agreement (PDF content)') }}</h2>
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
                        <h2 class="legal-section__heading"><span class="legal-section__num">9</span> {{ __('Contact') }}</h2>
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
        // Highlight the active TOC link as the user scrolls through the sections.
        (function () {
            const toc = document.querySelector('[data-legal-toc]');
            if (!toc) return;
            const links = Array.from(toc.querySelectorAll('a[href^="#"]'));
            const sections = links
                .map(l => document.getElementById(l.getAttribute('href').slice(1)))
                .filter(Boolean);
            if (!sections.length) return;

            const setActive = (id) => {
                links.forEach(l => {
                    l.classList.toggle('is-active', l.getAttribute('href') === '#' + id);
                });
            };

            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) setActive(e.target.id);
                });
            }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

            sections.forEach(s => io.observe(s));
        })();
    </script>
@endpush
