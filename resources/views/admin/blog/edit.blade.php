@extends('layouts.cuba.app')

@section('title', __('Edit blog').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Edit blog') }}</h3>
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
                <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">{{ __('Blog / Community') }}</a></li>
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
                    <h5 class="mb-0">{{ __('Edit blog post') }}</h5>
                </div>
                <div class="card-body">
                    <form id="blog-post-form" method="post" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        @include('admin.blog.partials.form-fields', ['post' => $post])
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">{{ __('Save changes') }}</button>
                            <a class="btn btn-light" href="{{ route('admin.blog.index') }}">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.blog.partials.editor-scripts')
@endpush
