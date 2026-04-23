<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Host application update') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h2 style="margin: 0 0 12px;">{{ __('Update on your host application') }}</h2>
    <p>{{ __('Hello :name,', ['name' => $application->full_name]) }}</p>
    <p>{{ __('After review, we are not able to approve this host application on :app at this time.', ['app' => config('app.name')]) }}</p>
    @if (filled($rejectionMessage))
        <p><strong>{{ __('Message from the team') }}</strong></p>
        <p style="white-space: pre-wrap;">{{ $rejectionMessage }}</p>
    @endif
    <p style="color:#666;font-size:14px;">{{ __('Application #:id', ['id' => $application->id]) }}</p>
    <p>— {{ config('app.name') }}</p>
</body>
</html>
