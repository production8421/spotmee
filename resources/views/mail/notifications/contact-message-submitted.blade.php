@php
    $brand = 'SPOTMEE';
@endphp
<x-mail.spotmee-layout
    :email-title="__('New contact form submission').' — '.$brand"
    :preheader="__(':name — :email', ['name' => $payload['name'], 'email' => $payload['email']])"
    :header-title="__('New contact form submission')"
    :header-subtitle="config('app.name')"
    :brand="$brand"
    :footer-note="__('You are receiving this because someone used the contact form on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 8px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Name') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $payload['name'] }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Email') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $payload['email'] }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Phone') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $payload['phone'] ?: '—' }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Company') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $payload['company'] ?: '—' }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Message') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:16px;font-size:14px;color:#334155;line-height:1.55;white-space:pre-wrap;">{{ $payload['message'] }}</td>
                </tr>
            </table>
        </td>
    </tr>
</x-mail.spotmee-layout>
