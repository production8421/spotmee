<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cuba.partials.head')
<body>
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"></fecolormatrix>
            </filter>
        </svg>
    </div>
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('cuba.partials.header')

        <div class="page-body-wrapper horizontal-menu">
            @include('cuba.partials.sidebar')

            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        @yield('page_header')
                    </div>
                </div>
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            @include('cuba.partials.footer')
        </div>
    </div>

    @include('cuba.partials.scripts')
    @stack('scripts')
</body>
</html>
