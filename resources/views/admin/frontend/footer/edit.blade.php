@extends('layouts.cuba.app')

@php
    $socialLabels = [
        'instagram' => __('Instagram'),
        'facebook' => __('Facebook'),
        'snapchat' => __('Snapchat'),
        'linkedin' => __('LinkedIn'),
        'tiktok' => __('TikTok'),
    ];
    $footerSocial = old('footer_social_urls', \App\Models\ApplicationSetting::normalizeFooterSocialUrlsForForm($settings->footer_social_urls));
@endphp

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Footer') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Footer') }}</li>
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
            <form method="POST" action="{{ route('admin.frontend.footer.update') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Socials') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('Optional profile links for the site footer. Use full https:// URLs.') }}</p>
                    </div>
                    <div class="card-body">
                        @foreach (\App\Models\ApplicationSetting::footerSocialPlatformKeys() as $platform)
                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="footer_social_{{ $platform }}">{{ $socialLabels[$platform] ?? $platform }} — {{ __('URL') }}</label>
                                <input
                                    class="form-control @error('footer_social_urls.'.$platform) is-invalid @enderror"
                                    type="text"
                                    name="footer_social_urls[{{ $platform }}]"
                                    id="footer_social_{{ $platform }}"
                                    value="{{ $footerSocial[$platform] ?? '' }}"
                                    maxlength="2048"
                                    placeholder="{{ __('https://…') }}"
                                    autocomplete="off"
                                >
                                @error('footer_social_urls.'.$platform)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-3 mb-4">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
