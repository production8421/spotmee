@extends('layouts.cuba.app')

@php
    $gymRoutePrefix = $gymRoutePrefix ?? 'admin.gym-listings';
@endphp

@section('title', __('Edit gym listing').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Edit gym listing') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route($gymRoutePrefix.'.index') }}">{{ __('Gym listings') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $gymListing->name }}</h5>
                </div>
                <div class="card-body">
                    <form class="gym-listing-form" method="POST" action="{{ route($gymRoutePrefix.'.update', $gymListing) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        @include('admin.gym-listings.partials.form-fields', ['gymListing' => $gymListing])
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">{{ __('Save changes') }}</button>
                            <a class="btn btn-light" href="{{ route($gymRoutePrefix.'.index') }}">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
