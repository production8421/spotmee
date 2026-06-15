@extends('layouts.web.master')

@section('title', __('Write article').' — '.$blogLabel.' — '.config('app.name'))

@include('web.community.partials.blog-styles')

@section('content')
    @php
        $isHostAudience = $audience === \App\Models\BlogPost::PUBLISH_FOR_HOST;
    @endphp

    <main class="spotmee-main bg-white">
        <section class="blog-masthead">
            <div class="site-container py-8 sm:py-10">
                <nav aria-label="Breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-[13px] font-medium text-[var(--color-ink-500)]">
                    <a href="{{ route('home') }}" class="hover:text-[var(--color-primary)]">{{ __('Home') }}</a>
                    <i class="fa-solid fa-chevron-right text-[9px] opacity-50"></i>
                    <a href="{{ route($indexRoute) }}" class="hover:text-[var(--color-primary)]">{{ $blogLabel }}</a>
                    <i class="fa-solid fa-chevron-right text-[9px] opacity-50"></i>
                    <span class="text-[var(--color-ink-900)]">{{ __('Write article') }}</span>
                </nav>
                <h1 class="text-[30px] font-extrabold tracking-tight text-[var(--color-ink-900)] sm:text-[38px]">
                    {{ __('Write a new article') }}
                </h1>
                <p class="mt-3 max-w-2xl text-[15px] text-[var(--color-ink-500)]">
                    {{ $isHostAudience
                        ? __('Share hosting tips, updates, or community news with other SPOTMEE hosts.')
                        : __('Share training tips, experiences, or updates with the SPOTMEE community.') }}
                </p>
            </div>
        </section>

        @include($isHostAudience ? 'web.community.partials.host-blog-nav' : 'web.community.partials.user-blog-nav')

        <section class="site-container py-10 sm:py-14">
            <div class="mx-auto max-w-3xl rounded-[28px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] sm:p-10">
                <form id="blog-post-form" method="post" action="{{ route($storeRoute) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @include('web.community.partials.post-form-fields')
                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Publish article') }}
                        </button>
                        <a href="{{ route($indexRoute) }}" class="btn btn-outline">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    @include('admin.blog.partials.editor-scripts')
@endpush
