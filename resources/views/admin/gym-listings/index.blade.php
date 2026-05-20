@extends('layouts.cuba.app')

@php
    $gymRoutePrefix = $gymRoutePrefix ?? 'admin.gym-listings';
    $filters = $filters ?? [];
    $gymListingsShowHostFilter = $gymListingsShowHostFilter ?? false;
    $gymListingsShowHostTierColumn = $gymListingsShowHostTierColumn ?? false;
    $gymListingsShowHostNameColumn = $gymListingsShowHostNameColumn ?? false;
    $tableColspan = 7
        + ($gymListingsShowHostNameColumn ? 1 : 0)
        + ($gymListingsShowHostTierColumn ? 1 : 0);
    $cfg = config('gym_listing');
    $filterHosts = $filterHosts ?? collect();
@endphp

@section('title', __('Gym listings').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Gym listings') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Gym listings') }}</li>
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
                    <h5 class="mb-0">{{ $gymListingsIndexHeading ?? __('All gym listings') }}</h5>
                    <a class="btn btn-primary btn-sm" href="{{ route($gymRoutePrefix.'.create') }}">{{ __('Add listing') }}</a>
                </div>
                <div class="card-body border-bottom pt-3">
                    <form class="row g-3 align-items-end" method="get" action="{{ route($gymRoutePrefix.'.index') }}">
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label small mb-1" for="gym-filter-q">{{ __('Search') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="gym-filter-q"
                                name="q"
                                type="search"
                                value="{{ $filters['q'] ?? '' }}"
                                placeholder="{{ __('Name, city, email…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="gym-filter-city">{{ __('City') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="gym-filter-city"
                                name="city"
                                type="text"
                                value="{{ $filters['city'] ?? '' }}"
                                placeholder="{{ __('Contains…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label small mb-1" for="gym-filter-state">{{ __('State') }}</label>
                            <select class="form-select form-select-sm" id="gym-filter-state" name="state">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($cfg['states'] ?? [] as $code => $label)
                                    <option value="{{ $code }}" @selected(($filters['state'] ?? '') === $code)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="gym-filter-workflow">{{ __('Workflow') }}</label>
                            <select class="form-select form-select-sm" id="gym-filter-workflow" name="workflow">
                                <option value="">{{ __('All') }}</option>
                                <option value="pending_approval" @selected(($filters['workflow'] ?? '') === 'pending_approval')>{{ __('Pending host approval') }}</option>
                                <option value="declined" @selected(($filters['workflow'] ?? '') === 'declined')>{{ __('Declined') }}</option>
                                <option value="approved" @selected(($filters['workflow'] ?? '') === 'approved')>{{ __('Admin approved') }}</option>
                                <option value="no_host" @selected(($filters['workflow'] ?? '') === 'no_host')>{{ __('No host assigned') }}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="gym-filter-published">{{ __('Published') }}</label>
                            <select class="form-select form-select-sm" id="gym-filter-published" name="published">
                                <option value="">{{ __('All') }}</option>
                                <option value="1" @selected(($filters['published'] ?? '') === '1')>{{ __('Yes') }}</option>
                                <option value="0" @selected(($filters['published'] ?? '') === '0')>{{ __('No') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="gym-filter-facility">{{ __('Facility type') }}</label>
                            <select class="form-select form-select-sm" id="gym-filter-facility" name="facility_type">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($cfg['facility_types'] ?? [] as $key => $label)
                                    <option value="{{ $key }}" @selected(($filters['facility_type'] ?? '') === $key)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($gymListingsShowHostFilter)
                            <div class="col-12 col-md-6 col-xl-3">
                                <label class="form-label small mb-1" for="gym-filter-host">{{ __('Host') }}</label>
                                <select class="form-select form-select-sm" id="gym-filter-host" name="user_id">
                                    <option value="">{{ __('All hosts') }}</option>
                                    @foreach ($filterHosts as $host)
                                        <option value="{{ $host->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $host->id)>{{ $host->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-12 col-md-6 col-xl-auto d-flex flex-wrap gap-2 pt-md-3 pt-xl-0">
                            <button class="btn btn-primary btn-sm" type="submit">{{ __('Apply filters') }}</button>
                            <a class="btn btn-light btn-sm" href="{{ route($gymRoutePrefix.'.index') }}">{{ __('Clear') }}</a>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" class="d-none d-md-table-cell">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('City') }}</th>
                                    <th scope="col">{{ __('Created date') }}</th>
                                    @if ($gymListingsShowHostNameColumn)
                                        <th scope="col">{{ __('Host') }}</th>
                                    @endif
                                    @if ($gymListingsShowHostTierColumn)
                                        <th scope="col">{{ __('Host tier') }}</th>
                                    @endif
                                    <th scope="col">{{ __('Published') }}</th>
                                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listings as $listing)
                                    <tr>
                                        <td>{{ $listing->id }}</td>
                                        <td class="d-none d-md-table-cell">
                                            @if ($listing->main_image_path)
                                                <img src="{{ $listing->mainImageUrl() }}" alt="" class="rounded" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $listing->name }}</td>
                                        <td>{{ $listing->city }}</td>
                                        <td class="text-muted small text-nowrap">
                                            {{ $listing->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?? '—' }}
                                        </td>
                                        @if ($gymListingsShowHostNameColumn)
                                            <td>
                                                @if ($listing->user)
                                                    <div class="fw-semibold">{{ $listing->user->name }}</div>
                                                    @if (filled($listing->user->email))
                                                        <div class="text-muted small">{{ $listing->user->email }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted" title="{{ __('No host account linked to this listing') }}">—</span>
                                                @endif
                                            </td>
                                        @endif
                                        @if ($gymListingsShowHostTierColumn)
                                            <td>
                                                <form method="post" action="{{ route('admin.gym-listings.host-tier.update', $listing) }}" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select
                                                        class="form-select form-select-sm"
                                                        name="host_tier"
                                                        style="min-width: 9rem; border: 2px solid #64748b; border-radius: 6px; box-shadow: 0 0 0 1px rgba(100, 116, 139, 0.15);"
                                                        onchange="this.form.submit()"
                                                        aria-label="{{ __('Host tier') }}"
                                                    >
                                                        <option value="silver" @selected($listing->hostTierKey() === 'silver')>{{ __('Silver Tier') }}</option>
                                                        <option value="gold" @selected($listing->hostTierKey() === 'gold')>{{ __('Gold Tier') }}</option>
                                                        <option value="platinum" @selected($listing->hostTierKey() === 'platinum')>{{ __('Platinum Tier') }}</option>
                                                    </select>
                                                </form>
                                            </td>
                                        @endif
                                        <td>
                                            @if ($listing->rejectedByAdmin())
                                                <span class="badge badge-light-danger">{{ __('Declined') }}</span>
                                            @elseif ($listing->pendingHostApproval())
                                                <span class="badge badge-light-warning">{{ __('Pending approval') }}</span>
                                            @elseif ($listing->is_published)
                                                <span class="badge badge-light-success">{{ __('Yes') }}</span>
                                            @else
                                                <span class="badge badge-light-secondary">{{ __('No') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route($gymRoutePrefix.'.show', $listing) }}">{{ __('View') }}</a>
                                            <a class="btn btn-light btn-sm" href="{{ route($gymRoutePrefix.'.edit', $listing) }}">{{ __('Edit') }}</a>
                                            <form class="d-inline" method="POST" action="{{ route($gymRoutePrefix.'.destroy', $listing) }}" onsubmit="return confirm(@json(__('Delete this listing?')))">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" type="submit">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $tableColspan }}" class="text-center text-muted py-4">{{ __('No listings yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($listings->hasPages())
                        <div class="px-3 pb-3">
                            {{ $listings->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
