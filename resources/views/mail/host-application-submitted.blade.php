<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('New host application') }}</title>
</head>
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; line-height: 1.5; color: #2f2f3b; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 1.25rem; margin: 0 0 16px;">{{ __('New host application') }}</h1>
    <p style="margin: 0 0 20px; color: #52526c;">{{ __('Someone submitted a host application on :app.', ['app' => config('app.name')]) }}</p>

    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tbody>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee; width: 40%;">{{ __('Full Name') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->full_name }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Date of Birth') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->date_of_birth?->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Social Security Number') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->social_security_number ?: __('Not provided') }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Phone Number') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->phone }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Email Address') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->email }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Street Address') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->street_address }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('City') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->city }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('State') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->state }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Postal Code') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->postal_code }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top; border-bottom: 1px solid #eee;">{{ __('Description') }}</th>
                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $application->description ?: '—' }}</td>
            </tr>
            @if ($application->user_id)
                <tr>
                    <th style="text-align: left; padding: 8px 12px 8px 0; vertical-align: top;">{{ __('Linked user ID') }}</th>
                    <td style="padding: 8px 0;">{{ $application->user_id }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p style="margin: 24px 0 0; font-size: 12px; color: #838383;">{{ __('Application #:id', ['id' => $application->id]) }}</p>
</body>
</html>
