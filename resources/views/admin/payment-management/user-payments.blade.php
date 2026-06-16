@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@include('admin.payment-management.partials.page-header')

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
                    <h5 class="mb-0">{{ __('User payments') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Guest payments with booking and host details.') }}</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Booking') }}</th>
                                    <th>{{ __('User / guest') }}</th>
                                    <th>{{ __('Booking details') }}</th>
                                    <th>{{ __('Gym') }}</th>
                                    <th>{{ __('Host') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Stripe payment ID') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Paid at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            <code class="user-select-all">{{ $payment->confirmation_code }}</code>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $payment->guest_name }}</div>
                                            <div class="text-muted small">{{ $payment->guest_email }}</div>
                                            @if ($payment->user)
                                                <div class="text-muted small mt-1">
                                                    {{ __('Account') }}: {{ $payment->user->name }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div>{{ $payment->booking_date?->format('M j, Y') ?? '—' }}</div>
                                            <div class="text-muted">
                                                {{ \Illuminate\Support\Str::substr((string) $payment->start_time, 0, 5) }}
                                                –
                                                {{ \Illuminate\Support\Str::substr((string) $payment->end_time, 0, 5) }}
                                            </div>
                                            <div class="text-muted">{{ __('Persons') }}: {{ $payment->number_of_persons }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $payment->gymListing?->name ?? '—' }}</div>
                                            @if ($payment->gymListing?->city || $payment->gymListing?->state)
                                                <div class="text-muted small">
                                                    {{ trim(($payment->gymListing->city ?? '').', '.($payment->gymListing->state ?? ''), ', ') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $payment->gymListing?->user?->name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $payment->gymListing?->user?->email ?? '—' }}</div>
                                        </td>
                                        <td>
                                            @if ($payment->total_price !== null)
                                                {{ strtoupper((string) ($payment->currency ?: 'USD')) }}
                                                {{ number_format((float) $payment->total_price, 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if (filled($payment->stripe_payment_intent_id))
                                                <span class="badge bg-success">{{ __('Stripe') }}</span>
                                            @elseif ((float) ($payment->total_price ?? 0) <= 0)
                                                <span class="badge bg-secondary">{{ __('Free') }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            @if (filled($payment->stripe_payment_intent_id))
                                                <span class="text-truncate d-inline-block" style="max-width: 10rem;" title="{{ $payment->stripe_payment_intent_id }}">
                                                    {{ $payment->stripe_payment_intent_id }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ ucfirst((string) $payment->status) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">{{ __('No user payments yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($payments->hasPages())
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
