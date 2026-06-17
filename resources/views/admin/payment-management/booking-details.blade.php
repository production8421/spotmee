@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@include('admin.payment-management.partials.page-header')

@php
    $filters = $filters ?? [];
    $profitSummary = $profitSummary ?? null;
    $summaryCurrency = strtoupper((string) ($profitSummary['currency'] ?? 'USD'));
    $filterHosts = $filterHosts ?? collect();
@endphp

@section('content')
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <form
                        method="POST"
                        action="{{ route('admin.payment-management.booking-details.host-payout-settings.update') }}"
                        class="row g-3 align-items-end"
                    >
                        @csrf
                        <input type="hidden" name="share_host_booking_earnings" value="0">
                        @foreach ($filterQueryParams ?? [] as $filterKey => $filterValue)
                            <input type="hidden" name="{{ $filterKey }}" value="{{ $filterValue }}">
                        @endforeach
                        @if (request()->query('page'))
                            <input type="hidden" name="page" value="{{ request()->query('page') }}">
                        @endif
                        <div class="col-lg-5">
                            <div class="form-check form-switch mb-0">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    name="share_host_booking_earnings"
                                    id="share_host_booking_earnings"
                                    value="1"
                                    @checked(old('share_host_booking_earnings', $shareHostBookingEarnings ?? true))
                                >
                                <label class="form-check-label fw-semibold" for="share_host_booking_earnings">
                                    {{ __('Enable host payout split (global)') }}
                                </label>
                            </div>
                            <p class="text-muted small mb-0 mt-1">
                                {{ __('Global setting applies to all bookings unless a booking split toggle below is turned off.') }}
                            </p>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold mb-1" for="host_payout_delay_hours">{{ __('Split after (hours from booking start)') }}</label>
                            <input
                                class="form-control @error('host_payout_delay_hours') is-invalid @enderror"
                                id="host_payout_delay_hours"
                                type="number"
                                name="host_payout_delay_hours"
                                min="1"
                                max="168"
                                step="1"
                                value="{{ old('host_payout_delay_hours', $hostPayoutDelayHours ?? 12) }}"
                                required
                            >
                            @error('host_payout_delay_hours')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-primary w-100" type="submit">{{ __('Save payout settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($profitSummary)
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ __('Filtered bookings') }}</div>
                        <div class="fs-4 fw-semibold mb-0">{{ number_format($profitSummary['booking_count']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ __('Total customer paid') }}</div>
                        <div class="fs-5 fw-semibold mb-0">{{ $summaryCurrency }} {{ number_format($profitSummary['customer_paid'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ __('Total host payout') }}</div>
                        <div class="fs-5 fw-semibold mb-0">{{ $summaryCurrency }} {{ number_format($profitSummary['host_payout'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ __('Total platform profit') }}</div>
                        <div class="fs-5 fw-semibold text-success mb-0">{{ $summaryCurrency }} {{ number_format($profitSummary['platform_profit'], 2) }}</div>
                        <div class="text-muted small mt-1">{{ __('Margin') }}: {{ number_format($profitSummary['profit_margin_pct'], 2) }}%</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="mb-0">
                        <h5 class="mb-0">{{ __('Booking details & profit margin') }}</h5>
                        <p class="text-muted small mb-0 mt-1">
                            {{ __('Review booking breakdowns, host payouts, and platform profit using current tier commission settings.') }}
                        </p>
                    </div>
                </div>
                <div class="card-body border-bottom">
                    <form class="row g-3 align-items-end" method="get" action="{{ route('admin.payment-management.booking-details.index') }}">
                        <div class="col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="booking-filter-q">{{ __('Search') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="booking-filter-q"
                                name="q"
                                type="search"
                                value="{{ $filters['q'] ?? '' }}"
                                placeholder="{{ __('Code, guest, gym…') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="booking-filter-date-from">{{ __('Date from') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="booking-filter-date-from"
                                name="date_from"
                                type="date"
                                value="{{ $filters['date_from'] ?? '' }}"
                            >
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="booking-filter-date-to">{{ __('Date to') }}</label>
                            <input
                                class="form-control form-control-sm"
                                id="booking-filter-date-to"
                                name="date_to"
                                type="date"
                                value="{{ $filters['date_to'] ?? '' }}"
                            >
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="booking-filter-status">{{ __('Booking status') }}</label>
                            <select class="form-select form-select-sm" id="booking-filter-status" name="status">
                                <option value="">{{ __('All') }}</option>
                                <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>{{ __('Confirmed') }}</option>
                                <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <label class="form-label small mb-1" for="booking-filter-payout-status">{{ __('Payout status') }}</label>
                            <select class="form-select form-select-sm" id="booking-filter-payout-status" name="payout_status">
                                <option value="">{{ __('All') }}</option>
                                <option value="pending" @selected(($filters['payout_status'] ?? '') === 'pending')>{{ __('Scheduled') }}</option>
                                <option value="paid" @selected(($filters['payout_status'] ?? '') === 'paid')>{{ __('Paid to host') }}</option>
                                <option value="skipped" @selected(($filters['payout_status'] ?? '') === 'skipped')>{{ __('Skipped') }}</option>
                                <option value="awaiting_connect" @selected(($filters['payout_status'] ?? '') === 'awaiting_connect')>{{ __('Awaiting bank link') }}</option>
                                <option value="failed" @selected(($filters['payout_status'] ?? '') === 'failed')>{{ __('Failed') }}</option>
                                <option value="processing" @selected(($filters['payout_status'] ?? '') === 'processing')>{{ __('Processing') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="booking-filter-host">{{ __('Host') }}</label>
                            <select class="form-select form-select-sm" id="booking-filter-host" name="host_id">
                                <option value="">{{ __('All hosts') }}</option>
                                @foreach ($filterHosts as $host)
                                    <option value="{{ $host->id }}" @selected((string) ($filters['host_id'] ?? '') === (string) $host->id)>{{ $host->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-xl-auto d-flex flex-wrap gap-2">
                            <button class="btn btn-primary btn-sm" type="submit">{{ __('Apply filters') }}</button>
                            <a class="btn btn-light btn-sm" href="{{ route('admin.payment-management.booking-details.index') }}">{{ __('Clear') }}</a>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Booking') }}</th>
                                    <th>{{ __('Guest') }}</th>
                                    <th>{{ __('Gym / host') }}</th>
                                    <th>{{ __('Schedule') }}</th>
                                    <th>{{ __('Customer paid') }}</th>
                                    <th>{{ __('Host payout') }}</th>
                                    <th>{{ __('Platform profit') }}</th>
                                    <th>{{ __('Margin') }}</th>
                                    <th>{{ __('Payout split') }}</th>
                                    <th>{{ __('Booking status') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    @php
                                        $profit = $profitMargins[$booking->id] ?? null;
                                        $currency = strtoupper((string) ($profit['currency'] ?? ($booking->currency ?: 'USD')));
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            <code class="user-select-all">{{ $booking->confirmation_code }}</code>
                                            @if ($booking->coupon)
                                                <div class="text-muted small mt-1">{{ __('Coupon') }}: {{ $booking->coupon->code }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->guest_name }}</div>
                                            <div class="text-muted small">{{ $booking->guest_email }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->gymListing?->name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $booking->gymListing?->user?->name ?? '—' }}</div>
                                            @if ($profit)
                                                <div class="text-muted small">{{ __('Tier') }}: {{ $profit['host_tier'] }}</div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div>{{ $booking->booking_date?->format('M j, Y') ?? '—' }}</div>
                                            <div class="text-muted">
                                                {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }}
                                                –
                                                {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}
                                            </div>
                                            <div class="text-muted">
                                                {{ __('Slots') }}: {{ $profit['slot_count'] ?? count($booking->time_slots ?? []) }}
                                                · {{ __('Persons') }}: {{ $booking->number_of_persons }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($profit)
                                                {{ $currency }} {{ number_format($profit['customer_paid'], 2) }}
                                            @elseif ($booking->total_price !== null)
                                                {{ $currency }} {{ number_format((float) $booking->total_price, 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if ($profit)
                                                {{ $currency }} {{ number_format($profit['host_total_payout'], 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if ($profit)
                                                <span class="fw-semibold text-success">
                                                    {{ $currency }} {{ number_format($profit['platform_profit'], 2) }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if ($profit)
                                                {{ number_format($profit['profit_margin_pct'], 2) }}%
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="small">
                                            <form
                                                method="POST"
                                                action="{{ route('admin.payment-management.booking-details.payout-split.update', $booking) }}"
                                                class="mb-2 booking-payout-split-toggle"
                                            >
                                                @csrf
                                                <input type="hidden" name="host_payout_split_enabled" value="0">
                                                @foreach ($filterQueryParams ?? [] as $filterKey => $filterValue)
                                                    <input type="hidden" name="{{ $filterKey }}" value="{{ $filterValue }}">
                                                @endforeach
                                                @if (request()->query('page'))
                                                    <input type="hidden" name="page" value="{{ request()->query('page') }}">
                                                @endif
                                                <div class="form-check form-switch mb-0">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        name="host_payout_split_enabled"
                                                        id="host_payout_split_enabled_{{ $booking->id }}"
                                                        value="1"
                                                        @checked($booking->isHostPayoutSplitEnabled())
                                                        @disabled(! $booking->canChangeHostPayoutSplitToggle())
                                                        onchange="this.form.submit()"
                                                    >
                                                    <label class="form-check-label" for="host_payout_split_enabled_{{ $booking->id }}">
                                                        {{ __('Split to host') }}
                                                    </label>
                                                </div>
                                            </form>
                                            <div>{{ $booking->hostPayoutStatusLabel() }}</div>
                                            @if ($booking->host_payout_scheduled_at)
                                                <div class="text-muted">{{ __('Split at') }}: {{ $booking->host_payout_scheduled_at->format('M j, Y g:i A') }}</div>
                                            @endif
                                            @if (filled($booking->stripe_transfer_id))
                                                <div class="text-muted"><code>{{ $booking->stripe_transfer_id }}</code></div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ ucfirst((string) $booking->status) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingProfitDetailModal{{ $booking->id }}"
                                            >{{ __('View detail') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">{{ __('No bookings match your filters.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bookings->hasPages())
                    <div class="card-footer">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($bookings as $booking)
        @include('admin.payment-management.partials.booking-profit-detail-modal', [
            'booking' => $booking,
            'profit' => $profitMargins[$booking->id] ?? null,
        ])
    @endforeach
@endsection
