{{-- $stroke: sprite id e.g. stroke-ecommerce; optional $sprite, $svgClass, $wrapClass --}}
@php
    $sprite = $sprite ?? asset(config('cuba.assets_path').'/svg/icon-sprite.svg');
    $svgClass = $svgClass ?? 'text-primary';
    $wrapClass = $wrapClass ?? 'bg-light';
@endphp
{{-- Cuba sprite paths rely on inherited stroke; sidebar/breadcrumb set stroke in CSS — main content does not, so force currentColor. --}}
<span class="d-inline-flex align-items-center justify-content-center rounded-2 flex-shrink-0 {{ $wrapClass }}"
    style="width: 2.375rem; height: 2.375rem;"
    aria-hidden="true">
    <svg class="stroke-icon {{ $svgClass }} flex-shrink-0" fill="none" style="width: 1.25rem; height: 1.25rem; stroke: currentColor;">
        <use href="{{ $sprite }}#{{ $stroke }}"></use>
    </svg>
</span>
