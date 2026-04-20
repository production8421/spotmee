@extends('layouts.cuba.app')

@section('title', __('Edit coupon').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Edit coupon') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">{{ __('Coupons') }}</a></li>
                <li class="breadcrumb-item active"><code>{{ $coupon->code }}</code></li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Edit coupon') }}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.coupons.update', $coupon) }}" novalidate>
                        @csrf
                        @method('PUT')
                        @include('admin.coupons.partials.form-fields', ['coupon' => $coupon, 'hosts' => $hosts, 'gymListings' => $gymListings])
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">{{ __('Save changes') }}</button>
                            <a class="btn btn-light" href="{{ route('admin.coupons.index') }}">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/coupon-multiselect.js'])
@endpush
