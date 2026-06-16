@php
    $profit = $profit ?? null;
    $currency = strtoupper((string) ($profit['currency'] ?? ($booking->currency ?: 'USD')));
@endphp
<div
    class="modal fade"
    id="bookingProfitDetailModal{{ $booking->id }}"
    tabindex="-1"
    aria-labelledby="bookingProfitDetailModalLabel{{ $booking->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingProfitDetailModalLabel{{ $booking->id }}">
                    {{ __('Booking') }} #{{ $booking->id }} — {{ __('Profit breakdown') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                @include('admin.gym-bookings.partials.detail-modal-body', ['booking' => $booking])

                @if ($profit)
                    <hr>
                    <h6 class="mb-3">{{ __('Profit margin') }}</h6>
                    @if (! empty($profit['estimated']))
                        <div class="alert alert-warning outline small" role="note">
                            {{ __('Some tier pricing settings are missing. Payout and profit figures may be incomplete.') }}
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Host tier') }}</div>
                            <div>{{ $profit['host_tier'] }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Gym commission') }}</div>
                            <div>{{ number_format($profit['gym_commission_pct'], 2) }}%</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Customer paid') }}</div>
                            <div class="fw-semibold">{{ $currency }} {{ number_format($profit['customer_paid'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Profit margin') }}</div>
                            <div class="fw-semibold text-success">{{ number_format($profit['profit_margin_pct'], 2) }}%</div>
                        </div>
                        <div class="col-12"><hr class="my-1"></div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Gym revenue (customer)') }}</div>
                            <div>{{ $currency }} {{ number_format($profit['gym_customer_amount'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('PT revenue (customer)') }}</div>
                            <div>{{ $currency }} {{ number_format($profit['pt_customer_amount'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Host gym payout') }}</div>
                            <div>{{ $currency }} {{ number_format($profit['host_gym_payout'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Host PT payout') }}</div>
                            <div>{{ $currency }} {{ number_format($profit['host_pt_payout'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Total host payout') }}</div>
                            <div class="fw-semibold">{{ $currency }} {{ number_format($profit['host_total_payout'], 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Platform profit') }}</div>
                            <div class="fw-semibold text-success">{{ $currency }} {{ number_format($profit['platform_profit'], 2) }}</div>
                        </div>
                        @if ($profit['coupon_discount'] > 0)
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase">{{ __('Coupon discount') }}</div>
                                <div>{{ $currency }} {{ number_format($profit['coupon_discount'], 2) }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                <hr>
                <h6 class="mb-3">{{ __('Stripe payout split') }}</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Booking split enabled') }}</div>
                        <div>{{ $booking->isHostPayoutSplitEnabled() ? __('Yes') : __('No') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Split status') }}</div>
                        <div>{{ $booking->hostPayoutStatusLabel() }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Scheduled split time') }}</div>
                        <div>{{ $booking->host_payout_scheduled_at?->format('Y-m-d H:i') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Stored host payout') }}</div>
                        <div>{{ $booking->host_payout_amount !== null ? $currency.' '.number_format((float) $booking->host_payout_amount, 2) : '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Stripe transfer ID') }}</div>
                        <div class="text-break font-monospace small">{{ $booking->stripe_transfer_id ?: '—' }}</div>
                    </div>
                    @if (filled($booking->host_payout_skip_reason))
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase">{{ __('Skip reason') }}</div>
                            <div>{{ str_replace('_', ' ', (string) $booking->host_payout_skip_reason) }}</div>
                        </div>
                    @endif
                    @if (filled($booking->host_payout_failure_reason))
                        <div class="col-12">
                            <div class="text-muted small text-uppercase">{{ __('Failure reason') }}</div>
                            <div class="text-danger small">{{ $booking->host_payout_failure_reason }}</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
