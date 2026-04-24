@php
    $href = \App\Models\ApplicationSetting::instance()->displayHeaderLogoUrl();
    $path = parse_url($href, PHP_URL_PATH) ?? '';
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $type = match ($ext) {
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'jpg', 'jpeg' => 'image/jpeg',
        default => null,
    };
@endphp
@if ($type)
    <link rel="icon" type="{{ $type }}" href="{{ $href }}" sizes="any">
@else
    <link rel="icon" href="{{ $href }}" sizes="any">
@endif
<link rel="apple-touch-icon" href="{{ $href }}">
