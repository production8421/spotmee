@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $sectionHeading }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.frontend.home') }}">{{ __('Frontend') }}</a></li>
                <li class="breadcrumb-item active">{{ $breadcrumbActive }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $sectionHeading }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ __('Content for this section will be configured here.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
