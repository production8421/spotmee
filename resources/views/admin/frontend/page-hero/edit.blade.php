@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $sectionHeading }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-home"></use>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('admin.frontend.home') }}">{{ __('Frontend') }}</a></li>
                <li class="breadcrumb-item active">{{ $breadcrumbActive }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form id="frontend-page-hero-form" method="POST" action="{{ $updateUrl }}" @if (! empty($waiverPdfSections)) enctype="multipart/form-data" @endif novalidate>
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Hero') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ $heroHelp }}</p>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="{{ $prefix }}_hero_title">{{ __('Page title') }}</label>
                            <input
                                class="form-control @error($prefix.'_hero_title') is-invalid @enderror"
                                type="text"
                                name="{{ $prefix }}_hero_title"
                                id="{{ $prefix }}_hero_title"
                                value="{{ old($prefix.'_hero_title', $settings->getAttribute($prefix.'_hero_title')) }}"
                                maxlength="200"
                                placeholder="{{ $defaultHeroTitle }}"
                            >
                            @error($prefix.'_hero_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Leave empty to use the default title on the site.') }}</p>
                        </div>

                        <div class="mb-4 border rounded p-3 bg-light">
                            <label class="form-label fw-semibold" for="{{ $prefix }}_hero_background_color">{{ __('Background color') }}</label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <input
                                    class="form-control form-control-color @error($prefix.'_hero_background_color') is-invalid @enderror"
                                    type="color"
                                    name="{{ $prefix }}_hero_background_color"
                                    id="{{ $prefix }}_hero_background_color"
                                    value="{{ old($prefix.'_hero_background_color', $settings->getAttribute($prefix.'_hero_background_color') ?: '#2563eb') }}"
                                    title="{{ __('Pick a color') }}"
                                >
                                <input
                                    class="form-control @error($prefix.'_hero_background_color') is-invalid @enderror"
                                    type="text"
                                    id="{{ $prefix }}_hero_background_color_hex"
                                    value="{{ old($prefix.'_hero_background_color', $settings->getAttribute($prefix.'_hero_background_color') ?: '#2563eb') }}"
                                    pattern="^#[0-9A-Fa-f]{6}$"
                                    maxlength="7"
                                    style="max-width: 8rem;"
                                    aria-label="{{ __('Hex color') }}"
                                >
                            </div>
                            @error($prefix.'_hero_background_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Leave the hex as default or pick any color for the hero bar.') }}</p>
                        </div>

                        <div class="mb-0">
                            <div class="form-label fw-semibold">{{ __('Preview') }}</div>
                            <div
                                id="{{ $prefix }}_hero_preview"
                                class="rounded py-4 px-3 text-center text-white fw-semibold fs-5"
                                style="background-color: {{ $settings->frontendPageHeroBackgroundColorCss($prefix) }};"
                            >
                                <span id="{{ $prefix }}_hero_preview_title">{{ $settings->frontendPageHeroTitleDisplay($prefix) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @isset($faqItemRows)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('FAQ') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Questions and answers shown on the public FAQ page.') }}</p>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-semibold pb-2 mb-3 border-bottom">{{ __('Questions & answers') }}</h6>
                            <p class="text-muted small mb-2">{{ __('Question') }} — {{ __('Answer') }}</p>

                            <div id="faq-items-block">
                                <div id="faq-items-rows" class="mb-2">
                                    @foreach ($faqItemRows as $i => $row)
                                        <div class="row g-2 mb-2 align-items-start" data-faq-item-row>
                                            <div class="col-md-5">
                                                <label class="form-label small text-muted mb-0 d-md-none" for="faq_question_{{ $i }}">{{ __('Question') }}</label>
                                                <input
                                                    class="form-control @error('faq_items.'.$i.'.question') is-invalid @enderror"
                                                    type="text"
                                                    name="faq_items[{{ $i }}][question]"
                                                    id="faq_question_{{ $i }}"
                                                    value="{{ old('faq_items.'.$i.'.question', $row['question'] ?? '') }}"
                                                    maxlength="500"
                                                    placeholder="{{ __('Question') }}"
                                                >
                                                @error('faq_items.'.$i.'.question')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label small text-muted mb-0 d-md-none" for="faq_answer_{{ $i }}">{{ __('Answer') }}</label>
                                                <textarea
                                                    class="form-control @error('faq_items.'.$i.'.answer') is-invalid @enderror"
                                                    name="faq_items[{{ $i }}][answer]"
                                                    id="faq_answer_{{ $i }}"
                                                    rows="2"
                                                    maxlength="10000"
                                                    placeholder="{{ __('Answer') }}"
                                                >{{ old('faq_items.'.$i.'.answer', $row['answer'] ?? '') }}</textarea>
                                                @error('faq_items.'.$i.'.answer')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small text-muted mb-0 d-md-none">&nbsp;</label>
                                                <button class="btn btn-outline-primary btn-sm w-100" type="button" data-remove-faq-item>{{ __('Remove') }}</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('faq_items')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror

                                <button class="btn btn-outline-primary btn-sm mb-0" type="button" data-add-faq-item>+ {{ __('Add FAQ item') }}</button>
                            </div>

                            <template id="faq-item-row-template">
                                <div class="row g-2 mb-2 align-items-start" data-faq-item-row>
                                    <div class="col-md-5">
                                        <input class="form-control" type="text" name="faq_items[__INDEX__][question]" value="" maxlength="500" placeholder="{{ __('Question') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <textarea class="form-control" name="faq_items[__INDEX__][answer]" rows="2" maxlength="10000" placeholder="{{ __('Answer') }}"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary btn-sm w-100" type="button" data-remove-faq-item>{{ __('Remove') }}</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                @endisset

                @if (! empty($waiverPdfSections))
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $waiverPdfUploadHeading ?? __('Waiver PDF documents') }}</h5>
                            <p class="text-muted small mb-0 mt-1">
                                {{ $waiverPdfUploadHelp ?? __('Upload PDFs for the public waiver page. Max 20 MB each.') }}
                            </p>
                        </div>
                        <div class="card-body">
                            @foreach ($waiverPdfSections as $pdf)
                                <div class="border rounded p-3 mb-3 {{ $loop->last ? 'mb-0' : '' }}">
                                    <h6 class="fw-semibold mb-2">{{ $pdf['label'] }}</h6>
                                    @if ($pdf['url'])
                                        <p class="text-muted small mb-2">
                                            <i class="fa-solid fa-circle-check text-success me-1" aria-hidden="true"></i>
                                            {{ __('Current file:') }}
                                            <a href="{{ $pdf['url'] }}" target="_blank" rel="noopener noreferrer">{{ __('View PDF') }}</a>
                                        </p>
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="{{ $pdf['remove_name'] }}"
                                                id="{{ $pdf['remove_name'] }}"
                                                value="1"
                                                @checked(old($pdf['remove_name']))
                                            >
                                            <label class="form-check-label" for="{{ $pdf['remove_name'] }}">{{ __('Remove current PDF') }}</label>
                                        </div>
                                    @else
                                        <p class="text-muted small mb-2">{{ __('No PDF uploaded yet.') }}</p>
                                    @endif
                                    <label class="form-label fw-semibold" for="{{ $pdf['input_name'] }}">{{ __('Upload PDF') }}</label>
                                    <input
                                        class="form-control @error($pdf['input_name']) is-invalid @enderror"
                                        type="file"
                                        name="{{ $pdf['input_name'] }}"
                                        id="{{ $pdf['input_name'] }}"
                                        accept="application/pdf,.pdf"
                                    >
                                    @error($pdf['input_name'])
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-3 mb-4">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var heroPrefix = @json($prefix);
            var titleInput = document.getElementById(heroPrefix + '_hero_title');
            var colorInput = document.getElementById(heroPrefix + '_hero_background_color');
            var colorHex = document.getElementById(heroPrefix + '_hero_background_color_hex');
            var preview = document.getElementById(heroPrefix + '_hero_preview');
            var previewTitle = document.getElementById(heroPrefix + '_hero_preview_title');
            var defaultTitle = @json($defaultHeroTitle);
            var defaultColor = '#2563eb';

            function resolvedColor() {
                if (colorInput && /^#[0-9A-Fa-f]{6}$/.test(colorInput.value)) {
                    return colorInput.value;
                }
                return defaultColor;
            }

            function syncHexFromColor() {
                if (colorInput && colorHex) {
                    colorHex.value = colorInput.value;
                }
                updatePreviewBg();
            }

            function syncColorFromHex() {
                if (!colorInput || !colorHex) {
                    return;
                }
                var v = (colorHex.value || '').trim();
                if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                    colorInput.value = v;
                }
                updatePreviewBg();
            }

            function updatePreviewBg() {
                if (preview) {
                    preview.style.backgroundColor = resolvedColor();
                }
            }

            function updatePreviewTitle() {
                if (!previewTitle) {
                    return;
                }
                var t = titleInput ? (titleInput.value || '').trim() : '';
                previewTitle.textContent = t !== '' ? t : defaultTitle;
            }

            if (colorInput && colorHex) {
                colorInput.addEventListener('input', syncHexFromColor);
                colorHex.addEventListener('input', function () {
                    syncColorFromHex();
                });
                colorHex.addEventListener('blur', syncColorFromHex);
            }
            if (titleInput) {
                titleInput.addEventListener('input', updatePreviewTitle);
            }

            var form = document.getElementById('frontend-page-hero-form');
            if (form) {
                form.addEventListener('submit', function () {
                    syncColorFromHex();
                });
            }
        })();

        @isset($faqItemRows)
        (function () {
            var block = document.getElementById('faq-items-block');
            var container = document.getElementById('faq-items-rows');
            var template = document.getElementById('faq-item-row-template');
            if (!block || !container || !template) {
                return;
            }
            var nextIndex = container.querySelectorAll('[data-faq-item-row]').length;
            block.addEventListener('click', function (e) {
                var removeBtn = e.target.closest('[data-remove-faq-item]');
                if (removeBtn) {
                    var rows = container.querySelectorAll('[data-faq-item-row]');
                    var row = removeBtn.closest('[data-faq-item-row]');
                    if (rows.length > 1 && row) {
                        row.remove();
                    }
                }
                var addBtn = e.target.closest('[data-add-faq-item]');
                if (addBtn) {
                    var html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex++));
                    var wrap = document.createElement('div');
                    wrap.innerHTML = html.trim();
                    var node = wrap.firstElementChild;
                    if (node) {
                        container.appendChild(node);
                    }
                }
            });
        })();
        @endisset
    </script>
@endpush
