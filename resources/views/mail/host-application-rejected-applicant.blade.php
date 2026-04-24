@php
    $brand = 'SPOTMEE';
@endphp
<x-mail.spotmee-layout
    :email-title="__('Host application update').' — '.$brand"
    :header-title="__('Update on your host application')"
    :header-subtitle="__('Hello :name,', ['name' => $application->full_name])"
    :brand="$brand"
    :footer-note="__('You are receiving this because you submitted a host application on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 8px;font-size:15px;line-height:1.6;color:#334155;">
            <p style="margin:0 0 16px;">{{ __('After review, we are not able to approve this host application on :app at this time.', ['app' => config('app.name')]) }}</p>
            @if (filled($rejectionMessage))
                <p style="margin:0 0 8px;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Message from the team') }}</p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#0f172a;white-space:pre-wrap;">{{ $rejectionMessage }}</td>
                    </tr>
                </table>
            @endif
            <p style="margin:20px 0 0;font-size:12px;color:#64748b;">{{ __('Application #:id', ['id' => $application->id]) }}</p>
        </td>
    </tr>
</x-mail.spotmee-layout>
