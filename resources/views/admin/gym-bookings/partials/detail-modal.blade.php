@php
    $start = $booking->start_time;
    $end = $booking->end_time;
    if ($start instanceof \DateTimeInterface) {
        $start = $start->format('H:i');
    } else {
        $start = \Illuminate\Support\Str::substr((string) $start, 0, 5);
    }
    if ($end instanceof \DateTimeInterface) {
        $end = $end->format('H:i');
    } else {
        $end = \Illuminate\Support\Str::substr((string) $end, 0, 5);
    }
    $trainerSlots = is_array($booking->trainer_per_slot) ? $booking->trainer_per_slot : [];
@endphp
<div
    class="modal fade"
    id="bookingDetailModal{{ $booking->id }}"
    tabindex="-1"
    aria-labelledby="bookingDetailModalLabel{{ $booking->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel{{ $booking->id }}">
                    {{ __('Booking') }} #{{ $booking->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Status') }}</div>
                        <div class="fw-semibold">{{ $booking->status ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Confirmation code') }}</div>
                        <div><code>{{ $booking->confirmation_code }}</code></div>
                    </div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Gym listing') }}</div>
                        <div>
                            @if ($booking->gymListing)
                                {{ $booking->gymListing->name }}
                                <span class="text-muted small">(ID {{ $booking->gym_listing_id }})</span>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Registered user') }}</div>
                        <div>
                            @if ($booking->user)
                                {{ $booking->user->name }} — {{ $booking->user->email }}
                                <span class="text-muted small">(ID {{ $booking->user_id }})</span>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Guest name') }}</div>
                        <div>{{ $booking->guest_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Guest email') }}</div>
                        <div>{{ $booking->guest_email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Guest phone') }}</div>
                        <div>{{ $booking->guest_phone ?: '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Booking date') }}</div>
                        <div>{{ $booking->booking_date?->format('Y-m-d (l)') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Time') }}</div>
                        <div>{{ $start }} – {{ $end }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Duration (hours)') }}</div>
                        <div>{{ $booking->duration_hours !== null ? number_format((float) $booking->duration_hours, 2) : '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Persons') }}</div>
                        <div>{{ $booking->number_of_persons }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Total') }}</div>
                        <div>
                            @if ($booking->total_price !== null)
                                {{ strtoupper((string) ($booking->currency ?? 'USD')) }}
                                {{ number_format((float) $booking->total_price, 2) }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Personal training requested') }}</div>
                        <div>{{ $booking->personal_trainer_requested ? __('Yes') : __('No') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('PT slots count') }}</div>
                        <div>{{ $booking->trainer_slot_count ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('PT free trial') }}</div>
                        <div>{{ $booking->pt_free_trial ? __('Yes') : __('No') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('PT free trial slot') }}</div>
                        <div>{{ $booking->pt_free_trial_slot ? \Illuminate\Support\Str::replace('|', ' – ', (string) $booking->pt_free_trial_slot) : '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small text-uppercase mb-1">{{ __('Trainer per slot') }}</div>
                        @if ($trainerSlots === [])
                            <div>—</div>
                        @else
                            <ul class="mb-0 ps-3 small">
                                @foreach ($trainerSlots as $slot => $on)
                                    <li>
                                        <code>{{ $slot }}</code>
                                        — {{ $on ? __('Yes') : __('No') }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="text-muted small text-uppercase mb-1">{{ __('Notes') }}</div>
                        <div class="border rounded p-2 bg-light small">{{ $booking->notes ? nl2br(e($booking->notes)) : '—' }}</div>
                    </div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Payment') }}</div>
                        <div>
                            @if (filled($booking->stripe_payment_intent_id))
                                <span class="badge bg-success">{{ __('Paid (Stripe)') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('No Stripe charge recorded') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Stripe PaymentIntent ID') }}</div>
                        <div class="text-break font-monospace small">{{ $booking->stripe_payment_intent_id ?: '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Created') }}</div>
                        <div class="small">{{ $booking->created_at?->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small text-uppercase">{{ __('Updated') }}</div>
                        <div class="small">{{ $booking->updated_at?->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
