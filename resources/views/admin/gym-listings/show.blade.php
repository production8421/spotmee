@extends('layouts.cuba.app')

@php
    $gymRoutePrefix = $gymRoutePrefix ?? 'admin.gym-listings';
    $cfg = config('gym_listing');
    $dayKeys = $cfg['weekday_keys'] ?? [];
    $dayLabels = $cfg['weekday_labels'] ?? [];
@endphp

@section('title', $gymListing->name.' — '.__('Gym listing').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Gym listing details') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route($gymRoutePrefix.'.index') }}">{{ __('Gym listings') }}</a></li>
                <li class="breadcrumb-item active">{{ $gymListing->name }}</li>
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
        <div class="col-xl-10">
            <div class="card mb-3">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="mb-0">{{ $gymListing->name }}</h5>
                        <p class="text-muted small mb-0">{{ __('Slug') }}: <code>{{ $gymListing->slug }}</code></p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($gymListing->rejectedByAdmin())
                            <span class="badge badge-light-danger">{{ __('Declined') }}</span>
                        @elseif ($gymListing->pendingHostApproval())
                            <span class="badge badge-light-warning">{{ __('Pending administrator approval') }}</span>
                        @elseif ($gymListing->is_published)
                            <span class="badge badge-light-success">{{ __('Published') }}</span>
                        @else
                            <span class="badge badge-light-secondary">{{ __('Draft') }}</span>
                        @endif
                        @can('approve', $gymListing)
                            <form class="d-inline" method="POST" action="{{ route('admin.gym-listings.approve', $gymListing) }}">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">{{ __('Approve & publish') }}</button>
                            </form>
                        @endcan
                        @can('unapprove', $gymListing)
                            <form class="d-inline" method="POST" action="{{ route('admin.gym-listings.unapprove', $gymListing) }}" onsubmit="return confirm(@json(__('Revoke approval? The listing will be unpublished and the host will be notified.')))">
                                @csrf
                                <button class="btn btn-outline-warning btn-sm" type="submit">{{ __('Revoke approval') }}</button>
                            </form>
                        @endcan
                        <a class="btn btn-primary btn-sm" href="{{ route($gymRoutePrefix.'.edit', $gymListing) }}">{{ __('Edit') }}</a>
                        <a class="btn btn-light btn-sm" href="{{ route($gymRoutePrefix.'.index') }}">{{ __('Back to list') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($gymListing->rejectedByAdmin() && filled($gymListing->rejection_message))
                        <div class="alert alert-light border border-danger mb-4">
                            <p class="small fw-semibold text-muted mb-1">{{ __('Administrator message') }}</p>
                            <p class="mb-0 small text-body-secondary" style="white-space: pre-wrap;">{{ $gymListing->rejection_message }}</p>
                        </div>
                    @endif
                    @can('reject', $gymListing)
                        <div class="border rounded p-3 mb-4 bg-light">
                            <h6 class="small fw-semibold mb-2">{{ __('Decline submission') }}</h6>
                            <form method="POST" action="{{ route('admin.gym-listings.reject', $gymListing) }}" onsubmit="return confirm(@json(__('Decline this listing? The host will be notified and can edit and resubmit.')))">
                                @csrf
                                <label class="form-label small mb-1" for="rejection_message">{{ __('Message to host (optional)') }}</label>
                                <textarea id="rejection_message" name="rejection_message" class="form-control form-control-sm @error('rejection_message') is-invalid @enderror" rows="3" maxlength="2000" placeholder="{{ __('Explain what needs to change…') }}">{{ old('rejection_message') }}</textarea>
                                @error('rejection_message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <button class="btn btn-outline-danger btn-sm mt-2" type="submit">{{ __('Decline') }}</button>
                            </form>
                        </div>
                    @endcan
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            @if ($gymListing->main_image_path)
                                <img src="{{ $gymListing->mainImageUrl() }}" alt="" class="img-fluid rounded border w-100" style="max-height: 280px; object-fit: cover;">
                            @else
                                <div class="border rounded bg-light d-flex align-items-center justify-content-center text-muted" style="min-height: 200px;">{{ __('No main image') }}</div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Contact & location') }}</h6>
                            <dl class="row small mb-0">
                                <dt class="col-sm-4 text-muted">{{ __('Email') }}</dt>
                                <dd class="col-sm-8">{{ $gymListing->email ?? '—' }}</dd>
                                <dt class="col-sm-4 text-muted">{{ __('Phone') }}</dt>
                                <dd class="col-sm-8">{{ $gymListing->phone ?? '—' }}</dd>
                                <dt class="col-sm-4 text-muted">{{ __('Address') }}</dt>
                                <dd class="col-sm-8">{{ $gymListing->address }}</dd>
                                <dt class="col-sm-4 text-muted">{{ __('City / State / ZIP') }}</dt>
                                <dd class="col-sm-8">
                                    {{ $gymListing->city }},
                                    {{ $cfg['states'][$gymListing->state] ?? $gymListing->state }}
                                    {{ $gymListing->postal_code }}
                                </dd>
                                @if ($gymListing->website)
                                    <dt class="col-sm-4 text-muted">{{ __('Website') }}</dt>
                                    <dd class="col-sm-8"><a href="{{ $gymListing->website }}" target="_blank" rel="noopener noreferrer">{{ $gymListing->website }}</a></dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if (is_array($gymListing->gallery_paths) && count($gymListing->gallery_paths) > 0)
                        <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Gallery') }}</h6>
                        <div class="row g-2 mb-4">
                            @foreach ($gymListing->gallery_paths as $path)
                                <div class="col-6 col-md-3 col-lg-2">
                                    <img src="{{ \App\Models\GymListing::publicStorageUrl($path) }}" alt="" class="img-fluid rounded border w-100" style="height: 100px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Facility') }}</h6>
                    <dl class="row small mb-4">
                        <dt class="col-sm-3 text-muted">{{ __('Facility type') }}</dt>
                        <dd class="col-sm-9">{{ __($cfg['facility_types'][$gymListing->facility_type] ?? $gymListing->facility_type) }}</dd>
                        <dt class="col-sm-3 text-muted">{{ __('Area size') }}</dt>
                        <dd class="col-sm-9">{{ __($cfg['area_sizes'][$gymListing->area_size] ?? $gymListing->area_size) }}</dd>
                        <dt class="col-sm-3 text-muted">{{ __('Pets policy') }}</dt>
                        <dd class="col-sm-9">{{ __($cfg['pets_policies'][$gymListing->pets_policy] ?? $gymListing->pets_policy) }}</dd>
                        @if ($gymListing->check_in_method)
                            <dt class="col-sm-3 text-muted">{{ __('Check-in method') }}</dt>
                            <dd class="col-sm-9">{{ __($cfg['check_in_methods'][$gymListing->check_in_method] ?? $gymListing->check_in_method) }}</dd>
                        @endif
                    </dl>

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Service options') }}</h6>
                    <ul class="small mb-4">
                        @foreach ($gymListing->service_options ?? [] as $key)
                            <li>{{ __($cfg['service_options'][$key] ?? $key) }}</li>
                        @endforeach
                    </ul>

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Amenities') }}</h6>
                    <ul class="row small mb-4 list-unstyled">
                        @foreach ($gymListing->amenities ?? [] as $key)
                            <li class="col-md-4 mb-1">• {{ __($cfg['amenities'][$key] ?? $key) }}</li>
                        @endforeach
                    </ul>

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Description') }}</h6>
                    <div class="small mb-4 text-body-secondary" style="white-space: pre-wrap;">{{ $gymListing->description }}</div>

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Equipment') }}</h6>
                    <ul class="small mb-4">
                        @foreach ($gymListing->equipment ?? [] as $row)
                            <li>{{ ($row['name'] ?? '') }} × {{ (int) ($row['quantity'] ?? 0) }}</li>
                        @endforeach
                    </ul>

                    @if ($gymListing->intro_video_path)
                        <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Intro video') }}</h6>
                        <p class="small mb-4"><a href="{{ $gymListing->introVideoUrl() }}" target="_blank" rel="noopener noreferrer">{{ __('Open video') }}</a></p>
                    @endif

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Availability schedule') }}</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Day') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Hours') }}</th>
                                    <th>{{ __('Slot duration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dayKeys as $day)
                                    @php
                                        $row = is_array($gymListing->availability_schedule[$day] ?? null) ? $gymListing->availability_schedule[$day] : [];
                                        $closed = filter_var($row['isClosed'] ?? $row['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
                                        $durs = $row['slotDuration'] ?? [];
                                        if (! is_array($durs)) {
                                            $durs = $durs ? [(string) $durs] : [];
                                        }
                                        if ($durs === [] && isset($row['slot_minutes'])) {
                                            $durs = [(string) (int) $row['slot_minutes']];
                                        }
                                        $durLabels = [];
                                        foreach ($durs as $m) {
                                            $durLabels[] = $m === '60' || $m === 60
                                                ? __('1 hour')
                                                : ($m === '40' || $m === 40 ? __('40 min') : (string) $m.' '.__('min'));
                                        }
                                        $start = $row['startTime'] ?? $row['start'] ?? '';
                                        $end = $row['endTime'] ?? $row['end'] ?? '';
                                    @endphp
                                    <tr>
                                        <td>{{ __($dayLabels[$day] ?? ucfirst($day)) }}</td>
                                        <td>{{ $closed ? __('Closed') : __('Open') }}</td>
                                        <td>
                                            @if ($closed || $start === '' || $end === '')
                                                —
                                            @else
                                                {{ $start }} – {{ $end }}
                                            @endif
                                        </td>
                                        <td>{{ $durLabels !== [] ? implode(', ', $durLabels) : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">{{ __('Personal training') }}</h6>
                    @if ($gymListing->personal_training_available)
                        <p class="small mb-3"><span class="badge badge-light-success">{{ __('Yes') }}</span></p>

                        @if ($gymListing->personal_training_cert_path || $gymListing->personal_training_cpr_cert_path)
                            <div class="row g-3 mb-4">
                                @if ($gymListing->personal_training_cert_path)
                                    <div class="col-md-6">
                                        <p class="small fw-semibold text-muted mb-2">{{ __('PT certification') }}</p>
                                        <div class="border rounded overflow-hidden bg-light p-2 text-center">
                                            <a href="{{ $gymListing->personalTrainingCertUrl() }}" target="_blank" rel="noopener noreferrer" class="d-inline-block">
                                                <img
                                                    src="{{ $gymListing->personalTrainingCertUrl() }}"
                                                    alt="{{ __('PT certification') }}"
                                                    class="img-fluid rounded"
                                                    style="max-height: 240px; max-width: 100%; object-fit: contain;"
                                                >
                                            </a>
                                        </div>
                                        <p class="small mb-0 mt-2">
                                            <a href="{{ $gymListing->personalTrainingCertUrl() }}" target="_blank" rel="noopener noreferrer">{{ __('Open full size in new tab') }}</a>
                                        </p>
                                    </div>
                                @endif
                                @if ($gymListing->personal_training_cpr_cert_path)
                                    <div class="col-md-6">
                                        <p class="small fw-semibold text-muted mb-2">{{ __('CPR / First aid certificate') }}</p>
                                        <div class="border rounded overflow-hidden bg-light p-2 text-center">
                                            <a href="{{ $gymListing->personalTrainingCprCertUrl() }}" target="_blank" rel="noopener noreferrer" class="d-inline-block">
                                                <img
                                                    src="{{ $gymListing->personalTrainingCprCertUrl() }}"
                                                    alt="{{ __('CPR / First aid certificate') }}"
                                                    class="img-fluid rounded"
                                                    style="max-height: 240px; max-width: 100%; object-fit: contain;"
                                                >
                                            </a>
                                        </div>
                                        <p class="small mb-0 mt-2">
                                            <a href="{{ $gymListing->personalTrainingCprCertUrl() }}" target="_blank" rel="noopener noreferrer">{{ __('Open full size in new tab') }}</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="table-responsive mb-0">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Day') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Time slots') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dayKeys as $day)
                                        @php
                                            $pt = is_array($gymListing->personal_training_availability[$day] ?? null) ? $gymListing->personal_training_availability[$day] : [];
                                            $ptClosed = filter_var($pt['isClosed'] ?? $pt['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
                                            $slots = $pt['timeSlots'] ?? [];
                                            if (! is_array($slots)) {
                                                $slots = [];
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ __($dayLabels[$day] ?? ucfirst($day)) }}</td>
                                            <td>{{ $ptClosed ? __('Closed') : __('Open') }}</td>
                                            <td>
                                                @if ($ptClosed || $slots === [])
                                                    —
                                                @else
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($slots as $slot)
                                                            <li class="small">{{ str_replace('|', ' – ', (string) $slot) }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="small text-muted mb-0">{{ __('Not offered') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
