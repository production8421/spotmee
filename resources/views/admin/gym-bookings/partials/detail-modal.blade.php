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
                @include('admin.gym-bookings.partials.detail-modal-body', ['booking' => $booking])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
