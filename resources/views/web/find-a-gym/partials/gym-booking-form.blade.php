{{-- Public gym booking: React (ported from WP rent-your-jim booking-form.jsx). Bootstrap JSON matches StorePublicGymBookingRequest. --}}
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    @vite('resources/js/gym-booking-form/main.jsx')
@endpush

<div
    id="spotmee-gym-booking-react"
    class="spotmee-gym-booking-react-root"
></div>

<script type="application/json" id="spotmee-booking-bootstrap">@json($bookingBootstrap)</script>
