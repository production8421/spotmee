<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    @php
        $faviconHref = $applicationSetting->displayHeaderLogoUrl();
        $faviconPath = parse_url($faviconHref, PHP_URL_PATH) ?? '';
        $faviconExt = strtolower(pathinfo($faviconPath, PATHINFO_EXTENSION));
        $faviconType = match ($faviconExt) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            default => null,
        };
    @endphp
    @if ($faviconType)
        <link rel="icon" type="{{ $faviconType }}" href="{{ $faviconHref }}" sizes="any">
    @else
        <link rel="icon" href="{{ $faviconHref }}" sizes="any">
    @endif
    <link rel="apple-touch-icon" href="{{ $faviconHref }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/feather-icon.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/prism.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ $cubaAsset('css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ $cubaAsset('css/responsive.css') }}">
    @stack('styles')
  </head>
