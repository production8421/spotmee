<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('New booking') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2 style="margin: 0 0 12px;">{{ __('You have a new booking') }}</h2>
    <p><strong>{{ __('Your listing') }}:</strong> {{ $gymListing->name }}</p>
    <p><strong>{{ __('Guest') }}:</strong> {{ $booking->guest_name }}</p>
    <p><strong>{{ __('Email') }}:</strong> {{ $booking->guest_email }}</p>
    @if ($booking->guest_phone)
        <p><strong>{{ __('Phone') }}:</strong> {{ $booking->guest_phone }}</p>
    @endif
    <p><strong>{{ __('Date') }}:</strong> {{ $booking->booking_date->format('Y-m-d') }}</p>
    <p><strong>{{ __('Time') }}:</strong> {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}</p>
    <p><strong>{{ __('Persons') }}:</strong> {{ $booking->number_of_persons }}</p>
    <p><strong>{{ __('Confirmation code') }}:</strong> {{ $booking->confirmation_code }}</p>
    @if ($booking->personal_trainer_requested)
        <p><strong>{{ __('Personal training requested') }}:</strong> {{ $booking->trainer_slot_count }} {{ __('slot(s)') }}</p>
    @endif
    @if ($booking->notes)
        <p><strong>{{ __('Guest notes') }}:</strong><br>{{ $booking->notes }}</p>
    @endif
    <p>{{ __('Payment has not been collected through the site yet.') }}</p>
    <p>— {{ config('app.name') }}</p>
</body>
</html>
