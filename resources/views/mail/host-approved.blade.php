@php
    $brand = 'SPOTMEE';
    $headerTitle = $approvedBy ? __('Your host application was approved') : __('Your host registration is complete');
    $loginUrl = route('login', [], true);
@endphp
<x-mail.spotmee-layout
    :email-title="$headerTitle.' — '.$brand"
    :header-title="$headerTitle"
    :header-subtitle="__('Hello :name,', ['name' => $hostUser->name])"
    :brand="$brand"
    :footer-note="__('You are receiving this because of your host account on :app.', ['app' => config('app.name')])"
>
    <tr>
        <td style="padding:24px 32px 8px;font-size:15px;line-height:1.6;color:#334155;">
            @if ($approvedBy)
                <p style="margin:0 0 16px;">{{ __('Your request to become a host on :app has been approved by an administrator.', ['app' => config('app.name')]) }}</p>
            @else
                <p style="margin:0 0 16px;">{{ __('Your host account on :app is ready. Sign in with the email and temporary password below.', ['app' => config('app.name')]) }}</p>
            @endif
            <p style="margin:0 0 20px;">{{ __('You can sign in with the email address below and the temporary password we generated for you. We recommend changing your password after you log in.') }}</p>
        </td>
    </tr>
    <tr>
        <td style="padding:0 32px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border:1px solid #fcd34d;border-radius:14px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 6px;font-size:13px;color:#78350f;"><strong>{{ __('Email') }}</strong></p>
                        <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#0f172a;">{{ $hostUser->email }}</p>
                        <p style="margin:0 0 6px;font-size:13px;color:#78350f;"><strong>{{ __('Temporary password') }}</strong></p>
                        <p style="margin:0;font-size:18px;font-weight:800;letter-spacing:0.06em;font-family:Consolas,monospace;color:#0f172a;background:#fff;padding:10px 14px;border-radius:8px;border:1px dashed #d97706;display:inline-block;">{{ $plainPassword }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:0 32px 16px;text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="border-radius:999px;background:#006d77;">
                        <a href="{{ $loginUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ __('Sign in') }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:0 32px 24px;font-size:13px;line-height:1.55;color:#64748b;text-align:center;">
            <p style="margin:0;">{{ __('If you did not apply to become a host, contact support immediately.') }}</p>
        </td>
    </tr>
</x-mail.spotmee-layout>
