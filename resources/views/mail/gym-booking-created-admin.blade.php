@php
    $brand = 'SPOTMEE';
    $booking->loadMissing('coupon');
    $bd = $booking->booking_date;
    $dateShort = $bd instanceof \DateTimeInterface ? $bd->format('Y-m-d') : (string) $bd;
    $dateLong = $dateShort !== ''
        ? \Illuminate\Support\Carbon::parse($dateShort)->timezone(config('app.timezone'))->translatedFormat('l, F j, Y')
        : '—';
    $start = \Illuminate\Support\Str::substr((string) $booking->start_time, 0, 5);
    $end = \Illuminate\Support\Str::substr((string) $booking->end_time, 0, 5);
    $currency = strtoupper(trim((string) ($booking->currency ?? 'USD'))) ?: 'USD';
    $totalFormatted = $booking->total_price !== null
        ? '$'.number_format((float) $booking->total_price, 2).' '.$currency
        : '—';
    $adminBookingsUrl = route('admin.gym-bookings.index', [], true);
@endphp
<x-mail.spotmee-layout
    :email-title="__('New gym booking').' — '.$brand"
    :preheader="__(':listing · :code · :date', ['listing' => $gymListing->name, 'code' => $booking->confirmation_code, 'date' => $dateLong])"
    :header-title="__('New gym booking')"
    :header-subtitle="$gymListing->name.' (ID '.$gymListing->id.')'"
    :brand="$brand"
    :footer-note="__('You are receiving this because a new booking was placed on :app.', ['app' => $brand])"
>
    <tr>
        <td style="padding:24px 32px 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f0faf9;border:1px solid #83c5be;border-radius:14px;">
                <tr>
                    <td style="padding:18px 20px;">
                        <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#006d77;">{{ __('Confirmation code') }}</p>
                        <p style="margin:0;font-size:20px;font-weight:800;letter-spacing:0.04em;color:#0f172a;font-family:Consolas,'Courier New',monospace;">{{ $booking->confirmation_code }}</p>
                        <p style="margin:10px 0 0;font-size:13px;color:#64748b;">
                            {{ __('Booking ID') }}: <strong style="color:#0f172a;">#{{ $booking->id }}</strong>
                            · {{ __('Status') }}: <strong style="color:#006d77;">{{ ucfirst((string) $booking->status) }}</strong>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:24px 32px 8px;">
            <h2 style="margin:0 0 14px;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Booking summary') }}</h2>
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Listing') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $gymListing->name }} <span style="color:#64748b;">#{{ $gymListing->id }}</span></td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Guest') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->guest_name }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Email') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->guest_email }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Phone') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->guest_phone ? $booking->guest_phone : '—' }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Date') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $dateLong }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Time') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $start }} – {{ $end }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Party size') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->number_of_persons }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Quoted total') }}</td>
                    <td style="padding:12px 16px;font-size:16px;font-weight:800;color:#006d77;border-bottom:1px solid #e2e8f0;">{{ $totalFormatted }}</td>
                </tr>
                @if ($booking->personal_trainer_requested)
                    <tr>
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Personal training') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                            {{ __('Yes') }} — {{ trans_choice('{1} :count slot|[2,*] :count slots', (int) $booking->trainer_slot_count, ['count' => (int) $booking->trainer_slot_count]) }}
                        </td>
                    </tr>
                @endif
                @if ($booking->coupon_id && $booking->coupon)
                    <tr>
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Coupon') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;"><strong>{{ $booking->coupon->code }}</strong></td>
                    </tr>
                @endif
                @if ($booking->notes)
                    <tr>
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;vertical-align:top;">{{ __('Notes') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#334155;white-space:pre-line;">{{ $booking->notes }}</td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
    @if ($createdGuestAccount)
        <tr>
            <td style="padding:8px 32px 0;">
                <p style="margin:0;padding:14px 18px;background:#fffbeb;border:1px solid #fcd34d;border-radius:12px;font-size:14px;color:#78350f;">
                    {{ __('A new subscriber account was created for this guest email.') }}
                </p>
            </td>
        </tr>
    @endif
    <tr>
        <td style="padding:24px 32px 28px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $adminBookingsUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('Open booking listing') }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</x-mail.spotmee-layout>
