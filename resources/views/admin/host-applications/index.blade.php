@extends('layouts.cuba.app')

@php
    $filters = $filters ?? [];
    $filterUsers = $filterUsers ?? collect();
@endphp

@section('title', __('Pending Host request').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Pending Host request') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Pending Host request') }}</li>
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

    @if ($errors->any())
        <div class="alert alert-danger outline alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Host applications') }}</h5>
                </div>
                <div class="card-body border-bottom pt-3">
                    <form class="row g-3 align-items-end" method="get" action="{{ route('admin.host-applications.index') }}">
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label small mb-1" for="host-app-filter-q">{{ __('Search') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="host-app-filter-q"
                                name="q"
                                type="search"
                                value="{{ $filters['q'] ?? '' }}"
                                placeholder="{{ __('Name, email, phone, city…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="host-app-filter-status">{{ __('Status') }}</label>
                            <select class="form-select form-select-sm" id="host-app-filter-status" name="status">
                                <option value="">{{ __('All') }}</option>
                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>{{ __('Pending') }}</option>
                                <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>{{ __('Approved') }}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="host-app-filter-city">{{ __('City') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="host-app-filter-city"
                                name="city"
                                type="text"
                                value="{{ $filters['city'] ?? '' }}"
                                placeholder="{{ __('Contains…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="host-app-filter-state">{{ __('State') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="host-app-filter-state"
                                name="state"
                                type="text"
                                value="{{ $filters['state'] ?? '' }}"
                                placeholder="{{ __('Contains…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="host-app-filter-from">{{ __('Submitted from') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="host-app-filter-from"
                                name="submitted_from"
                                type="date"
                                value="{{ $filters['submitted_from'] ?? '' }}"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="host-app-filter-to">{{ __('Submitted to') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="host-app-filter-to"
                                name="submitted_to"
                                type="date"
                                value="{{ $filters['submitted_to'] ?? '' }}"
                            >
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="host-app-filter-user">{{ __('Linked account') }}</label>
                            <select class="form-select form-select-sm" id="host-app-filter-user" name="user_id">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($filterUsers as $u)
                                    <option value="{{ $u->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-xl-auto d-flex flex-wrap gap-2 pt-md-3 pt-xl-0">
                            <button class="btn btn-primary btn-sm" type="submit">{{ __('Apply filters') }}</button>
                            <a class="btn btn-light btn-sm" href="{{ route('admin.host-applications.index') }}">{{ __('Clear') }}</a>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('City') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Submitted') }}</th>
                                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr>
                                        <td>{{ $application->id }}</td>
                                        <td>{{ $application->full_name }}</td>
                                        <td>{{ $application->email }}</td>
                                        <td>{{ $application->phone }}</td>
                                        <td>{{ $application->city }}</td>
                                        <td>
                                            @if ($application->isApproved())
                                                <span class="badge badge-light-success">{{ __('Approved') }}</span>
                                            @else
                                                <span class="badge badge-light-warning">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                                        <td class="text-end text-nowrap">
                                            <a class="btn btn-primary btn-sm" href="{{ route('admin.host-applications.show', $application) }}">{{ __('View details') }}</a>
                                            @if (! $application->isApproved())
                                                <form class="d-inline" method="post" action="{{ route('admin.host-applications.approve', $application) }}">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm" type="submit">{{ __('Approve') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">{{ __('No host applications yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($applications->hasPages())
                        <div class="px-3 pb-3">
                            {{ $applications->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
