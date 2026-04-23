<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Booking confirmed') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2 style="margin: 0 0 12px;">{{ __('Your booking is confirmed') }}</h2>
    <p>{{ __('Thank you for booking with :app.', ['app' => config('app.name')]) }}</p>
    <p><strong>{{ __('Gym') }}:</strong> {{ $gymListing->name }}</p>
    <p><strong>{{ __('Confirmation code') }}:</strong> {{ $booking->confirmation_code }}</p>
    <p><strong>{{ __('Date') }}:</strong> {{ $booking->booking_date->format('Y-m-d') }}</p>
    <p><strong>{{ __('Time') }}:</strong> {{ \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5) }}</p>
    <p><strong>{{ __('Party size') }}:</strong> {{ $booking->number_of_persons }}</p>
    @if ($booking->total_price !== null)
        <p><strong>{{ __('Total') }}:</strong> ${{ number_format((float) $booking->total_price, 2) }}</p>
    @endif
    @if ($booking->personal_trainer_requested)
        <p><strong>{{ __('Personal training') }}:</strong> {{ __('Yes') }} ({{ $booking->trainer_slot_count }} {{ __('slot(s)') }})</p>
    @endif
    @if (! empty($guestNewAccountPlainPassword))
        <p style="margin-top: 20px; padding: 14px 16px; background: #fff8e6; border-radius: 8px; border: 1px solid #e6d4a8;">
            <strong>{{ __('Your subscriber account') }}</strong><br>
            {{ __('We created a new account so you can sign in to :app.', ['app' => config('app.name')]) }}<br><br>
            <strong>{{ __('Login email') }}:</strong> {{ $booking->guest_email }}<br>
            <strong>{{ __('Temporary password') }}:</strong> <code style="font-size: 15px; background: #fff; padding: 2px 6px;">{{ $guestNewAccountPlainPassword }}</code><br><br>
            <a href="{{ url(route('login', [], false)) }}">{{ __('Sign in') }}</a>
            <span style="font-size: 12px; color: #555; display: block; margin-top: 10px;">{{ __('Please change your password after you log in.') }}</span>
        </p>
    @endif
    @if ($booking->status === 'confirmed' && $booking->isCancellable())
        <p style="margin-top: 20px; padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
            <strong>{{ __('Need to cancel?') }}</strong><br>
            <a href="{{ $booking->signedCancelUrl() }}">{{ __('Cancel this booking') }}</a>
            <span class="text-muted" style="font-size: 12px; display: block; margin-top: 8px;">{{ __('This link is valid until your session start time. If you paid by card, we will request a refund when you cancel.') }}</span>
        </p>
    @endif
    <p><a href="{{ route('gym.show', ['slug' => $gymListing->slug]) }}">{{ __('View gym page') }}</a></p>
    <p>— {{ config('app.name') }}</p>
</body>
</html>
