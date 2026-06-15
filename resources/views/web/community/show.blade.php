@extends('layouts.web.master')

@php
    use App\Models\BlogPost;

    $isHostAudience = $audience === BlogPost::PUBLISH_FOR_HOST;
    $blogLabel = $isHostAudience ? __('Host Blog') : __('User Blog');
@endphp

@section('title', $post->title.' — '.$blogLabel.' — '.config('app.name'))

@include('web.community.partials.blog-styles')

@push('styles')
    <style>
        .community-prose { color: var(--color-ink-700); font-size: 1.05rem; line-height: 1.8; }
        .community-prose > :first-child { margin-top: 0; }
        .community-prose h2, .community-prose h3, .community-prose h4 {
            color: var(--color-ink-900);
            font-weight: 700;
            line-height: 1.3;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
        }
        .community-prose h2 { font-size: 1.625rem; }
        .community-prose h3 { font-size: 1.375rem; }
        .community-prose h4 { font-size: 1.125rem; }
        .community-prose p { margin-bottom: 1rem; }
        .community-prose ul, .community-prose ol { margin: 1rem 0; padding-left: 1.5rem; }
        .community-prose ul { list-style: disc; }
        .community-prose ol { list-style: decimal; }
        .community-prose li { margin-bottom: 0.35rem; }
        .community-prose a { color: var(--color-primary); text-decoration: underline; }
        .community-prose blockquote {
            border-left: 4px solid var(--color-primary);
            margin: 1.25rem 0;
            padding: 0.5rem 0 0.5rem 1rem;
            color: var(--color-ink-600);
            font-style: italic;
        }
        .community-prose img { max-width: 100%; height: auto; border-radius: 1rem; margin: 1.25rem 0; }
        .community-prose table { width: 100%; border-collapse: collapse; margin: 1.25rem 0; }
        .community-prose th, .community-prose td { border: 1px solid var(--color-brand-100); padding: 0.5rem 0.75rem; }
    </style>
@endpush

@section('content')
    <main class="spotmee-main bg-white">
        <section class="blog-masthead">
            <div class="site-container py-8 sm:py-10">
                <nav aria-label="Breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-[13px] font-medium text-[var(--color-ink-500)]">
                    <a href="{{ route('home') }}" class="hover:text-[var(--color-primary)]">{{ __('Home') }}</a>
                    <i class="fa-solid fa-chevron-right text-[9px] opacity-50"></i>
                    <a href="{{ route($indexRoute) }}" class="hover:text-[var(--color-primary)]">{{ $blogLabel }}</a>
                    <i class="fa-solid fa-chevron-right text-[9px] opacity-50"></i>
                    <span class="text-[var(--color-ink-900)] line-clamp-1">{{ $post->title }}</span>
                </nav>

                <div class="max-w-4xl">
                    <span class="blog-tag">{{ __('Article') }}</span>
                    <h1 class="mt-4 text-[30px] font-extrabold leading-[1.1] tracking-tight text-[var(--color-ink-900)] sm:text-[40px]">
                        {{ $post->title }}
                    </h1>
                    <div class="blog-meta mt-5">
                        <time datetime="{{ $post->published_at?->toDateString() }}">
                            {{ $post->published_at?->format('F j, Y') ?? $post->created_at?->format('F j, Y') }}
                        </time>
                        @if ($post->user?->name)
                            <span class="blog-meta__dot"></span>
                            <span>{{ __('By') }} {{ $post->user->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @include($isHostAudience ? 'web.community.partials.host-blog-nav' : 'web.community.partials.user-blog-nav')

        <section class="site-container py-10 sm:py-14">
            <div class="mx-auto max-w-3xl">
                @if ($post->featuredImageUrl())
                    <div class="mb-8 overflow-hidden rounded-[28px] border border-[var(--color-brand-100)] shadow-[var(--shadow-md)]"
                         data-aos="fade-up">
                        <img src="{{ $post->featuredImageUrl() }}"
                             alt="{{ $post->title }}"
                             class="w-full object-cover"
                             loading="eager"
                             decoding="async">
                    </div>
                @endif

                <article class="community-prose rounded-[28px] border border-[var(--color-brand-100)] bg-white p-6 shadow-[var(--shadow-sm)] sm:p-10"
                         data-aos="fade-up">
                    {!! $post->body !!}
                </article>

                @include('web.community.partials.comments-section')

                <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-[var(--color-brand-100)] pt-8"
                     data-aos="fade-up">
                    <a href="{{ route($indexRoute) }}"
                       class="inline-flex items-center gap-2 text-[14px] font-semibold text-[var(--color-primary)] hover:underline">
                        <i class="fa-solid fa-arrow-left text-[12px]"></i>
                        {{ __('Back to') }} {{ $blogLabel }}
                    </a>
                    @if ($isHostAudience)
                        <a href="{{ route('community.user') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-[var(--color-brand-100)] bg-white px-4 py-2 text-[13px] font-semibold text-[var(--color-ink-700)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                            {{ __('User blog') }}
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    @elseif (auth()->user()?->canViewHostBlog())
                        <a href="{{ route('community.host') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-[var(--color-brand-100)] bg-white px-4 py-2 text-[13px] font-semibold text-[var(--color-ink-700)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                            {{ __('Host blog') }}
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    @else
                        <a href="{{ route('become-a-host') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-[var(--color-brand-100)] bg-white px-4 py-2 text-[13px] font-semibold text-[var(--color-ink-700)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                            {{ __('Become a host') }}
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
