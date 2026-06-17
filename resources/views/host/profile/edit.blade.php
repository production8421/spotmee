@extends('layouts.cuba.app')

@section('title', __('Host profile').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Host profile') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Host profile') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (! empty($tableMissing))
        <div class="alert alert-warning outline" role="alert">
            {{ __('Profile photos are not set up yet. Run database migrations on this server.') }}
        </div>
    @endif

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
                    <h5 class="mb-0">{{ __('Profile photo & name') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('This photo appears on your host dashboard and helps guests recognize you.') }}</p>
                </div>
                <div class="card-body">
                    @if (empty($tableMissing))
                        <form method="POST" action="{{ route('host.profile.update') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            @include('partials.profile-photo-upload', [
                                'photoUrl' => $user->profilePhotoUrl(),
                                'initials' => $user->profileInitials(),
                                'showRemove' => filled($user->profile_photo_path),
                            ])

                            <div class="form-group">
                                <label class="col-form-label" for="name">{{ __('Display name') }}</label>
                                <input
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    autocomplete="name"
                                >
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label class="col-form-label" for="email">{{ __('Email') }}</label>
                                <input class="form-control" id="email" type="email" value="{{ $user->email }}" disabled>
                                <div class="form-text">{{ __('Update your email from Account settings.') }}</div>
                            </div>

                            <div class="pt-3">
                                <button class="btn btn-primary" type="submit">{{ __('Save profile') }}</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
