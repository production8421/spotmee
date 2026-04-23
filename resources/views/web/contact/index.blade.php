@extends('layouts.web.master')

@section('title', 'Contact Us — SPOTMEE')

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
                    <i class="fa-solid fa-headset"></i>
                    {{ __('We\'re here to help') }}
                </span>
                <h1 class="inner-banner__title" data-aos="fade-down" data-aos-delay="100">
                    {{ __('Get in') }}
                    <span class="text-[var(--color-brand-200)]">{{ __('Touch') }}</span>
                </h1>
                <p class="inner-banner__subtitle" data-aos="fade-up" data-aos-delay="200">
                    {{ __('Questions about finding a gym, becoming a host, or anything in between — our SPOTMEE team is ready to help.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Quick contact cards
    ===================================================================== --}}
    <section class="site-container mt-10 sm:mt-14">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            {{-- Email --}}
            <a href="mailto:support@spotmee.com"
               class="group relative overflow-hidden rounded-[22px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] transition-all hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
               data-aos="fade-up">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                    <i class="fa-solid fa-envelope text-lg"></i>
                </span>
                <h3 class="mt-4 text-[15px] font-bold uppercase tracking-[0.08em] text-[var(--color-ink-900)]">
                    {{ __('Email us') }}
                </h3>
                <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('General inquiries & support') }}</p>
                <p class="mt-3 text-[16px] font-bold text-[var(--color-primary)] group-hover:underline">
                    support@spotmee.com
                </p>
            </a>

            {{-- Phone --}}
            <a href="tel:+15551234567"
               class="group relative overflow-hidden rounded-[22px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] transition-all hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
               data-aos="fade-up" data-aos-delay="100">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                    <i class="fa-solid fa-phone text-lg"></i>
                </span>
                <h3 class="mt-4 text-[15px] font-bold uppercase tracking-[0.08em] text-[var(--color-ink-900)]">
                    {{ __('Call us') }}
                </h3>
                <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Mon–Fri, 8am – 5pm CT') }}</p>
                <p class="mt-3 text-[16px] font-bold text-[var(--color-primary)] group-hover:underline">
                    +1 (555) 123-4567
                </p>
            </a>

            {{-- Location --}}
            <div class="group relative overflow-hidden rounded-[22px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] transition-all hover:-translate-y-1 hover:border-[var(--color-brand-200)] hover:shadow-[var(--shadow-lg)]"
                 data-aos="fade-up" data-aos-delay="200">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-primary)] transition-colors group-hover:bg-[var(--color-primary)] group-hover:text-white">
                    <i class="fa-solid fa-location-dot text-lg"></i>
                </span>
                <h3 class="mt-4 text-[15px] font-bold uppercase tracking-[0.08em] text-[var(--color-ink-900)]">
                    {{ __('Visit us') }}
                </h3>
                <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Our HQ is based in Austin') }}</p>
                <p class="mt-3 text-[15px] font-semibold leading-snug text-[var(--color-ink-900)]">
                    123 Fitness Blvd, Suite 100<br>Austin, TX 78701
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         Main content: Info + Form
    ===================================================================== --}}
    <section class="site-container py-14 sm:py-20">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-14">

            {{-- Left: Info & Support --}}
            <div class="lg:col-span-5" data-aos="fade-right">
                <span class="eyebrow">{{ __('Say hi') }}</span>
                <h2 class="mt-3 font-bold leading-[1.1] text-[var(--color-ink-900)]"
                    style="font-size: var(--text-h2);">
                    {{ __('Let\'s') }}
                    <span class="text-[var(--color-primary)]">{{ __('Talk') }}</span>
                </h2>
                <p class="mt-4 text-[15px] leading-relaxed text-[var(--color-ink-500)]">
                    {{ __('Whether you\'re looking for a private space to train, have a home gym to list, or just want to learn more about SPOTMEE — drop us a note and we\'ll reply within one business day.') }}
                </p>

                {{-- Quick response bullets --}}
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start gap-4 rounded-2xl border border-[var(--color-brand-100)] bg-white p-4 shadow-[var(--shadow-sm)]">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                            <i class="fa-solid fa-bolt"></i>
                        </span>
                        <div>
                            <p class="text-[14px] font-bold text-[var(--color-ink-900)]">
                                {{ __('Fast response') }}
                            </p>
                            <p class="text-[13px] text-[var(--color-ink-500)]">
                                {{ __('We aim to reply within one business day.') }}
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 rounded-2xl border border-[var(--color-brand-100)] bg-white p-4 shadow-[var(--shadow-sm)]">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                            <i class="fa-solid fa-user-shield"></i>
                        </span>
                        <div>
                            <p class="text-[14px] font-bold text-[var(--color-ink-900)]">
                                {{ __('Real humans') }}
                            </p>
                            <p class="text-[13px] text-[var(--color-ink-500)]">
                                {{ __('No bots — your message goes straight to the team.') }}
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 rounded-2xl border border-[var(--color-brand-100)] bg-white p-4 shadow-[var(--shadow-sm)]">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-primary)]">
                                <i class="fa-solid fa-circle-question"></i>
                        </span>
                        <div>
                            <p class="text-[14px] font-bold text-[var(--color-ink-900)]">
                                {{ __('Common questions') }}
                            </p>
                            <p class="text-[13px] text-[var(--color-ink-500)]">
                                {{ __('Find quick answers on our FAQ page before reaching out.') }}
                                <a href="{{ route('faq') }}" class="font-semibold text-[var(--color-primary)] hover:underline">
                                    {{ __('View FAQ') }} <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </a>
                            </p>
                        </div>
                    </li>
                </ul>

                {{-- Socials --}}
                @php
                    $contactSettings = $settings ?? \App\Models\ApplicationSetting::instance();
                    $socialLinks = $contactSettings ? $contactSettings->footerSocialLinksForPublic() : [];
                    $socialIcons = [
                        'facebook'  => 'fa-brands fa-facebook-f',
                        'twitter'   => 'fa-brands fa-x-twitter',
                        'x'         => 'fa-brands fa-x-twitter',
                        'instagram' => 'fa-brands fa-instagram',
                        'linkedin'  => 'fa-brands fa-linkedin-in',
                        'youtube'   => 'fa-brands fa-youtube',
                        'tiktok'    => 'fa-brands fa-tiktok',
                    ];
                @endphp
                <div class="mt-10">
                    <p class="mb-4 text-[13px] font-bold uppercase tracking-[0.12em] text-[var(--color-ink-500)]">
                        {{ __('Follow us') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @forelse ($socialLinks as $social)
                            @php
                                $key  = strtolower((string) ($social['platform'] ?? ''));
                                $icon = $socialIcons[$key] ?? 'fa-solid fa-globe';
                                $href = $social['href'] ?? '#';
                                $label = $social['label'] ?? ($social['platform'] ?? 'social');
                            @endphp
                            <a href="{{ $href }}" target="_blank" rel="noopener"
                               class="flex h-11 w-11 items-center justify-center rounded-full border border-[var(--color-brand-100)] bg-white text-[var(--color-primary)] transition-all hover:-translate-y-0.5 hover:border-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-white"
                               aria-label="{{ $label }}">
                                <i class="{{ $icon }}"></i>
                            </a>
                        @empty
                            {{-- Default placeholders --}}
                            @foreach (['facebook-f', 'instagram', 'x-twitter', 'linkedin-in'] as $brand)
                                <a href="#"
                                   class="flex h-11 w-11 items-center justify-center rounded-full border border-[var(--color-brand-100)] bg-white text-[var(--color-primary)] transition-all hover:-translate-y-0.5 hover:border-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-white">
                                    <i class="fa-brands fa-{{ $brand }}"></i>
                                </a>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="lg:col-span-7" data-aos="fade-left">
                <div class="relative overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-lg)] sm:p-10">
                    {{-- Decorative blob --}}
                    <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[var(--color-brand-50)]"></div>
                    <div class="pointer-events-none absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-[var(--color-brand-50)]/70"></div>

                    <div class="relative">
                        <span class="eyebrow">{{ __('Contact form') }}</span>
                        <h3 class="mt-2 text-[22px] font-bold text-[var(--color-ink-900)] sm:text-[26px]">
                            {{ __('Send us a message') }}
                        </h3>
                        <p class="mt-2 text-[14px] text-[var(--color-ink-500)]">
                            {{ __('Fill out the form below and we\'ll get back to you shortly.') }}
                        </p>

                        @if (session('status'))
                            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-[14px] text-green-800">
                                <i class="fa-solid fa-circle-check mt-0.5"></i>
                                <p>{{ session('status') }}</p>
                            </div>
                        @endif
                        @if ($errors->has('contact'))
                            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-[14px] text-red-800">
                                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                                <p>{{ $errors->first('contact') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="mt-8 space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="contact-label">
                                        {{ __('Full name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="contact-field">
                                        <i class="fa-solid fa-user contact-field__icon"></i>
                                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                                               placeholder="{{ __('Jane Doe') }}"
                                               class="contact-input @error('name') is-invalid @enderror">
                                    </div>
                                    @error('name')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-[12px] text-red-600">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="contact-label">
                                        {{ __('Email address') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="contact-field">
                                        <i class="fa-solid fa-envelope contact-field__icon"></i>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                                               placeholder="you@example.com"
                                               class="contact-input @error('email') is-invalid @enderror">
                                    </div>
                                    @error('email')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-[12px] text-red-600">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="contact-label">{{ __('Phone') }}</label>
                                    <div class="contact-field">
                                        <i class="fa-solid fa-phone contact-field__icon"></i>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                               placeholder="+1 (555) 123-4567"
                                               class="contact-input @error('phone') is-invalid @enderror">
                                    </div>
                                    @error('phone')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-[12px] text-red-600">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="company" class="contact-label">{{ __('Company') }}</label>
                                    <div class="contact-field">
                                        <i class="fa-solid fa-building contact-field__icon"></i>
                                        <input id="company" type="text" name="company" value="{{ old('company') }}"
                                               placeholder="{{ __('Optional') }}"
                                               class="contact-input @error('company') is-invalid @enderror">
                                    </div>
                                    @error('company')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-[12px] text-red-600">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="message" class="contact-label">
                                    {{ __('Your message') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="contact-field contact-field--textarea">
                                    <i class="fa-solid fa-comment-dots contact-field__icon"></i>
                                    <textarea id="message" rows="5" name="message"
                                              placeholder="{{ __('Tell us a bit about what you need…') }}"
                                              class="contact-input contact-input--textarea @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                </div>
                                @error('message')
                                    <p class="mt-1.5 flex items-center gap-1.5 text-[12px] text-red-600">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex flex-col items-start gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                <p class="flex items-center gap-2 text-[12px] text-[var(--color-ink-500)]">
                                    <i class="fa-solid fa-lock"></i>
                                    {{ __('Your details are kept private and never shared.') }}
                                </p>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Send message') }}
                                    <i class="fa-solid fa-paper-plane text-[13px]"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         "Prefer self-serve?" CTA strip
    ===================================================================== --}}
    <section class="site-container pb-20 sm:pb-28">
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[var(--color-primary)] via-[var(--color-brand-600)] to-[var(--color-brand-500)] px-6 py-12 text-white shadow-[var(--shadow-lg)] sm:px-12 sm:py-14"
             data-aos="fade-up">
            <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -left-16 -bottom-16 h-56 w-56 rounded-full bg-white/5"></div>

            <div class="relative grid grid-cols-1 items-center gap-8 md:grid-cols-12">
                <div class="md:col-span-8">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-[12px] font-semibold uppercase tracking-[0.12em] backdrop-blur">
                        <i class="fa-solid fa-lightbulb text-[11px]"></i>
                        {{ __('Prefer self-serve?') }}
                    </span>
                    <h3 class="mt-4 text-[24px] font-bold leading-tight sm:text-[30px]">
                        {{ __('Start exploring SPOTMEE today') }}
                    </h3>
                    <p class="mt-3 max-w-2xl text-[15px] text-white/85">
                        {{ __('Browse private gyms near you, or list your own space and start earning on your schedule — no long-term contracts, ever.') }}
                    </p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row md:col-span-4 md:justify-end">
                    <a href="{{ route('find-a-gym') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-[14px] font-bold text-[var(--color-primary)] shadow-[var(--shadow-sm)] transition hover:-translate-y-0.5 hover:shadow-[var(--shadow-lg)]">
                        <i class="fa-solid fa-magnifying-glass text-[13px]"></i>
                        {{ __('Find a Gym') }}
                    </a>
                    <a href="{{ route('become-a-host') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/60 bg-transparent px-5 py-3 text-[14px] font-bold text-white transition hover:bg-white hover:text-[var(--color-primary)]">
                        {{ __('Become a Host') }}
                        <i class="fa-solid fa-arrow-right text-[13px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact-specific styles (scoped via .contact-* classes) --}}
    <style>
        .contact-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--color-ink-700);
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .contact-field {
            position: relative;
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 1px solid var(--color-brand-100);
            border-radius: 14px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }
        .contact-field:hover { border-color: var(--color-brand-200); }
        .contact-field:focus-within {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(0, 109, 119, 0.12);
        }

        .contact-field__icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-brand-500);
            font-size: 14px;
            pointer-events: none;
        }

        .contact-field--textarea .contact-field__icon {
            top: 18px;
            transform: none;
        }

        .contact-input {
            width: 100%;
            border: 0;
            background: transparent;
            padding: 14px 16px 14px 44px;
            font-size: 14px;
            color: var(--color-ink-900);
            border-radius: 14px;
        }
        .contact-input::placeholder { color: var(--color-ink-400); }
        .contact-input:focus { outline: none; box-shadow: none; }

        .contact-input--textarea {
            padding-top: 16px;
            padding-bottom: 16px;
            resize: vertical;
            min-height: 130px;
        }

        .contact-input.is-invalid,
        .contact-field:has(.is-invalid) {
            border-color: #fca5a5;
        }
        .contact-field:has(.is-invalid):focus-within {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
        }
    </style>
@endsection
