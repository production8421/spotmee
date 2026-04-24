@props([
    'emailTitle',
    'headerTitle',
    'headerSubtitle' => null,
    'preheader' => null,
    'brand' => 'SPOTMEE',
    'footerNote' => null,
])

@php
    $cancellationPolicyUrl = route('legal.cancellation-policy', [], true);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailTitle }}</title>
</head>
<body style="margin:0;padding:0;background-color:#edf6f9;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    @if (filled($preheader))
        <span style="display:none !important;visibility:hidden;opacity:0;height:0;width:0;overflow:hidden;mso-hide:all;">{{ $preheader }}</span>
    @endif
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#edf6f9;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 12px 40px rgba(0,109,119,0.12);border:1px solid #c5e8e4;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#006d77 0%,#1a8d95 100%);padding:28px 32px;text-align:left;">
                            <p style="margin:0 0 4px;font-size:11px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.85);">{{ $brand }}</p>
                            <h1 style="margin:0;font-size:24px;line-height:1.25;font-weight:800;color:#ffffff;">{{ $headerTitle }}</h1>
                            @if (filled($headerSubtitle))
                                <p style="margin:12px 0 0;font-size:15px;line-height:1.5;color:rgba(255,255,255,0.92);">{{ $headerSubtitle }}</p>
                            @endif
                        </td>
                    </tr>
                    {{ $slot }}
                    <tr>
                        <td style="padding:20px 32px 28px;border-top:1px solid #e2e8f0;background:#fafafa;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;text-align:center;">
                                @if (filled($footerNote))
                                    {{ $footerNote }}<br>
                                @endif
                                <a href="{{ $cancellationPolicyUrl }}" style="color:#83c5be;font-weight:600;text-decoration:underline;">{{ __('Cancellation policy') }}</a>
                                <br><span style="color:#cbd5e1;">© {{ date('Y') }} {{ $brand }}</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
