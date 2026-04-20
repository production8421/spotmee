@extends('layouts.cuba.app')

@section('title', __('Host application').' #'.$application->id.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Host application') }} #{{ $application->id }}</h3>
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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.host-applications.index') }}">{{ __('Pending Host request') }}</a>
                </li>
                <li class="breadcrumb-item active">#{{ $application->id }}</li>
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">{{ $application->full_name }}</h5>
                        @if ($application->isApproved())
                            <span class="badge badge-light-success">{{ __('Approved') }}</span>
                        @else
                            <span class="badge badge-light-warning">{{ __('Pending') }}</span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if (! $application->isApproved())
                            <form class="d-inline mb-0" method="post" action="{{ route('admin.host-applications.approve', $application) }}">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">{{ __('Approve') }}</button>
                            </form>
                        @endif
                        <a class="btn btn-light btn-sm" href="{{ route('admin.host-applications.index') }}">{{ __('Back to list') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">{{ __('Status') }}</dt>
                                <dd class="col-sm-8">
                                    @if ($application->isApproved())
                                        {{ __('Approved') }}
                                        @if ($application->approved_at)
                                            <span class="text-muted small">— {{ $application->approved_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</span>
                                        @endif
                                        @if ($application->approvedBy)
                                            <span class="text-muted small d-block">{{ __('By :name', ['name' => $application->approvedBy->name]) }}</span>
                                        @endif
                                    @else
                                        {{ __('Pending') }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4 text-muted">{{ __('Full name') }}</dt>
                                <dd class="col-sm-8">{{ $application->full_name }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Date of birth') }}</dt>
                                <dd class="col-sm-8">{{ $application->date_of_birth?->format('Y-m-d') }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Social security number') }}</dt>
                                <dd class="col-sm-8">{{ $application->social_security_number !== null && $application->social_security_number !== '' ? $application->social_security_number : '—' }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Phone') }}</dt>
                                <dd class="col-sm-8">{{ $application->phone }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Email') }}</dt>
                                <dd class="col-sm-8"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">{{ __('Street address') }}</dt>
                                <dd class="col-sm-8">{{ $application->street_address }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('City') }}</dt>
                                <dd class="col-sm-8">{{ $application->city }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('State') }}</dt>
                                <dd class="col-sm-8">{{ $application->state }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Postal code') }}</dt>
                                <dd class="col-sm-8">{{ $application->postal_code }}</dd>

                                <dt class="col-sm-4 text-muted">{{ __('Linked account') }}</dt>
                                <dd class="col-sm-8">
                                    @if ($application->user)
                                        <a href="{{ route('admin.users.edit', $application->user) }}">{{ $application->user->name }}</a>
                                        <span class="text-muted small">({{ $application->user->email }})</span>
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-4 text-muted">{{ __('Submitted') }}</dt>
                                <dd class="col-sm-8">{{ $application->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s') }}</dd>
                            </dl>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="text-muted small mb-2">{{ __('Description / about') }}</p>
                            <div class="border rounded p-3 bg-light mb-0">{{ $application->description !== null && $application->description !== '' ? $application->description : '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
