@extends('layouts.cuba.app')

@section('title', __('Banking details').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Banking details') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Banking details') }}</li>
            </ol>
        </div>
    </div>
@endsection

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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $bankingDetail ? __('Update banking details') : __('Add banking details') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Add or update the bank account we use to send your host payouts. After you save, we link this account for automatic Stripe transfers — no separate Stripe setup is required.') }}</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border mb-4" role="note">
                        <i class="fa-solid fa-lock text-primary me-2" aria-hidden="true"></i>
                        {{ __('Your banking information is encrypted and only used for host payouts.') }}
                    </div>

                    @if ($bankingDetail)
                        <div class="alert alert-info outline mb-4" role="status">
                            <div class="fw-semibold mb-1">{{ __('Current account on file') }}</div>
                            <div class="small">
                                {{ __('Routing number') }}: <code>{{ $bankingDetail->maskedRoutingNumber() }}</code><br>
                                {{ __('Account number') }}: <code>{{ $bankingDetail->maskedAccountNumber() }}</code>
                            </div>
                            <div class="small text-muted mt-2">{{ __('Re-enter routing and account numbers below to update them.') }}</div>
                        </div>
                    @endif

                    @if (empty($tableMissing))
                        <form method="POST" action="{{ route('host.banking.update') }}" novalidate autocomplete="off">
                            @csrf
                            @method('PUT')

                            @include('host.partials.banking-form-fields', ['bankingDetail' => $bankingDetail])

                            <div class="pt-3">
                                <button class="btn btn-primary" type="submit">
                                    {{ $bankingDetail ? __('Save banking details') : __('Add banking details') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
