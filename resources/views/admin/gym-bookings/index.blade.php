@extends('layouts.cuba.app')

@section('title', __('Booking listing').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Booking listing') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gym-bookings.index') }}">{{ __('Gym Bookings') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Booking listing') }}</li>
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
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Bookings') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Listing') }}</th>
                                    <th>{{ __('Guest') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Persons') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            @if ($booking->gymListing)
                                                {{ $booking->gymListing->name }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->guest_name }}</div>
                                            <div class="text-muted small">{{ $booking->guest_email }}</div>
                                        </td>
                                        <td>{{ $booking->booking_date->format('Y-m-d') }}</td>
                                        <td>
                                            {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }}
                                            –
                                            {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}
                                        </td>
                                        <td>{{ $booking->number_of_persons }}</td>
                                        <td>
                                            @if ($booking->total_price !== null)
                                                ${{ number_format((float) $booking->total_price, 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td><code>{{ $booking->confirmation_code }}</code></td>
                                        <td>
                                            @if (filled($booking->stripe_payment_intent_id))
                                                <span class="badge bg-success">{{ __('Card') }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $booking->created_at?->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingDetailModal{{ $booking->id }}"
                                            >{{ __('View detail') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">{{ __('No bookings yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bookings->hasPages())
                    <div class="card-footer">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($bookings as $booking)
        @include('admin.gym-bookings.partials.detail-modal', ['booking' => $booking])
    @endforeach
@endsection
