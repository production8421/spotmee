@extends('layouts.cuba.app')

@section('title', __('Settings').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Settings') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Settings') }}</li>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Branding') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Choose logos from the media library for the dashboard header, sidebar, login page, and footer.') }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <div class="form-label fw-semibold">{{ __('Header logo') }}</div>
                            <p class="text-muted small">{{ __('Shown in the top bar, sidebar, and login screen. Choose a raster image (JPEG, PNG, GIF, WebP) from Media; recommended max height ~40px.') }}</p>
                            @if ($settings->header_logo_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->headerLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_header_logo" id="remove_header_logo" value="1">
                                    <label class="form-check-label" for="remove_header_logo">{{ __('Remove custom header logo (use theme default)') }}</label>
                                </div>
                            @endif
                            <input type="hidden" name="header_logo_media_id" id="header_logo_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="header">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="header_logo_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="header_logo_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="header_logo_media_preview_img" style="max-height: 48px;">
                                <p class="small text-muted mb-0" id="header_logo_media_preview_label"></p>
                            </div>
                            @error('header_logo_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-label fw-semibold">{{ __('Footer logo') }}</div>
                            <p class="text-muted small">{{ __('Shown above the copyright line in the dashboard footer. Choose an image from Media.') }}</p>
                            @if ($settings->footer_logo_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->footerLogoUrl() }}" alt="" class="img-fluid" style="max-height: 48px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_footer_logo" id="remove_footer_logo" value="1">
                                    <label class="form-check-label" for="remove_footer_logo">{{ __('Remove custom footer logo') }}</label>
                                </div>
                            @endif
                            <input type="hidden" name="footer_logo_media_id" id="footer_logo_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="footer">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="footer_logo_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="footer_logo_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="footer_logo_media_preview_img" style="max-height: 48px;">
                                <p class="small text-muted mb-0" id="footer_logo_media_preview_label"></p>
                            </div>
                            @error('footer_logo_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">{{ __('Home hero section') }}</h6>
                        <p class="text-muted small">{{ __('Controls only the first hero section on the home page.') }}</p>

                        <div class="mb-3">
                            <label class="form-label" for="home_hero_heading">{{ __('Hero heading') }}</label>
                            <input
                                id="home_hero_heading"
                                type="text"
                                name="home_hero_heading"
                                value="{{ old('home_hero_heading', $settings->home_hero_heading) }}"
                                class="form-control @error('home_hero_heading') is-invalid @enderror"
                                maxlength="255"
                            >
                            @error('home_hero_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">{{ __('Hero background type') }}</label>
                            @php
                                $heroType = old('home_hero_background_type', $settings->home_hero_background_type ?: 'color');
                            @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="home_hero_background_type" id="home_hero_background_type_color" value="color" {{ $heroType === 'color' ? 'checked' : '' }}>
                                <label class="form-check-label" for="home_hero_background_type_color">{{ __('Color') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="home_hero_background_type" id="home_hero_background_type_image" value="image" {{ $heroType === 'image' ? 'checked' : '' }}>
                                <label class="form-check-label" for="home_hero_background_type_image">{{ __('Image') }}</label>
                            </div>
                            @error('home_hero_background_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="home_hero_background_color">{{ __('Hero background color') }}</label>
                            <input
                                id="home_hero_background_color"
                                type="text"
                                name="home_hero_background_color"
                                value="{{ old('home_hero_background_color', $settings->home_hero_background_color ?: '#e3e3e0') }}"
                                class="form-control @error('home_hero_background_color') is-invalid @enderror"
                                placeholder="#e3e3e0"
                            >
                            @error('home_hero_background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-label fw-semibold">{{ __('Hero background image') }}</div>
                            @if ($settings->home_hero_background_path)
                                <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ $settings->homeHeroBackgroundPublicUrl() }}" alt="" class="img-fluid" style="max-height: 72px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_hero_background" id="remove_home_hero_background" value="1">
                                    <label class="form-check-label" for="remove_home_hero_background">{{ __('Remove current hero background image') }}</label>
                                </div>
                            @endif
                            <input type="hidden" name="home_hero_background_media_id" id="home_hero_background_media_id" value="">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#brandingMediaPickerModal" data-branding-slot="hero">
                                    {{ __('Select hero image from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="home_hero_background_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-light" id="home_hero_background_media_preview_wrap">
                                <img src="" alt="" class="img-fluid d-none" id="home_hero_background_media_preview_img" style="max-height: 72px;">
                                <p class="small text-muted mb-0" id="home_hero_background_media_preview_label"></p>
                            </div>
                            @error('home_hero_background_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button1_label">{{ __('Button 1 label') }}</label>
                                <input
                                    id="home_hero_button1_label"
                                    type="text"
                                    name="home_hero_button1_label"
                                    value="{{ old('home_hero_button1_label', $settings->home_hero_button1_label) }}"
                                    class="form-control @error('home_hero_button1_label') is-invalid @enderror"
                                    maxlength="60"
                                >
                                @error('home_hero_button1_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button1_url">{{ __('Button 1 URL') }}</label>
                                <input
                                    id="home_hero_button1_url"
                                    type="url"
                                    name="home_hero_button1_url"
                                    value="{{ old('home_hero_button1_url', $settings->home_hero_button1_url) }}"
                                    class="form-control @error('home_hero_button1_url') is-invalid @enderror"
                                    placeholder="https://example.com"
                                >
                                @error('home_hero_button1_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button2_label">{{ __('Button 2 label') }}</label>
                                <input
                                    id="home_hero_button2_label"
                                    type="text"
                                    name="home_hero_button2_label"
                                    value="{{ old('home_hero_button2_label', $settings->home_hero_button2_label) }}"
                                    class="form-control @error('home_hero_button2_label') is-invalid @enderror"
                                    maxlength="60"
                                >
                                @error('home_hero_button2_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="home_hero_button2_url">{{ __('Button 2 URL') }}</label>
                                <input
                                    id="home_hero_button2_url"
                                    type="url"
                                    name="home_hero_button2_url"
                                    value="{{ old('home_hero_button2_url', $settings->home_hero_button2_url) }}"
                                    class="form-control @error('home_hero_button2_url') is-invalid @enderror"
                                    placeholder="https://example.com"
                                >
                                @error('home_hero_button2_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">{{ __('Save settings') }}</button>
                            <a class="btn btn-light" href="{{ route('dashboard') }}">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="brandingMediaPickerModal" tabindex="-1" aria-labelledby="brandingMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandingMediaPickerModalLabel">{{ __('Choose an image from media library') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">{{ __('Only raster images from your library are listed (JPEG, PNG, GIF, WebP).') }}</p>
                    <div id="brandingMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="brandingMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="brandingMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('No images in the library yet.') }}
                        <a href="{{ route('admin.media.index') }}">{{ __('Open Media') }}</a>
                    </div>
                    <div id="brandingMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var pickerUrl = @json(route('admin.media.picker-images'));
            var slot = 'header';
            var modalEl = document.getElementById('brandingMediaPickerModal');
            var gridEl = document.getElementById('brandingMediaPickerGrid');
            var loadingEl = document.getElementById('brandingMediaPickerLoading');
            var emptyEl = document.getElementById('brandingMediaPickerEmpty');
            var errorEl = document.getElementById('brandingMediaPickerError');
            if (!modalEl || !gridEl) {
                return;
            }

            function slotPrefix(s) {
                if (s === 'footer') {
                    return 'footer_logo';
                }
                if (s === 'hero') {
                    return 'home_hero_background';
                }

                return 'header_logo';
            }

            function clearSlotPreview(s) {
                var p = slotPrefix(s);
                var hid = document.getElementById(p + '_media_id');
                var wrap = document.getElementById(p + '_media_preview_wrap');
                var img = document.getElementById(p + '_media_preview_img');
                var lab = document.getElementById(p + '_media_preview_label');
                var clr = document.getElementById(p + '_media_clear');
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

            function setSlotFromMedia(s, item) {
                var p = slotPrefix(s);
                var hid = document.getElementById(p + '_media_id');
                var wrap = document.getElementById(p + '_media_preview_wrap');
                var img = document.getElementById(p + '_media_preview_img');
                var lab = document.getElementById(p + '_media_preview_label');
                var clr = document.getElementById(p + '_media_clear');
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
            }

            document.querySelectorAll('[data-bs-target="#brandingMediaPickerModal"][data-branding-slot]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var target = btn.getAttribute('data-branding-slot');
                    slot = target === 'footer' || target === 'hero' ? target : 'header';
                });
            });

            ['header_logo', 'footer_logo', 'home_hero_background'].forEach(function (p) {
                var clr = document.getElementById(p + '_media_clear');
                if (clr) {
                    clr.addEventListener('click', function () {
                        clearSlotPreview(p);
                    });
                }
            });

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
                                '<img src="" alt="" class="object-fit-contain w-100 h-100" loading="lazy">' +
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
                                setSlotFromMedia(slot, item);
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
