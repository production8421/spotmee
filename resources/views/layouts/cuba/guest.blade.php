<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cuba.partials.head')
<body>
    @yield('content')
    @include('cuba.partials.scripts-guest')
</body>
</html>
