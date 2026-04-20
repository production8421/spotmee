<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('New gym booking') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2 style="margin: 0 0 12px;">{{ __('New gym booking') }}</h2>
    <p><strong>{{ __('Listing') }}:</strong> {{ $gymListing->name }} (ID {{ $gymListing->id }})</p>
    <p><strong>{{ __('Booking ID') }}:</strong> {{ $booking->id }}</p>
    <p><strong>{{ __('Confirmation code') }}:</strong> {{ $booking->confirmation_code }}</p>
    <p><strong>{{ __('Guest') }}:</strong> {{ $booking->guest_name }}</p>
    <p><strong>{{ __('Email') }}:</strong> {{ $booking->guest_email }}</p>
    @if ($booking->guest_phone)
        <p><strong>{{ __('Phone') }}:</strong> {{ $booking->guest_phone }}</p>
    @endif
    <p><strong>{{ __('Date') }}:</strong> {{ $booking->booking_date->format('Y-m-d') }}</p>
    <p><strong>{{ __('Time') }}:</strong> {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}</p>
    <p><strong>{{ __('Persons') }}:</strong> {{ $booking->number_of_persons }}</p>
    <p><strong>{{ __('Quoted total') }}:</strong> @if ($booking->total_price !== null) ${{ number_format((float) $booking->total_price, 2) }} @else — @endif</p>
    @if ($booking->personal_trainer_requested)
        <p><strong>{{ __('Personal training') }}:</strong> {{ __('Yes') }} ({{ $booking->trainer_slot_count }} {{ __('slot(s)') }})</p>
    @endif
    @if ($booking->notes)
        <p><strong>{{ __('Notes') }}:</strong><br>{{ $booking->notes }}</p>
    @endif
    @if ($createdGuestAccount)
        <p>{{ __('A new subscriber account was created for this guest email.') }}</p>
    @endif
    <p><a href="{{ route('admin.gym-bookings.index') }}">{{ __('Open booking listing') }}</a></p>
    <p>— {{ config('app.name') }}</p>
</body>
</html>
