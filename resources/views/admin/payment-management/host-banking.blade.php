@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@include('admin.payment-management.partials.page-header')

@section('content')
    @if (! empty($tableMissing))
        <div class="alert alert-warning outline" role="alert">
            {{ __('The host banking table is not set up yet. Run database migrations on this server.') }}
        </div>
    @endif

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
                    <h5 class="mb-0">{{ __('Host banking details') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Bank account information submitted by hosts for payouts.') }}</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Host') }}</th>
                                    <th>{{ __('Account holder') }}</th>
                                    <th>{{ __('Bank') }}</th>
                                    <th>{{ __('Account type') }}</th>
                                    <th>{{ __('Routing number') }}</th>
                                    <th>{{ __('Account number') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Updated') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bankingDetails as $detail)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $detail->user?->name ?? $detail->hostApplication?->full_name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $detail->user?->email ?? $detail->hostApplication?->email ?? '—' }}</div>
                                        </td>
                                        <td>{{ $detail->account_holder_name }}</td>
                                        <td>{{ $detail->bank_name ?: '—' }}</td>
                                        <td>{{ $detail->accountTypeLabel() }}</td>
                                        <td><code>{{ $detail->maskedRoutingNumber() }}</code></td>
                                        <td><code>{{ $detail->maskedAccountNumber() }}</code></td>
                                        <td>{{ strtoupper((string) $detail->bank_country) }}</td>
                                        <td class="text-muted small">{{ $detail->updated_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            {{ __('No host banking details yet.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bankingDetails->hasPages())
                    <div class="card-footer">
                        {{ $bankingDetails->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
