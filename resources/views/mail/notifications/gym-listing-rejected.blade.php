@php
    $brand = 'SPOTMEE';
@endphp
<x-mail.spotmee-layout
    :email-title="__('Gym listing not approved').' — '.$brand"
    :preheader="$listing->name"
    :header-title="__('Gym listing not approved')"
    :header-subtitle="__('Hello :name,', ['name' => $recipientName])"
    :brand="$brand"
    :footer-note="__('You are receiving this because you manage listings on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 8px;font-size:15px;line-height:1.6;color:#334155;">
            <p style="margin:0 0 16px;">{{ __('An administrator did not approve your gym listing submission on :app at this time.', ['app' => config('app.name')]) }}</p>
            <p style="margin:0 0 12px;"><strong>{{ $listing->name }}</strong> — {{ $listing->city }}</p>
            @if ($rejectionMessage !== null)
                <p style="margin:0 0 8px;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#006d77;">{{ __('Message from administrator:') }}</p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:16px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#0f172a;white-space:pre-wrap;">{{ $rejectionMessage }}</td>
                    </tr>
                </table>
            @endif
            <p style="margin:0 0 20px;">{{ __('You can edit your listing and save changes to send it back for review.') }}</p>
        </td>
    </tr>
    <tr>
        <td style="padding:0 32px 28px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $editListingUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('Edit listing') }}</a>
                    </td>
                </tr>
            </table>
            <p style="margin:18px 0 0;font-size:14px;color:#64748b;">{{ __('Thanks for using :app.', ['app' => config('app.name')]) }}</p>
        </td>
    </tr>
</x-mail.spotmee-layout>
