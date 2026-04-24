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
    $duration = $booking->duration_hours !== null
        ? (string) round((float) $booking->duration_hours, 2).' '.__('hours')
        : '—';
    $currency = strtoupper(trim((string) ($booking->currency ?? 'USD'))) ?: 'USD';
    $totalFormatted = $booking->total_price !== null
        ? '$'.number_format((float) $booking->total_price, 2).' '.$currency
        : '—';
    $addrLine = trim((string) ($gymListing->address ?? ''));
    $cityLine = trim(collect([
        $gymListing->city,
        strtoupper((string) ($gymListing->state ?? '')),
        $gymListing->postal_code,
    ])->filter()->implode(', '));
    $gymMapUrl = trim($addrLine.' '.$cityLine) !== ''
        ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(trim($addrLine.' '.$cityLine))
        : null;
    $gymUrl = route('gym.show', ['slug' => $gymListing->slug], true);
    $loginUrl = route('login', [], true);
    $cancellationPolicyUrl = route('legal.cancellation-policy', [], true);
    $canCancel = $booking->status === 'confirmed' && $booking->isCancellable();
    $cancelUrl = $canCancel ? $booking->signedCancelUrl() : null;
    $notes = trim((string) ($booking->notes ?? ''));
    $guestPhone = trim((string) ($booking->guest_phone ?? ''));
    $listingPhone = trim((string) ($gymListing->phone ?? ''));
    $listingEmail = trim((string) ($gymListing->email ?? ''));
@endphp
<x-mail.spotmee-layout
    :email-title="__('Your booking is confirmed').' — '.$brand"
    :preheader="__(':code — :gym on :date', ['code' => $booking->confirmation_code, 'gym' => $gymListing->name, 'date' => $dateLong])"
    :header-title="__('Your booking is confirmed')"
    :header-subtitle="__('Thank you for booking with :app.', ['app' => $brand])"
    :brand="$brand"
    :footer-note="__('You are receiving this because a booking was completed on :app.', ['app' => $brand])"
