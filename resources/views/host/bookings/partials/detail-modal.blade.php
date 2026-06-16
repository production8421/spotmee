@php
    $earning = $earning ?? null;
    $currency = strtoupper((string) ($earning['currency'] ?? ($booking->currency ?: 'USD')));
@endphp
<div
    class="modal fade"
    id="hostBookingDetailModal{{ $booking->id }}"
    tabindex="-1"
    aria-labelledby="hostBookingDetailModalLabel{{ $booking->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hostBookingDetailModalLabel{{ $booking->id }}">
                    {{ __('Booking') }} #{{ $booking->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                @include('admin.gym-bookings.partials.detail-modal-body', ['booking' => $booking])

                @if (($shareEarningsWithHost ?? true) && $earning)
                    <hr>
                    <h6 class="mb-3">{{ __('Your earnings') }}</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Customer paid') }}</div>
                            <div>{{ $currency }} {{ number_format($earning['customer_paid'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Your payout') }}</div>
                            <div class="fw-semibold text-success">{{ $currency }} {{ number_format($earning['host_total_payout'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Gym session payout') }}</div>
                            <div>{{ $currency }} {{ number_format($earning['host_gym_payout'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Personal training payout') }}</div>
                            <div>{{ $currency }} {{ number_format($earning['host_pt_payout'], 2) }}</div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
