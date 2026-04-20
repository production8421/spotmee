@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Home') }}</h3>
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
            <form id="hero-home-form" method="POST" action="{{ route('admin.frontend.home.update') }}" novalidate>
                @csrf
                @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Hero') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Heading, call-to-action buttons, and background for the public home page hero.') }}</p>
                </div>
                <div class="card-body">

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="home_hero_heading">{{ __('Heading') }}</label>
                            <input
                                class="form-control @error('home_hero_heading') is-invalid @enderror"
                                type="text"
                                name="home_hero_heading"
                                id="home_hero_heading"
                                value="{{ old('home_hero_heading', $settings->home_hero_heading) }}"
                                maxlength="200"
                                placeholder="{{ __('Let\'s get started') }}"
                            >
                            @error('home_hero_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Leave empty to use the default welcome title on the site.') }}</p>
                        </div>

                        <div class="mb-3">
                            <div class="form-label fw-semibold">{{ __('Background') }}</div>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="home_hero_background_type" id="hero_bg_color" value="color"
                                        {{ old('home_hero_background_type', $settings->homeHeroBackgroundKind()) === 'color' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hero_bg_color">{{ __('Solid color') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="home_hero_background_type" id="hero_bg_image" value="image"
                                        {{ old('home_hero_background_type', $settings->homeHeroBackgroundKind()) === 'image' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hero_bg_image">{{ __('Image from media library') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="home_hero_background_type" id="hero_bg_video" value="video"
                                        {{ old('home_hero_background_type', $settings->homeHeroBackgroundKind()) === 'video' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hero_bg_video">{{ __('Video from media library') }}</label>
                                </div>
                            </div>
                            @error('home_hero_background_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 border rounded p-3 bg-light" id="hero_color_panel">
                            <label class="form-label fw-semibold" for="home_hero_background_color">{{ __('Background color') }}</label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <input
                                    class="form-control form-control-color @error('home_hero_background_color') is-invalid @enderror"
                                    type="color"
                                    name="home_hero_background_color"
                                    id="home_hero_background_color"
                                    value="{{ old('home_hero_background_color', $settings->home_hero_background_color ?: '#7366ff') }}"
                                    title="{{ __('Pick a color') }}"
                                >
                                <input
                                    class="form-control @error('home_hero_background_color') is-invalid @enderror"
                                    type="text"
                                    id="home_hero_background_color_hex"
                                    value="{{ old('home_hero_background_color', $settings->home_hero_background_color ?: '#7366ff') }}"
                                    pattern="^#[0-9A-Fa-f]{6}$"
                                    maxlength="7"
                                    style="max-width: 8rem;"
                                    aria-label="{{ __('Hex color') }}"
                                >
                            </div>
                            @error('home_hero_background_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 border rounded p-3 d-none" id="hero_media_panel">
                            <p class="text-muted small">{{ __('JPEG, PNG, GIF, WebP for image; MP4, WebM, or MOV for video.') }}</p>
                            @if ($settings->homeHeroBackgroundKind() !== 'color' && $settings->home_hero_background_path)
                                <div class="mb-3 p-2 border rounded bg-white">
                                    <div class="form-label small text-muted mb-1">{{ __('Current background') }}</div>
                                    @if ($settings->homeHeroBackgroundKind() === 'video')
                                        <video class="w-100 rounded" style="max-height: 160px;" src="{{ $settings->homeHeroBackgroundPublicUrl() }}" muted playsinline controls></video>
                                    @else
                                        <img src="{{ $settings->homeHeroBackgroundPublicUrl() }}" alt="" class="img-fluid rounded" style="max-height: 160px;">
                                    @endif
                                </div>
                            @endif
                            <input type="hidden" name="home_hero_background_media_id" id="home_hero_background_media_id" value="{{ old('home_hero_background_media_id') }}">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#heroMediaPickerModal">
                                    {{ __('Select from media library') }}
                                </button>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="home_hero_media_clear">{{ __('Clear media selection') }}</button>
                            </div>
                            <div class="mb-2 d-none border rounded p-2 bg-white" id="home_hero_media_preview_wrap">
                                <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                <img src="" alt="" class="img-fluid d-none rounded" id="home_hero_media_preview_img" style="max-height: 160px;">
                                <video class="w-100 rounded d-none" id="home_hero_media_preview_video" style="max-height: 160px;" muted playsinline controls></video>
                                <p class="small text-muted mb-0 mt-1" id="home_hero_media_preview_label"></p>
                            </div>
                            @error('home_hero_background_media_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-label fw-semibold">{{ __('Buttons') }}</div>
                            <p class="text-muted small">{{ __('Up to two optional links shown under the hero heading. Label and URL must both be set for a button to appear.') }}</p>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1" for="home_hero_button1_label">{{ __('Button 1 — label') }}</label>
                                    <input
                                        class="form-control @error('home_hero_button1_label') is-invalid @enderror"
                                        type="text"
                                        name="home_hero_button1_label"
                                        id="home_hero_button1_label"
                                        value="{{ old('home_hero_button1_label', $settings->home_hero_button1_label) }}"
                                        maxlength="120"
                                        placeholder="{{ __('Book a Gym') }}"
                                    >
                                    @error('home_hero_button1_label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1" for="home_hero_button1_url">{{ __('Button 1 — URL') }}</label>
                                    <input
                                        class="form-control @error('home_hero_button1_url') is-invalid @enderror"
                                        type="text"
                                        name="home_hero_button1_url"
                                        id="home_hero_button1_url"
                                        value="{{ old('home_hero_button1_url', $settings->home_hero_button1_url) }}"
                                        maxlength="2048"
                                        placeholder="{{ __('e.g. https://example.com or /find-a-gym') }}"
                                    >
                                    @error('home_hero_button1_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1" for="home_hero_button2_label">{{ __('Button 2 — label') }}</label>
                                    <input
                                        class="form-control @error('home_hero_button2_label') is-invalid @enderror"
                                        type="text"
                                        name="home_hero_button2_label"
                                        id="home_hero_button2_label"
                                        value="{{ old('home_hero_button2_label', $settings->home_hero_button2_label) }}"
                                        maxlength="120"
                                        placeholder="{{ __('Book a Gym') }}"
                                    >
                                    @error('home_hero_button2_label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1" for="home_hero_button2_url">{{ __('Button 2 — URL') }}</label>
                                    <input
                                        class="form-control @error('home_hero_button2_url') is-invalid @enderror"
                                        type="text"
                                        name="home_hero_button2_url"
                                        id="home_hero_button2_url"
                                        value="{{ old('home_hero_button2_url', $settings->home_hero_button2_url) }}"
                                        maxlength="2048"
                                        placeholder="{{ __('e.g. https://example.com or /book') }}"
                                    >
                                    @error('home_hero_button2_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Second section (below hero)') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('“How it works” style block: heading with optional highlighted word, and three steps (image from media, badge, title, description). Shown on the public home page when any field is filled.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="how_heading">{{ __('Section heading') }}</label>
                            <input
                                class="form-control @error('how_heading') is-invalid @enderror"
                                type="text"
                                name="how_heading"
                                id="how_heading"
                                value="{{ old('how_heading', $how['heading']) }}"
                                maxlength="200"
                                placeholder="{{ __('How SPOTMEE Works') }}"
                            >
                            @error('how_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="how_heading_emphasis">{{ __('Highlight word (optional)') }}</label>
                            <input
                                class="form-control @error('how_heading_emphasis') is-invalid @enderror"
                                type="text"
                                name="how_heading_emphasis"
                                id="how_heading_emphasis"
                                value="{{ old('how_heading_emphasis', $how['emphasis']) }}"
                                maxlength="80"
                                placeholder="{{ __('SPOTMEE') }}"
                            >
                            @error('how_heading_emphasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Styled in accent color when it appears inside the heading.') }}</p>
                        </div>
                    </div>

                    @for ($i = 1; $i <= 3; $i++)
                        @php
                            $step = $how['steps'][$i - 1];
                            $existingUrl = filled($step['image_path'] ?? null) ? asset('storage/'.$step['image_path']) : null;
                        @endphp
                        <div class="border rounded p-3 mb-4 bg-light">
                            <div class="fw-semibold mb-3">{{ __('Step :n', ['n' => $i]) }}</div>
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-label small text-muted">{{ __('Image') }}</div>
                                    @if ($existingUrl)
                                        <div class="mb-2 p-2 border rounded bg-white">
                                            <img src="{{ $existingUrl }}" alt="" class="img-fluid rounded" style="max-height: 140px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="hidden" name="how_step_{{ $i }}_media_id" id="how_step_{{ $i }}_media_id" value="{{ old("how_step_{$i}_media_id") }}">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#howItWorksMediaPickerModal" data-how-step="{{ $i }}">
                                            {{ __('Select from media library') }}
                                        </button>
                                        <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="how_step_{{ $i }}_media_clear">{{ __('Clear selection') }}</button>
                                    </div>
                                    <div class="d-none border rounded p-2 bg-white mb-2" id="how_step_{{ $i }}_media_preview_wrap">
                                        <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                        <img src="" alt="" class="img-fluid rounded d-none" id="how_step_{{ $i }}_media_preview_img" style="max-height: 140px; width: 100%; object-fit: cover;">
                                        <p class="small text-muted mb-0" id="how_step_{{ $i }}_media_preview_label"></p>
                                    </div>
                                    @error("how_step_{$i}_media_id")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1" for="how_step_{{ $i }}_badge">{{ __('Badge (e.g. Step 1)') }}</label>
                                        <input
                                            class="form-control form-control-sm @error("how_step_{$i}_badge") is-invalid @enderror"
                                            type="text"
                                            name="how_step_{{ $i }}_badge"
                                            id="how_step_{{ $i }}_badge"
                                            value="{{ old("how_step_{$i}_badge", $step['badge']) }}"
                                            maxlength="60"
                                            placeholder="{{ __('Step :n', ['n' => $i]) }}"
                                        >
                                        @error("how_step_{$i}_badge")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1" for="how_step_{{ $i }}_title">{{ __('Title') }}</label>
                                        <input
                                            class="form-control @error("how_step_{$i}_title") is-invalid @enderror"
                                            type="text"
                                            name="how_step_{{ $i }}_title"
                                            id="how_step_{{ $i }}_title"
                                            value="{{ old("how_step_{$i}_title", $step['title']) }}"
                                            maxlength="200"
                                        >
                                        @error("how_step_{$i}_title")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="form-label small text-muted mb-1" for="how_step_{{ $i }}_body">{{ __('Description') }}</label>
                                        <textarea
                                            class="form-control @error("how_step_{$i}_body") is-invalid @enderror"
                                            name="how_step_{{ $i }}_body"
                                            id="how_step_{{ $i }}_body"
                                            rows="3"
                                            maxlength="2500"
                                        >{{ old("how_step_{$i}_body", $step['body']) }}</textarea>
                                        @error("how_step_{$i}_body")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Fourth section (“Why people love” style)') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Centered heading with optional highlight, short description, four columns (icon from media, optional linked pill text, bold line), and an optional bottom button. Shown when any field is filled.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="why_heading">{{ __('Section title') }}</label>
                            <input
                                class="form-control @error('why_heading') is-invalid @enderror"
                                type="text"
                                name="why_heading"
                                id="why_heading"
                                value="{{ old('why_heading', $why['heading']) }}"
                                maxlength="200"
                                placeholder="{{ __('Why People Love') }}"
                            >
                            @error('why_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="why_heading_emphasis">{{ __('Highlight word (optional)') }}</label>
                            <input
                                class="form-control @error('why_heading_emphasis') is-invalid @enderror"
                                type="text"
                                name="why_heading_emphasis"
                                id="why_heading_emphasis"
                                value="{{ old('why_heading_emphasis', $why['emphasis']) }}"
                                maxlength="80"
                                placeholder="{{ __('SPOTMEE') }}"
                            >
                            @error('why_heading_emphasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Shown in accent color on its own line under the title when it does not appear inside the title text.') }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="why_description">{{ __('Section description') }}</label>
                        <textarea
                            class="form-control @error('why_description') is-invalid @enderror"
                            name="why_description"
                            id="why_description"
                            rows="2"
                            maxlength="600"
                            placeholder="{{ __('Discover why thousands choose private home gyms…') }}"
                        >{{ old('why_description', $why['description']) }}</textarea>
                        @error('why_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @for ($i = 1; $i <= 4; $i++)
                        @php
                            $feat = $why['features'][$i - 1];
                            $whyImgUrl = filled($feat['image_path'] ?? null) ? asset('storage/'.$feat['image_path']) : null;
                        @endphp
                        <div class="border rounded p-3 mb-4 bg-light">
                            <div class="fw-semibold mb-3">{{ __('Column :n', ['n' => $i]) }}</div>
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-label small text-muted">{{ __('Icon (from media)') }}</div>
                                    @if ($whyImgUrl)
                                        <div class="mb-2 p-2 border rounded bg-white text-center">
                                            <img src="{{ $whyImgUrl }}" alt="" class="img-fluid rounded-circle object-fit-cover" style="width: 96px; height: 96px;">
                                        </div>
                                    @endif
                                    <input type="hidden" name="why_feature_{{ $i }}_media_id" id="why_feature_{{ $i }}_media_id" value="{{ old("why_feature_{$i}_media_id") }}">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#howItWorksMediaPickerModal" data-why-feature="{{ $i }}">
                                            {{ __('Select from media library') }}
                                        </button>
                                        <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="why_feature_{{ $i }}_media_clear">{{ __('Clear selection') }}</button>
                                    </div>
                                    <div class="d-none border rounded p-2 bg-white mb-2" id="why_feature_{{ $i }}_media_preview_wrap">
                                        <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                        <img src="" alt="" class="img-fluid rounded d-none" id="why_feature_{{ $i }}_media_preview_img" style="max-height: 120px; width: 100%; object-fit: contain;">
                                        <p class="small text-muted mb-0" id="why_feature_{{ $i }}_media_preview_label"></p>
                                    </div>
                                    @error("why_feature_{$i}_media_id")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted mb-1" for="why_feature_{{ $i }}_link_label">{{ __('Pill / link text') }}</label>
                                            <input
                                                class="form-control form-control-sm @error("why_feature_{$i}_link_label") is-invalid @enderror"
                                                type="text"
                                                name="why_feature_{{ $i }}_link_label"
                                                id="why_feature_{{ $i }}_link_label"
                                                value="{{ old("why_feature_{$i}_link_label", $feat['link_label']) }}"
                                                maxlength="80"
                                                placeholder="{{ __('Private & Safe') }}"
                                            >
                                            @error("why_feature_{$i}_link_label")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted mb-1" for="why_feature_{{ $i }}_link_url">{{ __('Pill / link URL') }}</label>
                                            <input
                                                class="form-control form-control-sm @error("why_feature_{$i}_link_url") is-invalid @enderror"
                                                type="text"
                                                name="why_feature_{{ $i }}_link_url"
                                                id="why_feature_{{ $i }}_link_url"
                                                value="{{ old("why_feature_{$i}_link_url", $feat['link_url']) }}"
                                                maxlength="2048"
                                                placeholder="{{ __('e.g. /find-a-gym') }}"
                                            >
                                            @error("why_feature_{$i}_link_url")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label small text-muted mb-1" for="why_feature_{{ $i }}_text">{{ __('Bold description line') }}</label>
                                        <input
                                            class="form-control @error("why_feature_{$i}_text") is-invalid @enderror"
                                            type="text"
                                            name="why_feature_{{ $i }}_text"
                                            id="why_feature_{{ $i }}_text"
                                            value="{{ old("why_feature_{$i}_text", $feat['text']) }}"
                                            maxlength="300"
                                            placeholder="{{ __('Work out alone and enjoy it!') }}"
                                        >
                                        @error("why_feature_{$i}_text")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="why_cta_label">{{ __('Bottom button — label') }}</label>
                            <input
                                class="form-control @error('why_cta_label') is-invalid @enderror"
                                type="text"
                                name="why_cta_label"
                                id="why_cta_label"
                                value="{{ old('why_cta_label', $why['cta_label']) }}"
                                maxlength="120"
                                placeholder="{{ __('View All Gyms') }}"
                            >
                            @error('why_cta_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="why_cta_url">{{ __('Bottom button — URL') }}</label>
                            <input
                                class="form-control @error('why_cta_url') is-invalid @enderror"
                                type="text"
                                name="why_cta_url"
                                id="why_cta_url"
                                value="{{ old('why_cta_url', $why['cta_url']) }}"
                                maxlength="2048"
                                placeholder="{{ __('e.g. /find-a-gym') }}"
                            >
                            @error('why_cta_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Fifth section (host / earn)') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Two-column style block: title with optional highlight, three bullet lines, main description, CTA button, small text under the button, and a large image from the media library. Shown when any field is filled.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="earn_heading">{{ __('Title') }}</label>
                            <input
                                class="form-control @error('earn_heading') is-invalid @enderror"
                                type="text"
                                name="earn_heading"
                                id="earn_heading"
                                value="{{ old('earn_heading', $earn['heading']) }}"
                                maxlength="200"
                                placeholder="{{ __('Earn Money With Your Home Gym') }}"
                            >
                            @error('earn_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="earn_heading_emphasis">{{ __('Highlight phrase (optional)') }}</label>
                            <input
                                class="form-control @error('earn_heading_emphasis') is-invalid @enderror"
                                type="text"
                                name="earn_heading_emphasis"
                                id="earn_heading_emphasis"
                                value="{{ old('earn_heading_emphasis', $earn['emphasis']) }}"
                                maxlength="80"
                                placeholder="{{ __('Earn Money') }}"
                            >
                            @error('earn_heading_emphasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mb-0 mt-1">{{ __('Shown in accent color inside the title when it appears in the title text, or on a second line when it does not.') }}</p>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1" for="earn_point_{{ $i }}">{{ __('Point :n', ['n' => $i]) }}</label>
                                <input
                                    class="form-control @error("earn_point_{$i}") is-invalid @enderror"
                                    type="text"
                                    name="earn_point_{{ $i }}"
                                    id="earn_point_{{ $i }}"
                                    value="{{ old("earn_point_{$i}", $earn['points'][$i - 1]) }}"
                                    maxlength="200"
                                    placeholder="{{ __('Share your space.') }}"
                                >
                                @error("earn_point_{$i}")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endfor
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="earn_description">{{ __('Description') }}</label>
                        <textarea
                            class="form-control @error('earn_description') is-invalid @enderror"
                            name="earn_description"
                            id="earn_description"
                            rows="4"
                            maxlength="2500"
                            placeholder="{{ __('List your fitness space with SPOTMEE…') }}"
                        >{{ old('earn_description', $earn['description']) }}</textarea>
                        @error('earn_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="earn_cta_label">{{ __('Button — label') }}</label>
                            <input
                                class="form-control @error('earn_cta_label') is-invalid @enderror"
                                type="text"
                                name="earn_cta_label"
                                id="earn_cta_label"
                                value="{{ old('earn_cta_label', $earn['cta_label']) }}"
                                maxlength="120"
                                placeholder="{{ __('Start Hosting') }}"
                            >
                            @error('earn_cta_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="earn_cta_url">{{ __('Button — URL') }}</label>
                            <input
                                class="form-control @error('earn_cta_url') is-invalid @enderror"
                                type="text"
                                name="earn_cta_url"
                                id="earn_cta_url"
                                value="{{ old('earn_cta_url', $earn['cta_url']) }}"
                                maxlength="2048"
                                placeholder="{{ __('e.g. /host/apply') }}"
                            >
                            @error('earn_cta_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="earn_footnote">{{ __('Small description (below button)') }}</label>
                        <textarea
                            class="form-control @error('earn_footnote') is-invalid @enderror"
                            name="earn_footnote"
                            id="earn_footnote"
                            rows="2"
                            maxlength="500"
                            placeholder="{{ __('Join hundreds of hosts…') }}"
                        >{{ old('earn_footnote', $earn['footnote']) }}</textarea>
                        @error('earn_footnote')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @php
                        $earnImgUrl = filled($earn['image_path'] ?? null) ? asset('storage/'.$earn['image_path']) : null;
                    @endphp
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold mb-2">{{ __('Side image') }}</div>
                        @if ($earnImgUrl)
                            <div class="mb-2 p-2 border rounded bg-white">
                                <img src="{{ $earnImgUrl }}" alt="" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                            </div>
                        @endif
                        <input type="hidden" name="earn_media_id" id="earn_media_id" value="{{ old('earn_media_id') }}">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#howItWorksMediaPickerModal" data-earn-image="1">
                                {{ __('Select from media library') }}
                            </button>
                            <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="earn_media_clear">{{ __('Clear selection') }}</button>
                        </div>
                        <div class="d-none border rounded p-2 bg-white mb-0" id="earn_media_preview_wrap">
                            <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                            <img src="" alt="" class="img-fluid rounded d-none" id="earn_media_preview_img" style="max-height: 200px; width: 100%; object-fit: cover;">
                            <p class="small text-muted mb-0" id="earn_media_preview_label"></p>
                        </div>
                        @error('earn_media_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Sixth section (community / blog style)') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Centered title with optional highlight, short description, four cards (image, title, text), and an optional button. Shown when any field is filled.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="community_heading">{{ __('Section title') }}</label>
                            <input
                                class="form-control @error('community_heading') is-invalid @enderror"
                                type="text"
                                name="community_heading"
                                id="community_heading"
                                value="{{ old('community_heading', $community['heading']) }}"
                                maxlength="200"
                                placeholder="{{ __('Community &') }}"
                            >
                            @error('community_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="community_heading_emphasis">{{ __('Highlight phrase (optional)') }}</label>
                            <input
                                class="form-control @error('community_heading_emphasis') is-invalid @enderror"
                                type="text"
                                name="community_heading_emphasis"
                                id="community_heading_emphasis"
                                value="{{ old('community_heading_emphasis', $community['emphasis']) }}"
                                maxlength="80"
                                placeholder="{{ __('Discussions') }}"
                            >
                            @error('community_heading_emphasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="community_description">{{ __('Section description') }}</label>
                        <textarea
                            class="form-control @error('community_description') is-invalid @enderror"
                            name="community_description"
                            id="community_description"
                            rows="2"
                            maxlength="600"
                            placeholder="{{ __('Where fitness lovers, hosts, and users come together.') }}"
                        >{{ old('community_description', $community['description']) }}</textarea>
                        @error('community_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @for ($i = 1; $i <= 4; $i++)
                        @php
                            $card = $community['cards'][$i - 1];
                            $commImgUrl = filled($card['image_path'] ?? null) ? asset('storage/'.$card['image_path']) : null;
                        @endphp
                        <div class="border rounded p-3 mb-4 bg-light">
                            <div class="fw-semibold mb-3">{{ __('Card :n', ['n' => $i]) }}</div>
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-label small text-muted">{{ __('Image') }}</div>
                                    @if ($commImgUrl)
                                        <div class="mb-2 p-2 border rounded bg-white">
                                            <img src="{{ $commImgUrl }}" alt="" class="img-fluid rounded" style="max-height: 140px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="hidden" name="community_card_{{ $i }}_media_id" id="community_card_{{ $i }}_media_id" value="{{ old("community_card_{$i}_media_id") }}">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#howItWorksMediaPickerModal" data-community-card="{{ $i }}">
                                            {{ __('Select from media library') }}
                                        </button>
                                        <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="community_card_{{ $i }}_media_clear">{{ __('Clear selection') }}</button>
                                    </div>
                                    <div class="d-none border rounded p-2 bg-white mb-0" id="community_card_{{ $i }}_media_preview_wrap">
                                        <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                                        <img src="" alt="" class="img-fluid rounded d-none" id="community_card_{{ $i }}_media_preview_img" style="max-height: 140px; width: 100%; object-fit: cover;">
                                        <p class="small text-muted mb-0" id="community_card_{{ $i }}_media_preview_label"></p>
                                    </div>
                                    @error("community_card_{$i}_media_id")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1" for="community_card_{{ $i }}_title">{{ __('Card title') }}</label>
                                        <input
                                            class="form-control @error("community_card_{$i}_title") is-invalid @enderror"
                                            type="text"
                                            name="community_card_{{ $i }}_title"
                                            id="community_card_{{ $i }}_title"
                                            value="{{ old("community_card_{$i}_title", $card['title']) }}"
                                            maxlength="200"
                                        >
                                        @error("community_card_{$i}_title")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="form-label small text-muted mb-1" for="community_card_{{ $i }}_body">{{ __('Card description') }}</label>
                                        <textarea
                                            class="form-control @error("community_card_{$i}_body") is-invalid @enderror"
                                            name="community_card_{{ $i }}_body"
                                            id="community_card_{{ $i }}_body"
                                            rows="3"
                                            maxlength="600"
                                        >{{ old("community_card_{$i}_body", $card['body']) }}</textarea>
                                        @error("community_card_{$i}_body")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="community_cta_label">{{ __('Button — label') }}</label>
                            <input
                                class="form-control @error('community_cta_label') is-invalid @enderror"
                                type="text"
                                name="community_cta_label"
                                id="community_cta_label"
                                value="{{ old('community_cta_label', $community['cta_label']) }}"
                                maxlength="120"
                                placeholder="{{ __('Visit Blog') }}"
                            >
                            @error('community_cta_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="community_cta_url">{{ __('Button — URL') }}</label>
                            <input
                                class="form-control @error('community_cta_url') is-invalid @enderror"
                                type="text"
                                name="community_cta_url"
                                id="community_cta_url"
                                value="{{ old('community_cta_url', $community['cta_url']) }}"
                                maxlength="2048"
                                placeholder="{{ __('e.g. /blog') }}"
                            >
                            @error('community_cta_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Seventh section (promo banner)') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Full-width banner: title with optional highlight, background image from media, and one button. Shown when any field or image is set.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="promo_banner_heading">{{ __('Title') }}</label>
                            <input
                                class="form-control @error('promo_banner_heading') is-invalid @enderror"
                                type="text"
                                name="promo_banner_heading"
                                id="promo_banner_heading"
                                value="{{ old('promo_banner_heading', $promoBanner['heading']) }}"
                                maxlength="200"
                                placeholder="{{ __('Your Perfect Workout Space Is Just a Click Away.') }}"
                            >
                            @error('promo_banner_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="promo_banner_heading_emphasis">{{ __('Highlight phrase (optional)') }}</label>
                            <input
                                class="form-control @error('promo_banner_heading_emphasis') is-invalid @enderror"
                                type="text"
                                name="promo_banner_heading_emphasis"
                                id="promo_banner_heading_emphasis"
                                value="{{ old('promo_banner_heading_emphasis', $promoBanner['emphasis']) }}"
                                maxlength="80"
                                placeholder="{{ __('Perfect Workout') }}"
                            >
                            @error('promo_banner_heading_emphasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="promo_banner_cta_label">{{ __('Button — label') }}</label>
                            <input
                                class="form-control @error('promo_banner_cta_label') is-invalid @enderror"
                                type="text"
                                name="promo_banner_cta_label"
                                id="promo_banner_cta_label"
                                value="{{ old('promo_banner_cta_label', $promoBanner['cta_label']) }}"
                                maxlength="120"
                                placeholder="{{ __('Get Started') }}"
                            >
                            @error('promo_banner_cta_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1" for="promo_banner_cta_url">{{ __('Button — URL') }}</label>
                            <input
                                class="form-control @error('promo_banner_cta_url') is-invalid @enderror"
                                type="text"
                                name="promo_banner_cta_url"
                                id="promo_banner_cta_url"
                                value="{{ old('promo_banner_cta_url', $promoBanner['cta_url']) }}"
                                maxlength="2048"
                                placeholder="{{ __('e.g. /register') }}"
                            >
                            @error('promo_banner_cta_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @php
                        $promoImgUrl = filled($promoBanner['image_path'] ?? null) ? asset('storage/'.$promoBanner['image_path']) : null;
                    @endphp
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold mb-2">{{ __('Background image') }}</div>
                        @if ($promoImgUrl)
                            <div class="mb-2 p-2 border rounded bg-white">
                                <img src="{{ $promoImgUrl }}" alt="" class="img-fluid rounded" style="max-height: 180px; width: 100%; object-fit: cover;">
                            </div>
                        @endif
                        <input type="hidden" name="promo_banner_media_id" id="promo_banner_media_id" value="{{ old('promo_banner_media_id') }}">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#howItWorksMediaPickerModal" data-promo-banner="1">
                                {{ __('Select from media library') }}
                            </button>
                            <button class="btn btn-link btn-sm text-decoration-none p-0 d-none" type="button" id="promo_banner_media_clear">{{ __('Clear selection') }}</button>
                        </div>
                        <div class="d-none border rounded p-2 bg-white mb-0" id="promo_banner_media_preview_wrap">
                            <div class="small text-muted mb-1">{{ __('New selection') }}</div>
                            <img src="" alt="" class="img-fluid rounded d-none" id="promo_banner_media_preview_img" style="max-height: 180px; width: 100%; object-fit: cover;">
                            <p class="small text-muted mb-0" id="promo_banner_media_preview_label"></p>
                        </div>
                        @error('promo_banner_media_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    <a class="btn btn-light" href="{{ route('dashboard') }}">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="heroMediaPickerModal" tabindex="-1" aria-labelledby="heroMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="heroMediaPickerModalLabel">{{ __('Choose from media library') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small" id="heroPickerHint"></p>
                    <div id="heroMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="heroMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="heroMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('Nothing matches this type in the library yet.') }}
                        <a href="{{ route('admin.media.index') }}">{{ __('Open Media') }}</a>
                    </div>
                    <div id="heroMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="howItWorksMediaPickerModal" tabindex="-1" aria-labelledby="howItWorksMediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="howItWorksMediaPickerModalLabel">{{ __('Choose an image') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">{{ __('Raster images only (JPEG, PNG, GIF, WebP).') }}</p>
                    <div id="howItWorksMediaPickerLoading" class="text-muted small py-4 text-center">{{ __('Loading…') }}</div>
                    <div id="howItWorksMediaPickerError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="howItWorksMediaPickerEmpty" class="text-muted small py-4 text-center d-none">
                        {{ __('No images in the library yet.') }}
                        <a href="{{ route('admin.media.index') }}">{{ __('Open Media') }}</a>
                    </div>
                    <div id="howItWorksMediaPickerGrid" class="row g-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var pickerUrl = @json(route('admin.media.picker-hero-assets'));
            var modalEl = document.getElementById('heroMediaPickerModal');
            var gridEl = document.getElementById('heroMediaPickerGrid');
            var loadingEl = document.getElementById('heroMediaPickerLoading');
            var emptyEl = document.getElementById('heroMediaPickerEmpty');
            var errorEl = document.getElementById('heroMediaPickerError');
            var hintEl = document.getElementById('heroPickerHint');
            var colorPanel = document.getElementById('hero_color_panel');
            var mediaPanel = document.getElementById('hero_media_panel');
            var radios = document.querySelectorAll('input[name="home_hero_background_type"]');
            var colorInput = document.getElementById('home_hero_background_color');
            var colorHex = document.getElementById('home_hero_background_color_hex');

            function selectedType() {
                var r = document.querySelector('input[name="home_hero_background_type"]:checked');
                return r ? r.value : 'color';
            }

            function syncHexFromColor() {
                if (colorInput && colorHex) {
                    colorHex.value = colorInput.value;
                }
            }

            function syncColorFromHex() {
                if (!colorInput || !colorHex) {
                    return;
                }
                var v = (colorHex.value || '').trim();
                if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                    colorInput.value = v;
                }
            }

            function togglePanels() {
                var t = selectedType();
                if (colorPanel) {
                    colorPanel.classList.toggle('d-none', t !== 'color');
                }
                if (mediaPanel) {
                    mediaPanel.classList.toggle('d-none', t === 'color');
                }
                if (hintEl) {
                    hintEl.textContent = t === 'image'
                        ? @json(__('Showing images only. Pick one for the hero background.'))
                        : (t === 'video'
                            ? @json(__('Showing videos only. Pick one for the hero background.'))
                            : '');
                }
            }

            if (colorInput && colorHex) {
                colorInput.addEventListener('input', syncHexFromColor);
                colorHex.addEventListener('input', syncColorFromHex);
                colorHex.addEventListener('blur', syncColorFromHex);
            }

            radios.forEach(function (r) {
                r.addEventListener('change', togglePanels);
            });
            togglePanels();

            var heroForm = document.getElementById('hero-home-form');
            if (heroForm) {
                heroForm.addEventListener('submit', function () {
                    syncColorFromHex();
                });
            }

            var hid = document.getElementById('home_hero_background_media_id');
            var wrap = document.getElementById('home_hero_media_preview_wrap');
            var img = document.getElementById('home_hero_media_preview_img');
            var vid = document.getElementById('home_hero_media_preview_video');
            var lab = document.getElementById('home_hero_media_preview_label');
            var clr = document.getElementById('home_hero_media_clear');

            function clearPreview() {
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
                if (vid) {
                    vid.classList.add('d-none');
                    vid.removeAttribute('src');
                }
                if (lab) {
                    lab.textContent = '';
                }
                if (clr) {
                    clr.classList.add('d-none');
                }
            }

            function setFromMedia(item) {
                if (hid) {
                    hid.value = String(item.id);
                }
                if (wrap) {
                    wrap.classList.remove('d-none');
                }
                if (item.kind === 'video') {
                    if (img) {
                        img.classList.add('d-none');
                        img.removeAttribute('src');
                    }
                    if (vid) {
                        vid.src = item.preview_url || '';
                        vid.classList.remove('d-none');
                    }
                } else {
                    if (vid) {
                        vid.classList.add('d-none');
                        vid.removeAttribute('src');
                    }
                    if (img) {
                        img.src = item.preview_url || '';
                        img.classList.remove('d-none');
                    }
                }
                if (lab) {
                    lab.textContent = item.name || '';
                }
                if (clr) {
                    clr.classList.remove('d-none');
                }
            }

            if (clr) {
                clr.addEventListener('click', clearPreview);
            }

            if (!modalEl || !gridEl) {
                return;
            }

            modalEl.addEventListener('show.bs.modal', function () {
                var want = selectedType();
                if (want === 'color') {
                    return;
                }
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
                        rows = rows.filter(function (item) {
                            return item.kind === want;
                        });
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
                            var inner = '';
                            if (item.kind === 'video') {
                                inner =
                                    '<span class="d-block ratio ratio-16x9 bg-dark rounded overflow-hidden mb-1">' +
                                    '<video src="" class="w-100 h-100 object-fit-cover" muted playsinline preload="metadata"></video>' +
                                    '</span>';
                            } else {
                                inner =
                                    '<span class="d-block ratio ratio-16x9 bg-light rounded overflow-hidden mb-1">' +
                                    '<img src="" alt="" class="object-fit-cover w-100 h-100" loading="lazy">' +
                                    '</span>';
                            }
                            inner += '<span class="small text-truncate d-block" title=""></span>';
                            btn.innerHTML = inner;
                            var cap = btn.querySelector('span.small');
                            if (item.kind === 'video') {
                                var vm = btn.querySelector('video');
                                if (vm) {
                                    vm.src = item.preview_url || '';
                                }
                            } else {
                                var im = btn.querySelector('img');
                                if (im) {
                                    im.src = item.preview_url || '';
                                }
                            }
                            if (cap) {
                                cap.textContent = item.name || '';
                                cap.setAttribute('title', item.name || '');
                            }
                            btn.addEventListener('click', function () {
                                setFromMedia(item);
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
            var modalEl = document.getElementById('howItWorksMediaPickerModal');
            var gridEl = document.getElementById('howItWorksMediaPickerGrid');
            var loadingEl = document.getElementById('howItWorksMediaPickerLoading');
            var emptyEl = document.getElementById('howItWorksMediaPickerEmpty');
            var errorEl = document.getElementById('howItWorksMediaPickerError');
            var stepNum = 1;
            var pickerKind = 'how';
            var whyFeatureNum = 1;
            var communityCardNum = 1;

            function clearPromoBannerPreview() {
                var hid = document.getElementById('promo_banner_media_id');
                var wrap = document.getElementById('promo_banner_media_preview_wrap');
                var img = document.getElementById('promo_banner_media_preview_img');
                var lab = document.getElementById('promo_banner_media_preview_label');
                var clr = document.getElementById('promo_banner_media_clear');
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

            function setPromoBannerFromMedia(item) {
                var hid = document.getElementById('promo_banner_media_id');
                var wrap = document.getElementById('promo_banner_media_preview_wrap');
                var img = document.getElementById('promo_banner_media_preview_img');
                var lab = document.getElementById('promo_banner_media_preview_label');
                var clr = document.getElementById('promo_banner_media_clear');
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

            function clearEarnPreview() {
                var hid = document.getElementById('earn_media_id');
                var wrap = document.getElementById('earn_media_preview_wrap');
                var img = document.getElementById('earn_media_preview_img');
                var lab = document.getElementById('earn_media_preview_label');
                var clr = document.getElementById('earn_media_clear');
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

            function setEarnFromMedia(item) {
                var hid = document.getElementById('earn_media_id');
                var wrap = document.getElementById('earn_media_preview_wrap');
                var img = document.getElementById('earn_media_preview_img');
                var lab = document.getElementById('earn_media_preview_label');
                var clr = document.getElementById('earn_media_clear');
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

            function clearCommunityCardPreview(n) {
                var hid = document.getElementById('community_card_' + n + '_media_id');
                var wrap = document.getElementById('community_card_' + n + '_media_preview_wrap');
                var img = document.getElementById('community_card_' + n + '_media_preview_img');
                var lab = document.getElementById('community_card_' + n + '_media_preview_label');
                var clr = document.getElementById('community_card_' + n + '_media_clear');
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

            function setCommunityCardFromMedia(n, item) {
                var hid = document.getElementById('community_card_' + n + '_media_id');
                var wrap = document.getElementById('community_card_' + n + '_media_preview_wrap');
                var img = document.getElementById('community_card_' + n + '_media_preview_img');
                var lab = document.getElementById('community_card_' + n + '_media_preview_label');
                var clr = document.getElementById('community_card_' + n + '_media_clear');
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

            function clearWhyFeaturePreview(n) {
                var hid = document.getElementById('why_feature_' + n + '_media_id');
                var wrap = document.getElementById('why_feature_' + n + '_media_preview_wrap');
                var img = document.getElementById('why_feature_' + n + '_media_preview_img');
                var lab = document.getElementById('why_feature_' + n + '_media_preview_label');
                var clr = document.getElementById('why_feature_' + n + '_media_clear');
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

            function setWhyFeatureFromMedia(n, item) {
                var hid = document.getElementById('why_feature_' + n + '_media_id');
                var wrap = document.getElementById('why_feature_' + n + '_media_preview_wrap');
                var img = document.getElementById('why_feature_' + n + '_media_preview_img');
                var lab = document.getElementById('why_feature_' + n + '_media_preview_label');
                var clr = document.getElementById('why_feature_' + n + '_media_clear');
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

            function clearStepPreview(n) {
                var hid = document.getElementById('how_step_' + n + '_media_id');
                var wrap = document.getElementById('how_step_' + n + '_media_preview_wrap');
                var img = document.getElementById('how_step_' + n + '_media_preview_img');
                var lab = document.getElementById('how_step_' + n + '_media_preview_label');
                var clr = document.getElementById('how_step_' + n + '_media_clear');
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

            function setStepFromMedia(n, item) {
                var hid = document.getElementById('how_step_' + n + '_media_id');
                var wrap = document.getElementById('how_step_' + n + '_media_preview_wrap');
                var img = document.getElementById('how_step_' + n + '_media_preview_img');
                var lab = document.getElementById('how_step_' + n + '_media_preview_label');
                var clr = document.getElementById('how_step_' + n + '_media_clear');
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

            document.querySelectorAll('[data-bs-target="#howItWorksMediaPickerModal"][data-how-step]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pickerKind = 'how';
                    var v = parseInt(btn.getAttribute('data-how-step'), 10);
                    stepNum = isNaN(v) ? 1 : v;
                });
            });

            document.querySelectorAll('[data-bs-target="#howItWorksMediaPickerModal"][data-why-feature]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pickerKind = 'why';
                    var v = parseInt(btn.getAttribute('data-why-feature'), 10);
                    whyFeatureNum = isNaN(v) ? 1 : v;
                });
            });

            document.querySelectorAll('[data-bs-target="#howItWorksMediaPickerModal"][data-earn-image]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pickerKind = 'earn';
                });
            });

            var earnClr = document.getElementById('earn_media_clear');
            if (earnClr) {
                earnClr.addEventListener('click', function () {
                    clearEarnPreview();
                });
            }

            document.querySelectorAll('[data-bs-target="#howItWorksMediaPickerModal"][data-promo-banner]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pickerKind = 'promoBanner';
                });
            });

            var promoClr = document.getElementById('promo_banner_media_clear');
            if (promoClr) {
                promoClr.addEventListener('click', function () {
                    clearPromoBannerPreview();
                });
            }

            document.querySelectorAll('[data-bs-target="#howItWorksMediaPickerModal"][data-community-card]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    pickerKind = 'community';
                    var v = parseInt(btn.getAttribute('data-community-card'), 10);
                    communityCardNum = isNaN(v) ? 1 : v;
                });
            });

            for (var c = 1; c <= 4; c++) {
                (function (num) {
                    var clrC = document.getElementById('community_card_' + num + '_media_clear');
                    if (clrC) {
                        clrC.addEventListener('click', function () {
                            clearCommunityCardPreview(num);
                        });
                    }
                })(c);
            }

            for (var w = 1; w <= 4; w++) {
                (function (num) {
                    var clrW = document.getElementById('why_feature_' + num + '_media_clear');
                    if (clrW) {
                        clrW.addEventListener('click', function () {
                            clearWhyFeaturePreview(num);
                        });
                    }
                })(w);
            }

            for (var n = 1; n <= 3; n++) {
                (function (num) {
                    var clr = document.getElementById('how_step_' + num + '_media_clear');
                    if (clr) {
                        clr.addEventListener('click', function () {
                            clearStepPreview(num);
                        });
                    }
                })(n);
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
                                if (pickerKind === 'why') {
                                    setWhyFeatureFromMedia(whyFeatureNum, item);
                                } else if (pickerKind === 'earn') {
                                    setEarnFromMedia(item);
                                } else if (pickerKind === 'community') {
                                    setCommunityCardFromMedia(communityCardNum, item);
                                } else if (pickerKind === 'promoBanner') {
                                    setPromoBannerFromMedia(item);
                                } else {
                                    setStepFromMedia(stepNum, item);
                                }
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