>
                    {{-- Confirmation chip --}}
                    <tr>
                        <td style="padding:24px 32px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f0faf9;border:1px solid #83c5be;border-radius:14px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#006d77;">{{ __('Confirmation code') }}</p>
                                        <p style="margin:0;font-size:22px;font-weight:800;letter-spacing:0.04em;color:#0f172a;font-family:Consolas,'Courier New',monospace;">
                                            {{ $booking->confirmation_code }}
                                        </p>
                                        <p style="margin:10px 0 0;font-size:13px;color:#64748b;">
                                            {{ __('Booking reference') }}: <strong style="color:#0f172a;">#{{ $booking->id }}</strong>
                                            · {{ __('Status') }}: <strong style="color:#006d77;">{{ ucfirst((string) $booking->status) }}</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Session summary --}}
                    <tr>
                        <td style="padding:24px 32px 8px;">
                            <h2 style="margin:0 0 14px;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Session details') }}</h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Gym') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $gymListing->name }}</td>
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
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Duration') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $duration }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Party size') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->number_of_persons }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Total paid') }}</td>
                                    <td style="padding:12px 16px;font-size:16px;font-weight:800;color:#006d77;border-bottom:1px solid #e2e8f0;">{{ $totalFormatted }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Personal training') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                        @if ($booking->personal_trainer_requested)
                                            {{ __('Yes') }} — {{ trans_choice('{1} :count slot|[2,*] :count slots', (int) $booking->trainer_slot_count, ['count' => (int) $booking->trainer_slot_count]) }}
                                        @else
                                            {{ __('No') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('PT free trial') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                        @if ($booking->pt_free_trial)
                                            {{ __('Yes') }}@if (filled($booking->pt_free_trial_slot)) ({{ $booking->pt_free_trial_slot }})@endif
                                        @else
                                            {{ __('No') }}
                                        @endif
                                    </td>
                                </tr>
                                @if (is_array($booking->trainer_per_slot) && count($booking->trainer_per_slot) > 0)
                                    <tr>
                                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;vertical-align:top;border-bottom:1px solid #e2e8f0;">{{ __('Trainer by slot') }}</td>
                                        <td style="padding:12px 16px;font-size:13px;color:#334155;border-bottom:1px solid #e2e8f0;">
                                            @foreach ($booking->trainer_per_slot as $slotKey => $val)
                                                <div style="margin-bottom:4px;">
                                                    <strong>{{ $slotKey }}</strong>:
                                                    {{ filter_var($val, FILTER_VALIDATE_BOOLEAN) ? __('Yes') : __('No') }}
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                @if ($booking->coupon_id && $booking->coupon)
                                    <tr>
                                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Coupon') }}</td>
                                        <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                            <strong>{{ $booking->coupon->code }}</strong>
                                            @if ($booking->coupon_discount !== null && (float) $booking->coupon_discount > 0)
                                                <span style="color:#64748b;"> — {{ __('Discount') }}: ${{ number_format((float) $booking->coupon_discount, 2) }}</span>
                                            @endif
                                            @if ($booking->coupon_applied_slots !== null && (int) $booking->coupon_applied_slots > 0)
                                                <br><span style="font-size:12px;color:#64748b;">{{ trans_choice('{1} :count free slot applied|[2,*] :count free slots applied', (int) $booking->coupon_applied_slots, ['count' => (int) $booking->coupon_applied_slots]) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($notes !== '')
                                    <tr>
                                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;vertical-align:top;">{{ __('Your notes') }}</td>
                                        <td style="padding:12px 16px;font-size:14px;color:#334155;white-space:pre-line;">{{ $notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    {{-- Your details --}}
                    <tr>
                        <td style="padding:16px 32px 8px;">
                            <h2 style="margin:0 0 14px;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Your details') }}</h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Name') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->guest_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Email') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $booking->guest_email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;">{{ __('Phone') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;">{{ $guestPhone !== '' ? $guestPhone : '—' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Gym location --}}
                    <tr>
                        <td style="padding:16px 32px 8px;">
                            <h2 style="margin:0 0 14px;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Gym location & host contact') }}</h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;vertical-align:top;">{{ __('Address') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;line-height:1.5;">
                                        @if ($addrLine !== '' || $cityLine !== '')
                                            @if ($addrLine !== '')
                                                {{ $addrLine }}<br>
                                            @endif
                                            @if ($cityLine !== '')
                                                {{ $cityLine }}
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Listing phone') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $listingPhone !== '' ? $listingPhone : '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;">{{ __('Listing email') }}</td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;">{{ $listingEmail !== '' ? $listingEmail : '—' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- CTAs --}}
                    <tr>
                        <td style="padding:20px 32px 8px;text-align:center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                <tr>
                                    <td style="border-radius:999px;background:#006d77;">
                                        <a href="{{ $gymUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('View gym page') }}</a>
                                    </td>
                                </tr>
                            </table>
                            @if ($gymMapUrl)
                                <p style="margin:14px 0 0;font-size:13px;">
                                    <a href="{{ $gymMapUrl }}" style="color:#006d77;font-weight:600;text-decoration:underline;">{{ __('Open in Google Maps') }}</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                    {{-- New account --}}
                    @if (! empty($guestNewAccountPlainPassword))
                        <tr>
                            <td style="padding:12px 32px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border:1px solid #fcd34d;border-radius:14px;">
                                    <tr>
                                        <td style="padding:20px 22px;">
                                            <p style="margin:0 0 8px;font-size:13px;font-weight:800;color:#92400e;text-transform:uppercase;letter-spacing:0.06em;">{{ __('Your subscriber account') }}</p>
                                            <p style="margin:0 0 14px;font-size:14px;line-height:1.55;color:#78350f;">
                                                {{ __('We created a new account for you on :app. Sign in with the email below and your temporary password, then change your password from your profile.', ['app' => $brand]) }}
                                            </p>
                                            <p style="margin:0 0 6px;font-size:13px;color:#78350f;"><strong>{{ __('Login email') }}</strong></p>
                                            <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#0f172a;">{{ $booking->guest_email }}</p>
                                            <p style="margin:0 0 6px;font-size:13px;color:#78350f;"><strong>{{ __('Temporary password') }}</strong></p>
                                            <p style="margin:0 0 16px;font-size:18px;font-weight:800;letter-spacing:0.06em;font-family:Consolas,monospace;color:#0f172a;background:#fff;padding:10px 14px;border-radius:8px;border:1px dashed #d97706;display:inline-block;">{{ $guestNewAccountPlainPassword }}</p>
                                            <br>
                                            <a href="{{ $loginUrl }}" style="display:inline-block;margin-top:4px;padding:12px 22px;border-radius:10px;background:#0f172a;color:#fff;font-size:14px;font-weight:700;text-decoration:none;">{{ __('Sign in') }}</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                    {{-- Cancel --}}
                    @if ($cancelUrl)
                        <tr>
                            <td style="padding:12px 32px 24px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:14px;">
                                    <tr>
                                        <td style="padding:20px 22px;text-align:center;">
                                            <p style="margin:0 0 6px;font-size:15px;font-weight:800;color:#0f172a;">{{ __('Need to cancel?') }}</p>
                                            <p style="margin:0 0 12px;font-size:13px;line-height:1.55;color:#475569;">
                                                {{ __('Before you cancel, please read our') }}
                                                <a href="{{ $cancellationPolicyUrl }}" style="color:#006d77;font-weight:700;text-decoration:underline;">{{ __('cancellation policy') }}</a>
                                                {{ __('so you understand timing, refunds, and any fees that may apply.') }}
                                            </p>
                                            <p style="margin:0 0 16px;font-size:13px;line-height:1.5;color:#64748b;">
                                                {{ __('The button below opens your secure cancellation page. It is valid until your session start time. If you paid by card, refunds follow the cancellation policy above.') }}
                                            </p>
                                            <a href="{{ $cancelUrl }}" style="display:inline-block;padding:12px 24px;border-radius:10px;background:#ffffff;color:#006d77;font-size:14px;font-weight:700;text-decoration:none;border:2px solid #006d77;">{{ __('Cancel this booking') }}</a>
                                            <p style="margin:14px 0 0;font-size:12px;line-height:1.5;color:#64748b;">
                                                <a href="{{ $cancellationPolicyUrl }}" style="color:#006d77;font-weight:700;text-decoration:underline;">{{ __('Cancellation policy') }}</a>
                                                <span style="color:#94a3b8;"> · </span>
                                                <a href="{{ $cancelUrl }}" style="color:#006d77;font-weight:700;text-decoration:underline;">{{ __('Cancel page link') }}</a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td style="padding:4px 32px 20px;text-align:center;">
                                <p style="margin:0;font-size:12px;line-height:1.55;color:#64748b;">
                                    {{ __('For cancellation rules and refund timing, read our') }}
                                    <a href="{{ $cancellationPolicyUrl }}" style="color:#006d77;font-weight:700;text-decoration:underline;">{{ __('cancellation policy') }}</a>.
                                </p>
                            </td>
                        </tr>
                    @endif
</x-mail.spotmee-layout>
