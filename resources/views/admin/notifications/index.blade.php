@extends('layouts.cuba.app')

@section('title', __('Notifications').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Notifications') }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-home"></use>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ __('Notifications') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('All notifications') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    @php
                                        $data = is_array($notification->data) ? $notification->data : [];
                                        $title = (string) ($data['title'] ?? __('Notification'));
                                        $body = (string) ($data['body'] ?? '');
                                        $url = isset($data['url']) && is_string($data['url']) && $data['url'] !== '' ? $data['url'] : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $title }}</td>
                                        <td class="text-muted">{{ $body }}</td>
                                        <td>
                                            @if ($notification->read_at === null)
                                                <span class="badge badge-light-warning">{{ __('Unread') }}</span>
                                            @else
                                                <span class="badge badge-light-success">{{ __('Read') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $notification->created_at?->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                @if ($url !== null)
                                                    <a href="{{ $url }}" class="btn btn-sm btn-outline-primary">{{ __('Open') }}</a>
                                                @endif
                                                @if ($notification->read_at === null)
                                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('Mark read') }}</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" onsubmit="return confirm(@json(__('Delete this notification?')));">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">{{ __('No notifications yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

