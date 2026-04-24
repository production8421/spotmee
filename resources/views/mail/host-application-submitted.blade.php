@php
    $brand = 'SPOTMEE';
    $applicationUrl = route('admin.host-applications.show', $application, true);
@endphp
<x-mail.spotmee-layout
    :email-title="__('New host application').' — '.$brand"
    :preheader="$application->full_name.' — '.$application->email"
    :header-title="__('New host application')"
    :header-subtitle="__('Someone submitted a host application on :app.', ['app' => config('app.name')])"
    :brand="$brand"
    :footer-note="__('You are receiving this as a site administrator.')"
>
    <tr>
        <td style="padding:24px 32px 8px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Full Name') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Date of Birth') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->date_of_birth?->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Social Security Number') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->social_security_number ?: __('Not provided') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Phone Number') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->phone }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Email Address') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->email }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Street Address') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->street_address }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('City') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->city }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('State') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->state }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Postal Code') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->postal_code }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">{{ __('Description') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">{{ $application->description ?: '—' }}</td>
                </tr>
                @if ($application->user_id)
                    <tr>
                        <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#475569;">{{ __('Linked user ID') }}</td>
                        <td style="padding:12px 16px;font-size:14px;color:#0f172a;">{{ $application->user_id }}</td>
                    </tr>
                @endif
            </table>
            <p style="margin:16px 0 0;font-size:12px;color:#64748b;">{{ __('Application #:id', ['id' => $application->id]) }}</p>
        </td>
    </tr>
    <tr>
        <td style="padding:8px 32px 28px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $applicationUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('Review application') }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</x-mail.spotmee-layout>
