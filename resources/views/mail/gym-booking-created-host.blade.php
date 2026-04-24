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
    $listingUrl = route('host.gym-listings.show', $gymListing, true);
@endphp
<x-mail.spotmee-layout
    :email-title="__('You have a new booking').' — '.$brand"
    :preheader="__(':guest · :date · :code', ['guest' => $booking->guest_name, 'date' => $dateLong, 'code' => $booking->confirmation_code])"
    :header-title="__('You have a new booking')"
    :header-subtitle="$gymListing->name"
    :brand="$brand"
    :footer-note="__('You are receiving this because you host this listing on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f0faf9;border:1px solid #83c5be;border-radius:14px;">
                <tr>
                    <td style="padding:18px 20px;">
                        <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#006d77;">{{ __('Confirmation code') }}</p>
                        <p style="margin:0;font-size:20px;font-weight:800;letter-spacing:0.04em;color:#0f172a;font-family:Consolas,'Courier New',monospace;">{{ $booking->confirmation_code }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:24px 32px 8px;">
            <h2 style="margin:0 0 14px;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Session details') }}</h2>
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Guest') }}</td>
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
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Personal training requested') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                            {{ trans_choice('{1} :count slot|[2,*] :count slots', (int) $booking->trainer_slot_count, ['count' => (int) $booking->trainer_slot_count]) }}
                        </td>
                    </tr>
                @endif
                @if ($booking->notes)
                    <tr>
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;vertical-align:top;">{{ __('Guest notes') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#334155;white-space:pre-line;">{{ $booking->notes }}</td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:8px 32px 0;">
            <p style="margin:0;padding:14px 18px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:12px;font-size:14px;color:#475569;">
                {{ __('Payment has not been collected through the site yet.') }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px 32px 28px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $listingUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('View your listing') }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</x-mail.spotmee-layout>
