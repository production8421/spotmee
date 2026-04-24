@php
    /**
     * Header — Spotmee
     * Add / remove / reorder nav items in the $navLinks array below.
     * The whole header is driven by tokens in resources/css/app.css,
     * so colour / font / radius tweaks happen in one place.
     */
    $settings      = $settings ?? \App\Models\ApplicationSetting::instance();
    $headerLogoUrl = $settings->displayHeaderLogoUrl();
    $user          = auth()->user();
    $current       = \Illuminate\Support\Facades\Route::currentRouteName();

    $isActive = fn (string ...$routes) => in_array($current, $routes, true)
        || collect($routes)->contains(fn ($r) => str_starts_with((string) $current, $r . '.'));

    $navLinks = [
        ['label' => __('Home'),           'href' => route('home'),           'active' => $isActive('home')],
        ['label' => __('How It Works'),   'href' => route('how-it-works'),   'active' => $isActive('how-it-works')],
        ['label' => __('Find a Gym'),     'href' => route('find-a-gym'),     'active' => $isActive('find-a-gym')],
        ['label' => __('Become a Host'),  'href' => route('become-a-host'),  'active' => $isActive('become-a-host', 'host.apply')],
        ['label' => __('About Us'),       'href' => route('about'),          'active' => $isActive('about')],
        ['label' => __('FAQ'),            'href' => route('faq'),            'active' => $isActive('faq')],
        ['label' => __('Contact'),        'href' => route('contact'),        'active' => $isActive('contact')],
    ];

    $userInitial = $user ? mb_strtoupper(mb_substr($user->name ?? $user->email ?? 'U', 0, 1)) : null;
@endphp

<header id="siteHeader" class="site-header" role="banner">
    <div class="site-container">
        <div class="site-header__inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="site-logo inline-flex shrink-0 items-center" aria-label="{{ config('app.name') }} home">
                <img src="{{ $headerLogoUrl }}" alt="{{ config('app.name') }}">
            </a>

            {{-- Desktop nav --}}
            <nav class="site-nav" aria-label="Primary">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="site-nav-link {{ $link['active'] ? 'is-active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Desktop actions --}}
            <div class="hidden items-center gap-3 lg:flex">
                @if ($user)
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-gauge-high text-[12px]"></i>
                        {{ __('Dashboard') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm" aria-label="{{ __('Logout') }}">
                            {{ __('Logout') }}
                        </button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="site-avatar" aria-label="{{ $user->name ?? $user->email }}">
                        {{ $userInitial }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">{{ __('Login') }}</a>
                    <a href="{{ route('host.apply') }}" class="btn btn-primary btn-sm">
                        {{ __('Sign Up') }}
                        <i class="fa-solid fa-arrow-right text-[12px]"></i>
                    </a>
                @endif
            </div>

            {{-- Mobile hamburger --}}
            <button type="button"
                    class="site-hamburger"
                    data-drawer-open
                    aria-label="{{ __('Open menu') }}"
                    aria-controls="mobileDrawer"
                    aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="h-5 w-5">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Drawer outside <header>: fixed overlay must stack vs <main>, not inside the header z-50 subtree --}}
<div id="mobileDrawer" class="mobile-drawer" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="mobile-drawer__backdrop" data-drawer-close></div>

        <aside class="mobile-drawer__panel">
            <div class="flex min-w-0 items-center justify-between gap-3 border-b border-[var(--color-ink-100)] px-4 py-3 sm:px-5 sm:py-4">
                <a href="{{ route('home') }}" class="site-logo inline-flex min-w-0 items-center" aria-label="{{ config('app.name') }}">
                    <img src="{{ $headerLogoUrl }}" alt="{{ config('app.name') }}">
                </a>
                <button type="button" class="site-hamburger shrink-0" data-drawer-close aria-label="{{ __('Close menu') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="h-5 w-5">
                        <line x1="6" y1="6" x2="18" y2="18"/>
                        <line x1="18" y1="6" x2="6" y2="18"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 min-w-0 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-4" aria-label="Mobile">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="mobile-nav-link {{ $link['active'] ? 'is-active' : '' }}">
                        <span>{{ $link['label'] }}</span>
                        @if ($link['active'])
                            <i class="fa-solid fa-circle text-[12px] text-[var(--color-primary)]"></i>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="min-w-0 shrink-0 space-y-2 border-t border-[var(--color-ink-100)] p-4 box-border">
                @if ($user)
                    <div class="mb-2 flex min-w-0 items-center gap-3 rounded-2xl bg-[var(--color-brand-50)] p-3">
                        <span class="site-avatar">{{ $userInitial }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-[14px] font-semibold text-[var(--color-ink-900)]">
                                {{ $user->name ?? __('Welcome') }}
                            </p>
                            <p class="truncate text-[12px] text-[var(--color-ink-500)]">{{ $user->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline w-full">
                        <i class="fa-solid fa-gauge-high text-[12px]"></i> {{ __('Dashboard') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full min-w-0">
                        @csrf
                        <button type="submit" class="btn btn-soft w-full">{{ __('Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline w-full">{{ __('Login') }}</a>
                    <a href="{{ route('host.apply') }}" class="btn btn-primary w-full">
                        {{ __('Sign Up') }}
                        <i class="fa-solid fa-arrow-right text-[12px]"></i>
                    </a>
                @endif
            </div>
        </aside>
</div>

<script>
    (function () {
        'use strict';

        const header  = document.getElementById('siteHeader');
        const drawer  = document.getElementById('mobileDrawer');
        const openBtn = document.querySelector('[data-drawer-open]');
        const closers = document.querySelectorAll('[data-drawer-close]');

        /* ---- Sticky header shadow on scroll ---- */
        const onScroll = () => {
            header.classList.toggle('site-header--scrolled', window.scrollY > 8);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        /* ---- Mobile drawer ---- */
        const openDrawer = () => {
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            openBtn?.setAttribute('aria-expanded', 'true');
            document.body.classList.add('is-drawer-open');
        };
        const closeDrawer = () => {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            openBtn?.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('is-drawer-open');
        };

        openBtn?.addEventListener('click', openDrawer);
        closers.forEach(el => el.addEventListener('click', closeDrawer));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) closeDrawer();
        });

        /* Close drawer when resizing up past lg breakpoint */
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeDrawer();
        });
    })();
</script>
