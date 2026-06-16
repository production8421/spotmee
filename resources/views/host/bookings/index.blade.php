@extends('layouts.cuba.app')

@section('title', __('Booking details').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Booking details') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Booking details') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('My bookings') }}</h5>
                    <p class="text-muted small mb-0 mt-1">
                        @if ($shareEarningsWithHost ?? true)
                            {{ __('Bookings for your gym listings and your estimated payout per booking.') }}
                        @else
                            {{ __('Bookings for your gym listings.') }}
                        @endif
                    </p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Booking') }}</th>
                                    <th>{{ __('Guest') }}</th>
                                    <th>{{ __('Gym') }}</th>
                                    <th>{{ __('Schedule') }}</th>
                                    @if ($shareEarningsWithHost ?? true)
                                        <th>{{ __('Customer paid') }}</th>
                                        <th>{{ __('Your payout') }}</th>
                                    @endif
                                    <th>{{ __('Payout split') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    @php
                                        $earning = $earnings[$booking->id] ?? null;
                                        $currency = strtoupper((string) ($earning['currency'] ?? ($booking->currency ?: 'USD')));
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td><code class="user-select-all">{{ $booking->confirmation_code }}</code></td>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->guest_name }}</div>
                                            <div class="text-muted small">{{ $booking->guest_email }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->gymListing?->name ?? '—' }}</div>
                                            @if ($booking->gymListing?->city || $booking->gymListing?->state)
                                                <div class="text-muted small">
                                                    {{ trim(($booking->gymListing->city ?? '').', '.($booking->gymListing->state ?? ''), ', ') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div>{{ $booking->booking_date?->format('M j, Y') ?? '—' }}</div>
                                            <div class="text-muted">
                                                {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }}
                                                –
                                                {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}
                                            </div>
                                            <div class="text-muted">{{ __('Persons') }}: {{ $booking->number_of_persons }}</div>
                                        </td>
                                        @if ($shareEarningsWithHost ?? true)
                                            <td>
                                                @if ($earning)
                                                    {{ $currency }} {{ number_format($earning['customer_paid'], 2) }}
                                                @elseif ($booking->total_price !== null)
                                                    {{ $currency }} {{ number_format((float) $booking->total_price, 2) }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                @if ($earning)
                                                    <span class="fw-semibold text-success">
                                                        {{ $currency }} {{ number_format($earning['host_total_payout'], 2) }}
                                                    </span>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        @endif
                                        <td class="small">
                                            <div>{{ $booking->hostPayoutStatusLabel() }}</div>
                                            @if ($booking->host_payout_scheduled_at)
                                                <div class="text-muted">{{ $booking->host_payout_scheduled_at->format('M j, g:i A') }}</div>
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
                                                data-bs-target="#hostBookingDetailModal{{ $booking->id }}"
                                            >{{ __('View detail') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ ($shareEarningsWithHost ?? true) ? 10 : 8 }}" class="text-center text-muted py-4">{{ __('No bookings for your listings yet.') }}</td>
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
        @include('host.bookings.partials.detail-modal', [
            'booking' => $booking,
            'earning' => $earnings[$booking->id] ?? null,
            'shareEarningsWithHost' => $shareEarningsWithHost ?? true,
        ])
    @endforeach
@endsection
