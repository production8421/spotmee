<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SPOTMEE')</title>

    {{--  Fonts · single family for easy swap (change in one place)  --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    {{--  Icons  --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{--  Animations (used by inner pages)  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
        integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{--  Carousels (used by home + gym pages)  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"
        integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"
        integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @viteReactRefresh
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body class="{{ request()->routeIs('home') ? 'home-page' : 'inner-page' }}">
    @include('layouts.web.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.web.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
        integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        (function () {
            if (typeof AOS === 'undefined') return;

            AOS.init({
                once: true,
                duration: 700,
                easing: 'ease-out-cubic',
                offset: 40,
                startEvent: 'DOMContentLoaded',
                disableMutationObserver: false,
            });

            // AOS calculates element positions once on init. If images/fonts
            // finish loading after that, element offsets shift and AOS will
            // never trigger — leaving blocks invisible until the user resizes
            // the window. Refresh when the page is fully ready + on image loads.
            function refreshAos() {
                try { AOS.refreshHard(); } catch (e) {}
            }

            if (document.readyState === 'complete') {
                refreshAos();
            } else {
                window.addEventListener('load', refreshAos);
            }

            // Refresh again once all <img> tags have finished loading (covers
            // lazy/heavy images that may push content down after page load).
            window.addEventListener('load', function () {
                document.querySelectorAll('img').forEach(function (img) {
                    if (!img.complete) {
                        img.addEventListener('load',  refreshAos, { once: true });
                        img.addEventListener('error', refreshAos, { once: true });
                    }
                });
            });

            // Refresh when web fonts finish loading (Plus Jakarta Sans can
            // shift baselines after swap).
            if (document.fonts && typeof document.fonts.ready?.then === 'function') {
                document.fonts.ready.then(refreshAos).catch(function () {});
            }

            // Final safety net: force a refresh 400ms after load in case
            // anything else (Slick carousels, custom widgets) shifted layout.
            window.addEventListener('load', function () {
                setTimeout(refreshAos, 400);
            });
        })();
    </script>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
