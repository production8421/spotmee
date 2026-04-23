{{-- Public gym booking: React (ported from WP rent-your-jim booking-form.jsx). Bootstrap JSON matches StorePublicGymBookingRequest. --}}
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    @vite('resources/js/gym-booking-form/main.jsx')
@endpush

<div
    id="spotmee-gym-booking-react"
    class="spotmee-gym-booking-react-root"
    data-booking-mounted="false"
>
    <div class="rounded-xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)]/40 px-4 py-6 text-center text-[14px] text-[var(--color-ink-500)]">
        {{ __('Loading booking form…') }}
    </div>
</div>

<script type="application/json" id="spotmee-booking-bootstrap">@json($bookingBootstrap)</script>
