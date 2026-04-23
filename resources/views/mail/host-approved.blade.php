<x-mail::message>
@if ($approvedBy)
# {{ __('Your host application was approved') }}
@else
# {{ __('Your host registration is complete') }}
@endif

{{ __('Hello :name,', ['name' => $hostUser->name]) }}

@if ($approvedBy)
{{ __('Your request to become a host on :app has been approved by an administrator.', ['app' => config('app.name')]) }}
@else
{{ __('Your host account on :app is ready. Sign in with the email and temporary password below.', ['app' => config('app.name')]) }}
@endif

{{ __('You can sign in with the email address below and the temporary password we generated for you. We recommend changing your password after you log in.') }}

<x-mail::panel>
**{{ __('Email') }}:** {{ $hostUser->email }}  
**{{ __('Temporary password') }}:** {{ $plainPassword }}
</x-mail::panel>

<x-mail::button :url="route('login')">
{{ __('Sign in') }}
</x-mail::button>

{{ __('If you did not apply to become a host, contact support immediately.') }}

{{ __('Thanks,') }}  
{{ config('app.name') }}
</x-mail::message>
