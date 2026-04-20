@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('How It Works') }}</h3>
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
            <form id="how-it-works-page-form" method="POST" action="{{ route('admin.frontend.how-it-works.update') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Hero') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('Page title bar for the public How it works page (centered title on a solid color band).') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="how_it_works_hero_title">{{ __('Page title') }}</label>
                            <input
                                class="form-control @error('how_it_works_hero_title') is-invalid @enderror"
                                type="text"
                                name="how_it_works_hero_title"
                                id="how_it_works_hero_title"
                                value="{{ old('how_it_works_hero_title', $settings->how_it_works_hero_title) }}"
                                maxlength="200"
                                placeholder="{{ __('How It Works') }}"
                            >
                            @error('how_it_works_hero_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Leave empty to use the default title on the site.') }}</p>
                        </div>

                        <div class="mb-4 border rounded p-3 bg-light">
                            <label class="form-label fw-semibold" for="how_it_works_hero_background_color">{{ __('Background color') }}</label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <input
                                    class="form-control form-control-color @error('how_it_works_hero_background_color') is-invalid @enderror"
                                    type="color"
                                    name="how_it_works_hero_background_color"
                                    id="how_it_works_hero_background_color"
                                    value="{{ old('how_it_works_hero_background_color', $settings->how_it_works_hero_background_color ?: '#2563eb') }}"
                                    title="{{ __('Pick a color') }}"
                                >
                                <input
                                    class="form-control @error('how_it_works_hero_background_color') is-invalid @enderror"
                                    type="text"
                                    id="how_it_works_hero_background_color_hex"
                                    value="{{ old('how_it_works_hero_background_color', $settings->how_it_works_hero_background_color ?: '#2563eb') }}"
                                    pattern="^#[0-9A-Fa-f]{6}$"
                                    maxlength="7"
                                    style="max-width: 8rem;"
                                    aria-label="{{ __('Hex color') }}"
                                >
                            </div>
                            @error('how_it_works_hero_background_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Leave the hex as default or pick any color for the hero bar.') }}</p>
                        </div>

                        <div class="mb-0">
                            <div class="form-label fw-semibold">{{ __('Preview') }}</div>
                            <div
                                id="how_it_works_hero_preview"
                                class="rounded py-4 px-3 text-center text-white fw-semibold fs-5"
                                style="background-color: {{ $settings->howItWorksHeroBackgroundColorCss() }};"
                            >
                                <span id="how_it_works_hero_preview_title">{{ $settings->howItWorksHeroTitleDisplay() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Second section (intro)') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('Title with optional highlight, small title, two text blocks, two buttons, and an optional side image for the public How it works page.') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold" for="intro_heading">{{ __('Title') }}</label>
                                <input
                                    class="form-control @error('intro_heading') is-invalid @enderror"
                                    type="text"
                                    name="intro_heading"
                                    id="intro_heading"
                                    value="{{ old('intro_heading', $intro['heading']) }}"
                                    maxlength="200"
                                    placeholder="{{ __('How SPOTMEE works') }}"
                                >
                                @error('intro_heading')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="intro_heading_emphasis">{{ __('Highlight phrase (optional)') }}</label>
                                <input
                                    class="form-control @error('intro_heading_emphasis') is-invalid @enderror"
                                    type="text"
                                    name="intro_heading_emphasis"
                                    id="intro_heading_emphasis"
                                    value="{{ old('intro_heading_emphasis', $intro['emphasis']) }}"
                                    maxlength="80"
                                    placeholder="{{ __('SPOTMEE') }}"
                                >
                                @error('intro_heading_emphasis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="intro_subtitle">{{ __('Small title') }}</label>
                            <input
                                class="form-control @error('intro_subtitle') is-invalid @enderror"
                                type="text"
                                name="intro_subtitle"
                                id="intro_subtitle"
                                value="{{ old('intro_subtitle', $intro['subtitle']) }}"
                                maxlength="200"
                                placeholder="{{ __('Flexible fitness booking made easy') }}"
                            >
                            @error('intro_subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="intro_description_1">{{ __('Description') }} 1</label>
                            <textarea
                                class="form-control @error('intro_description_1') is-invalid @enderror"
                                name="intro_description_1"
                                id="intro_description_1"
                                rows="4"
                                maxlength="8000"
                                placeholder="{{ __('First paragraph…') }}"
                            >{{ old('intro_description_1', $intro['description_1']) }}</textarea>
                            @error('intro_description_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="intro_description_2">{{ __('Description') }} 2</label>
                            <textarea
                                class="form-control @error('intro_description_2') is-invalid @enderror"
                                name="intro_description_2"
                                id="intro_description_2"
                                rows="4"
                                maxlength="8000"
                                placeholder="{{ __('Second paragraph…') }}"
                            >{{ old('intro_description_2', $intro['description_2']) }}</textarea>
                            @error('intro_description_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1" for="intro_button1_label">{{ __('Button') }} 1 — {{ __('label') }}</label>
                                <input
                                    class="form-control @error('intro_button1_label') is-invalid @enderror"
                                    type="text"
                                    name="intro_button1_label"
                                    id="intro_button1_label"
                                    value="{{ old('intro_button1_label', $intro['button1_label']) }}"
                                    maxlength="120"
                                >
                                @error('intro_button1_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1" for="intro_button1_url">{{ __('Button') }} 1 — {{ __('URL') }}</label>
                                <input
                                    class="form-control @error('intro_button1_url') is-invalid @enderror"
                                    type="text"
                                    name="intro_button1_url"
                                    id="intro_button1_url"
                                    value="{{ old('intro_button1_url', $intro['button1_url']) }}"
                                    maxlength="2048"
                                    placeholder="{{ __('e.g. /host/apply') }}"
                                >
                                @error('intro_button1_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1" for="intro_button2_label">{{ __('Button') }} 2 — {{ __('label') }}</label>
                                <input
                                    class="form-control @error('intro_button2_label') is-invalid @enderror"
                                    type="text"
                                    name="intro_button2_label"
                                    id="intro_button2_label"
                                    value="{{ old('intro_button2_label', $intro['button2_label']) }}"
                                    maxlength="120"
                                >
                                @error('intro_button2_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1" for="intro_button2_url">{{ __('Button') }} 2 — {{ __('URL') }}</label>
                                <input
                                    class="form-control @error('intro_button2_url') is-invalid @enderror"
                                    type="text"
                                    name="intro_button2_url"
                                    id="intro_button2_url"
                                    value="{{ old('intro_button2_url', $intro['button2_url']) }}"
                                    maxlength="2048"
                                    placeholder="{{ __('e.g. /login') }}"
                                >
                                @error('intro_button2_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @php
                            $introImgUrl = filled($intro['image_path'] ?? null) ? asset('storage/'.$intro['image_path']) : null;
                        @endphp
                        <div class="border rounded p-3 bg-light">
                            <div class="fw-semibold mb-2">{{ __('Side image (optional)') }}</div>
                            @if ($introImgUrl)
                                <div class="mb-2 p-2 border rounded bg-white">
                                    <img src="{{ $introImgUrl }}" alt="" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            @endif
                            <input type="hidden" name="intro_media_id" id="intro_media_id" value="{{ old('intro_media_id') }}">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#hiwIntroMediaPickerModal">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="intro_media_clear">{{ __('Clear selection') }}</button>
                            </div>
                            @if ($introImgUrl)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="intro_remove_image" id="intro_remove_image" value="1" @checked(old('intro_remove_image'))>
                                    <label class="form-check-label" for="intro_remove_image">{{ __('Remove saved side image') }}</label>
                                </div>
                            @endif
                            <div class="d-none border rounded p-2 bg-white mb-0" id="intro_media_preview_wrap">
                                <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                <img src="" alt="" class="img-fluid rounded d-none" id="intro_media_preview_img" style="max-height: 200px; width: 100%; object-fit: cover;">
                                <p class="small text-muted mb-0" id="intro_media_preview_label"></p>
                            </div>
                            @error('intro_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Third section (image and text)') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('Optional image (e.g. left column), main title, and description for the public How it works page.') }}</p>
                    </div>
                    <div class="card-body">
                        @php
                            $approachImgUrl = filled($approach['image_path'] ?? null) ? asset('storage/'.$approach['image_path']) : null;
                        @endphp
                        <div class="border rounded p-3 bg-light mb-4">
                            <div class="fw-semibold mb-2">{{ __('Image') }}</div>
                            @if ($approachImgUrl)
                                <div class="mb-2 p-2 border rounded bg-white">
                                    <img src="{{ $approachImgUrl }}" alt="" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            @endif
                            <input type="hidden" name="approach_media_id" id="approach_media_id" value="{{ old('approach_media_id') }}">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#hiwApproachMediaPickerModal">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="approach_media_clear">{{ __('Clear selection') }}</button>
                            </div>
                            @if ($approachImgUrl)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="approach_remove_image" id="approach_remove_image" value="1" @checked(old('approach_remove_image'))>
                                    <label class="form-check-label" for="approach_remove_image">{{ __('Remove saved image') }}</label>
                                </div>
                            @endif
                            <div class="d-none border rounded p-2 bg-white mb-0" id="approach_media_preview_wrap">
                                <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                <img src="" alt="" class="img-fluid rounded d-none" id="approach_media_preview_img" style="max-height: 200px; width: 100%; object-fit: cover;">
                                <p class="small text-muted mb-0" id="approach_media_preview_label"></p>
                            </div>
                            @error('approach_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold" for="approach_title">{{ __('Title') }}</label>
                                <input
                                    class="form-control @error('approach_title') is-invalid @enderror"
                                    type="text"
                                    name="approach_title"
                                    id="approach_title"
                                    value="{{ old('approach_title', $approach['title']) }}"
                                    maxlength="200"
                                    placeholder="{{ __('Our unique approach') }}"
                                >
                                @error('approach_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <p class="text-muted small mb-0 mt-1">{{ __('Leave empty to use the default title on the site when this section is shown.') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="approach_title_emphasis">{{ __('Highlight phrase (optional)') }}</label>
                                <input
                                    class="form-control @error('approach_title_emphasis') is-invalid @enderror"
                                    type="text"
                                    name="approach_title_emphasis"
                                    id="approach_title_emphasis"
                                    value="{{ old('approach_title_emphasis', $approach['emphasis'] ?? '') }}"
                                    maxlength="80"
                                    placeholder="{{ __('unique') }}"
                                >
                                @error('approach_title_emphasis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="approach_description">{{ __('Description') }}</label>
                            <textarea
                                class="form-control @error('approach_description') is-invalid @enderror"
                                name="approach_description"
                                id="approach_description"
                                rows="5"
                                maxlength="8000"
                                placeholder="{{ __('Describe your approach…') }}"
                            >{{ old('approach_description', $approach['description']) }}</textarea>
                            @error('approach_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-3 mb-4">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="hiwIntroMediaPickerModal" tabindex="-1" aria-labelledby="hiwIntroMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hiwIntroMediaPickerModalLabel">{{ __('Choose an image') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div id="hiwIntroMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="hiwIntroMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="hiwIntroMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('No images in the library yet.') }}
                    </div>
                    <div id="hiwIntroMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hiwApproachMediaPickerModal" tabindex="-1" aria-labelledby="hiwApproachMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hiwApproachMediaPickerModalLabel">{{ __('Choose an image') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div id="hiwApproachMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="hiwApproachMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="hiwApproachMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('No images in the library yet.') }}
                    </div>
                    <div id="hiwApproachMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var titleInput = document.getElementById('how_it_works_hero_title');
            var colorInput = document.getElementById('how_it_works_hero_background_color');
            var colorHex = document.getElementById('how_it_works_hero_background_color_hex');
            var preview = document.getElementById('how_it_works_hero_preview');
            var previewTitle = document.getElementById('how_it_works_hero_preview_title');
            var defaultTitle = @json(__('How It Works'));
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

            var form = document.getElementById('how-it-works-page-form');
            if (form) {
                form.addEventListener('submit', function () {
                    syncColorFromHex();
                });
            }
        })();

        (function () {
            var pickerUrl = @json(route('admin.media.picker-images'));
            var modalEl = document.getElementById('hiwIntroMediaPickerModal');
            var gridEl = document.getElementById('hiwIntroMediaPickerGrid');
            var loadingEl = document.getElementById('hiwIntroMediaPickerLoading');
            var emptyEl = document.getElementById('hiwIntroMediaPickerEmpty');
            var errorEl = document.getElementById('hiwIntroMediaPickerError');

            function clearIntroPreview() {
                var hid = document.getElementById('intro_media_id');
                var wrap = document.getElementById('intro_media_preview_wrap');
                var img = document.getElementById('intro_media_preview_img');
                var lab = document.getElementById('intro_media_preview_label');
                var clr = document.getElementById('intro_media_clear');
                if (hid) {
                    hid.value = '';
                }
                if (wrap) {
                    wrap.classList.add('d-none');
                }
                if (img) {
                    img.classList.add('d-none');
                    img.removeAttribute('src');
                }
                if (lab) {
                    lab.textContent = '';
                }
                if (clr) {
                    clr.classList.add('d-none');
                }
            }

            function setIntroFromMedia(item) {
                var hid = document.getElementById('intro_media_id');
                var wrap = document.getElementById('intro_media_preview_wrap');
                var img = document.getElementById('intro_media_preview_img');
                var lab = document.getElementById('intro_media_preview_label');
                var clr = document.getElementById('intro_media_clear');
                var removeCb = document.getElementById('intro_remove_image');
                if (hid) {
                    hid.value = String(item.id);
                }
                if (wrap) {
                    wrap.classList.remove('d-none');
                }
                if (img && item.preview_url) {
                    img.src = item.preview_url;
                    img.classList.remove('d-none');
                }
                if (lab) {
                    lab.textContent = item.name || '';
                }
                if (clr) {
                    clr.classList.remove('d-none');
                }
                if (removeCb) {
                    removeCb.checked = false;
                }
            }

            var introClr = document.getElementById('intro_media_clear');
            if (introClr) {
                introClr.addEventListener('click', function () {
                    clearIntroPreview();
                });
            }

            if (!modalEl || !gridEl) {
                return;
            }

            modalEl.addEventListener('show.bs.modal', function () {
                if (loadingEl) {
                    loadingEl.classList.remove('d-none');
                }
                if (emptyEl) {
                    emptyEl.classList.add('d-none');
                }
                if (errorEl) {
                    errorEl.classList.add('d-none');
                    errorEl.textContent = '';
                }
                gridEl.classList.add('d-none');
                gridEl.innerHTML = '';

                fetch(pickerUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                })
                    .then(function (r) {
                        if (!r.ok) {
                            throw new Error('HTTP ' + r.status);
                        }
                        return r.json();
                    })
                    .then(function (json) {
                        var rows = json && json.data ? json.data : [];
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (!rows.length) {
                            if (emptyEl) {
                                emptyEl.classList.remove('d-none');
                            }
                            return;
                        }
                        rows.forEach(function (item) {
                            var col = document.createElement('div');
                            col.className = 'col-6 col-sm-4 col-md-3';
                            var btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-light border w-100 p-1 text-start h-100';
                            btn.innerHTML =
                                '<span class="d-block ratio ratio-1x1 bg-light rounded overflow-hidden mb-1">' +
                                '<img src="" alt="" class="object-fit-cover w-100 h-100" loading="lazy">' +
                                '</span>' +
                                '<span class="small text-truncate d-block" title=""></span>';
                            var im = btn.querySelector('img');
                            var cap = btn.querySelector('span.small');
                            if (im) {
                                im.src = item.preview_url || '';
                            }
                            if (cap) {
                                cap.textContent = item.name || '';
                                cap.setAttribute('title', item.name || '');
                            }
                            btn.addEventListener('click', function () {
                                setIntroFromMedia(item);
                                var inst = window.bootstrap && window.bootstrap.Modal.getInstance(modalEl);
                                if (inst) {
                                    inst.hide();
                                }
                            });
                            col.appendChild(btn);
                            gridEl.appendChild(col);
                        });
                        gridEl.classList.remove('d-none');
                    })
                    .catch(function () {
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (errorEl) {
                            errorEl.textContent = @json(__('Could not load media library.'));
                            errorEl.classList.remove('d-none');
                        }
                    });
            });
        })();

        (function () {
            var pickerUrl = @json(route('admin.media.picker-images'));
            var modalEl = document.getElementById('hiwApproachMediaPickerModal');
            var gridEl = document.getElementById('hiwApproachMediaPickerGrid');
            var loadingEl = document.getElementById('hiwApproachMediaPickerLoading');
            var emptyEl = document.getElementById('hiwApproachMediaPickerEmpty');
            var errorEl = document.getElementById('hiwApproachMediaPickerError');

            function clearApproachPreview() {
                var hid = document.getElementById('approach_media_id');
                var wrap = document.getElementById('approach_media_preview_wrap');
                var img = document.getElementById('approach_media_preview_img');
                var lab = document.getElementById('approach_media_preview_label');
                var clr = document.getElementById('approach_media_clear');
                if (hid) {
                    hid.value = '';
                }
                if (wrap) {
                    wrap.classList.add('d-none');
                }
                if (img) {
                    img.classList.add('d-none');
                    img.removeAttribute('src');
                }
                if (lab) {
                    lab.textContent = '';
                }
                if (clr) {
                    clr.classList.add('d-none');
                }
            }

            function setApproachFromMedia(item) {
                var hid = document.getElementById('approach_media_id');
                var wrap = document.getElementById('approach_media_preview_wrap');
                var img = document.getElementById('approach_media_preview_img');
                var lab = document.getElementById('approach_media_preview_label');
                var clr = document.getElementById('approach_media_clear');
                var removeCb = document.getElementById('approach_remove_image');
                if (hid) {
                    hid.value = String(item.id);
                }
                if (wrap) {
                    wrap.classList.remove('d-none');
                }
                if (img && item.preview_url) {
                    img.src = item.preview_url;
                    img.classList.remove('d-none');
                }
                if (lab) {
                    lab.textContent = item.name || '';
                }
                if (clr) {
                    clr.classList.remove('d-none');
                }
                if (removeCb) {
                    removeCb.checked = false;
                }
            }

            var approachClr = document.getElementById('approach_media_clear');
            if (approachClr) {
                approachClr.addEventListener('click', function () {
                    clearApproachPreview();
                });
            }

            if (!modalEl || !gridEl) {
                return;
            }

            modalEl.addEventListener('show.bs.modal', function () {
                if (loadingEl) {
                    loadingEl.classList.remove('d-none');
                }
                if (emptyEl) {
                    emptyEl.classList.add('d-none');
                }
                if (errorEl) {
                    errorEl.classList.add('d-none');
                    errorEl.textContent = '';
                }
                gridEl.classList.add('d-none');
                gridEl.innerHTML = '';

                fetch(pickerUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                })
                    .then(function (r) {
                        if (!r.ok) {
                            throw new Error('HTTP ' + r.status);
                        }
                        return r.json();
                    })
                    .then(function (json) {
                        var rows = json && json.data ? json.data : [];
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (!rows.length) {
                            if (emptyEl) {
                                emptyEl.classList.remove('d-none');
                            }
                            return;
                        }
                        rows.forEach(function (item) {
                            var col = document.createElement('div');
                            col.className = 'col-6 col-sm-4 col-md-3';
                            var btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-light border w-100 p-1 text-start h-100';
                            btn.innerHTML =
                                '<span class="d-block ratio ratio-1x1 bg-light rounded overflow-hidden mb-1">' +
                                '<img src="" alt="" class="object-fit-cover w-100 h-100" loading="lazy">' +
                                '</span>' +
                                '<span class="small text-truncate d-block" title=""></span>';
                            var im = btn.querySelector('img');
                            var cap = btn.querySelector('span.small');
                            if (im) {
                                im.src = item.preview_url || '';
                            }
                            if (cap) {
                                cap.textContent = item.name || '';
                                cap.setAttribute('title', item.name || '');
                            }
                            btn.addEventListener('click', function () {
                                setApproachFromMedia(item);
                                var inst = window.bootstrap && window.bootstrap.Modal.getInstance(modalEl);
                                if (inst) {
                                    inst.hide();
                                }
                            });
                            col.appendChild(btn);
                            gridEl.appendChild(col);
                        });
                        gridEl.classList.remove('d-none');
                    })
                    .catch(function () {
                        if (loadingEl) {
                            loadingEl.classList.add('d-none');
                        }
                        if (errorEl) {
                            errorEl.textContent = @json(__('Could not load media library.'));
                            errorEl.classList.remove('d-none');
                        }
                    });
            });
        })();
    </script>
@endpush
