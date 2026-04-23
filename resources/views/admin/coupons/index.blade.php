@extends('layouts.cuba.app')

@section('title', __('Coupons').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Coupons') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gym-listings.index') }}">{{ __('Gym Listings') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Coupons') }}</li>
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
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('Gym booking coupons') }}</h5>
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.coupons.create') }}">{{ __('Add coupon') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Hosts / gyms') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Slots redeemed') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-muted small">{{ __('Updated') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <code class="user-select-all">{{ $coupon->code }}</code>
                                            @if (filled($coupon->description))
                                                <div class="text-muted small text-truncate" style="max-width: 14rem">{{ $coupon->description }}</div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if (($coupon->hosts_count ?? 0) === 0 && ($coupon->gym_listings_count ?? 0) === 0)
                                                <span class="text-muted">{{ __('Any') }}</span>
                                            @else
                                                @if (($coupon->hosts_count ?? 0) > 0)
                                                    <div>{{ $coupon->hosts_count }} {{ $coupon->hosts_count === 1 ? __('host') : __('hosts') }}</div>
                                                @endif
                                                @if (($coupon->gym_listings_count ?? 0) > 0)
                                                    <div>{{ $coupon->gym_listings_count }} {{ $coupon->gym_listings_count === 1 ? __('gym') : __('gyms') }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if ($coupon->percent_discount_enabled && $coupon->percent_discount !== null)
                                                <span class="badge bg-info text-dark">
                                                    {{ rtrim(rtrim(number_format((float) $coupon->percent_discount, 2), '0'), '.') }}% {{ __('off total') }}
                                                </span>
                                            @else
                                                {{ (int) $coupon->valid_sessions }}
                                                <span class="text-muted">{{ __('free slots / identity') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ (int) ($coupon->redeemed_confirmed_sum_coupon_applied_slots ?? 0) }}
                                        </td>
                                        <td>
                                            @if ($coupon->is_active)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $coupon->updated_at?->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.coupons.edit', $coupon) }}">{{ __('Edit') }}</a>
                                                <form class="d-inline" method="post" action="{{ route('admin.coupons.toggle-active', $coupon) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($coupon->is_active)
                                                        <button class="btn btn-sm btn-outline-secondary" type="submit">{{ __('Deactivate') }}</button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-success" type="submit">{{ __('Activate') }}</button>
                                                    @endif
                                                </form>
                                                <form class="d-inline" method="post" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm(@json(__('Delete this coupon?')));">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">{{ __('No coupons yet. Create one to get started.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($coupons->hasPages())
                    <div class="card-footer">
                        {{ $coupons->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
