@php
    $brand = 'SPOTMEE';
@endphp
<x-mail.spotmee-layout
    :email-title="__('Gym listing approval revoked').' — '.$brand"
    :preheader="$listing->name"
    :header-title="__('Gym listing approval revoked')"
    :header-subtitle="__('Hello :name,', ['name' => $recipientName])"
    :brand="$brand"
    :footer-note="__('You are receiving this because you manage listings on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 8px;font-size:15px;line-height:1.6;color:#334155;">
            <p style="margin:0 0 16px;">{{ __('An administrator has revoked approval for your gym listing on :app. It is no longer published and must be reviewed again before it can go live.', ['app' => config('app.name')]) }}</p>
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="padding:12px 16px;background:#f8fafc;width:38%;font-size:13px;font-weight:700;color:#475569;">{{ __('Listing') }}</td>
                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;"><strong>{{ $listing->name }}</strong> — {{ $listing->city }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:20px 32px 28px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $listingUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('View your listing') }}</a>
                    </td>
                </tr>
            </table>
            <p style="margin:18px 0 0;font-size:14px;color:#64748b;">{{ __('Thanks for using :app.', ['app' => config('app.name')]) }}</p>
        </td>
    </tr>
</x-mail.spotmee-layout>
