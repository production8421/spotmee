@extends('layouts.web.master')

@php
    use App\Models\BlogPost;

    $isHostAudience = $audience === BlogPost::PUBLISH_FOR_HOST;
    $blogLabel = $isHostAudience ? __('Host Blog') : __('User Blog');
    $featuredPost = $posts->onFirstPage() && $posts->count() > 0 ? $posts->first() : null;
    $listPosts = $featuredPost ? $posts->slice(1) : $posts;
    $recentPosts = $posts->take(5);
@endphp

@section('title', $blogLabel.' — '.config('app.name'))

@include('web.community.partials.blog-styles')

@section('content')
    <main class="spotmee-main bg-white">
        <section class="blog-masthead">
            <div class="site-container py-10 sm:py-12">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl" data-aos="fade-up">
                        <span class="blog-masthead__eyebrow">
                            <i class="fa-solid fa-pen-nib"></i>
                            {{ $blogLabel }}
                        </span>
                        <h1 class="mt-4 text-[34px] font-extrabold leading-[1.05] tracking-tight text-[var(--color-ink-900)] sm:text-[42px]">
                            {{ $heroTitle }}
                        </h1>
                        <p class="mt-4 text-[16px] leading-relaxed text-[var(--color-ink-500)]">
                            {{ $heroSubtitle }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3" data-aos="fade-up" data-aos-delay="100">
                        <span class="blog-tag">
                            <i class="fa-solid fa-layer-group"></i>
                            {{ trans_choice('{0} No posts|{1} 1 article|[2,*] :count articles', $posts->total(), ['count' => $posts->total()]) }}
                        </span>
                        @if (! empty($canContribute))
                            <a href="{{ route($createRoute) }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-pen text-[11px]"></i>
                                {{ __('Write article') }}
                            </a>
                        @endif
                        @if ($isHostAudience)
                            <a href="{{ route('community.user') }}" class="btn btn-outline btn-sm">
                                {{ __('User blog') }}
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        @else
                            @if (auth()->user()?->canViewHostBlog())
                                <a href="{{ route('community.host') }}" class="btn btn-outline btn-sm">
                                    {{ __('Host blog') }}
                                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </a>
                            @else
                                <a href="{{ route('become-a-host') }}" class="btn btn-outline btn-sm">
                                    {{ __('Become a host') }}
                                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @include($isHostAudience ? 'web.community.partials.host-blog-nav' : 'web.community.partials.user-blog-nav')

        <section class="site-container py-10 sm:py-14">
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-8">
                    <div class="lg:col-span-8">
                        @if ($featuredPost)
                            <article class="blog-featured group mb-10" data-aos="fade-up">
                                <a href="{{ route($showRoute, ['slug' => $featuredPost->slug]) }}" class="blog-featured__media block overflow-hidden">
                                    @if ($featuredPost->featuredImageUrl())
                                        <img src="{{ $featuredPost->featuredImageUrl() }}"
                                             alt="{{ $featuredPost->title }}"
                                             loading="eager"
                                             decoding="async">
                                    @else
                                        <div class="flex h-full min-h-[240px] items-center justify-center text-[var(--color-brand-300)]">
                                            <i class="fa-solid fa-newspaper text-6xl"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="blog-featured__body">
                                    <span class="blog-tag">{{ __('Featured') }}</span>
                                    <div class="blog-meta mt-4">
                                        <time datetime="{{ $featuredPost->published_at?->toDateString() }}">
                                            {{ $featuredPost->published_at?->format('F j, Y') ?? $featuredPost->created_at?->format('F j, Y') }}
                                        </time>
                                        @if ($featuredPost->user?->name)
                                            <span class="blog-meta__dot"></span>
                                            <span>{{ __('By') }} {{ $featuredPost->user->name }}</span>
                                        @endif
                                    </div>
                                    <h2 class="mt-4 text-[28px] font-bold leading-tight text-[var(--color-ink-900)] transition-colors group-hover:text-[var(--color-primary)] sm:text-[32px]">
                                        <a href="{{ route($showRoute, ['slug' => $featuredPost->slug]) }}">{{ $featuredPost->title }}</a>
                                    </h2>
                                    <p class="mt-4 line-clamp-4 text-[15px] leading-relaxed text-[var(--color-ink-500)]">
                                        {{ $featuredPost->excerpt(260) }}
                                    </p>
                                    <a href="{{ route($showRoute, ['slug' => $featuredPost->slug]) }}"
                                       class="mt-6 inline-flex items-center gap-2 text-[14px] font-semibold text-[var(--color-primary)]">
                                        {{ __('Continue reading') }}
                                        <i class="fa-solid fa-arrow-right text-[12px]"></i>
                                    </a>
                                </div>
                            </article>
                        @endif

                        @if ($listPosts->count() > 0)
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <h2 class="text-[20px] font-bold text-[var(--color-ink-900)]">{{ __('Latest articles') }}</h2>
                            </div>
                            <div class="blog-card-list">
                                @foreach ($listPosts as $post)
                                    <article class="blog-card-row group" data-aos="fade-up">
                                        <a href="{{ route($showRoute, ['slug' => $post->slug]) }}" class="blog-card-row__media block overflow-hidden">
                                            @if ($post->featuredImageUrl())
                                                <img src="{{ $post->featuredImageUrl() }}"
                                                     alt="{{ $post->title }}"
                                                     loading="lazy"
                                                     decoding="async">
                                            @else
                                                <div class="flex h-full min-h-[180px] items-center justify-center text-[var(--color-brand-300)]">
                                                    <i class="fa-solid fa-image text-3xl"></i>
                                                </div>
                                            @endif
                                        </a>
                                        <div class="blog-card-row__body">
                                            <div class="blog-meta">
                                                <time datetime="{{ $post->published_at?->toDateString() }}">
                                                    {{ $post->published_at?->format('M j, Y') ?? $post->created_at?->format('M j, Y') }}
                                                </time>
                                                @if ($post->user?->name)
                                                    <span class="blog-meta__dot"></span>
                                                    <span>{{ $post->user->name }}</span>
                                                @endif
                                            </div>
                                            <h3 class="mt-3 text-[22px] font-bold leading-snug text-[var(--color-ink-900)] transition-colors group-hover:text-[var(--color-primary)]">
                                                <a href="{{ route($showRoute, ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                                            </h3>
                                            <p class="mt-3 line-clamp-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                                                {{ $post->excerpt() }}
                                            </p>
                                            <a href="{{ route($showRoute, ['slug' => $post->slug]) }}"
                                               class="mt-4 inline-flex items-center gap-2 text-[13px] font-semibold text-[var(--color-primary)]">
                                                {{ __('Read article') }}
                                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        @if ($posts->hasPages())
                            <div class="mt-10">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    </div>

                    <aside class="lg:col-span-4">
                        <div class="blog-sidebar-card" data-aos="fade-up">
                            <h3 class="text-[16px] font-bold text-[var(--color-ink-900)]">{{ __('About this blog') }}</h3>
                            <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                                @if ($isHostAudience)
                                    {{ __('Updates, hosting tips, and community news for SPOTMEE hosts.') }}
                                @else
                                    {{ __('Training tips, platform updates, and stories for guests and subscribers.') }}
                                @endif
                            </p>
                        </div>

                        @if ($recentPosts->count() > 0)
                            <div class="blog-sidebar-card" data-aos="fade-up" data-aos-delay="50">
                                <h3 class="text-[16px] font-bold text-[var(--color-ink-900)]">{{ __('Recent posts') }}</h3>
                                <ul class="mt-4 space-y-4">
                                    @foreach ($recentPosts as $recent)
                                        <li class="border-b border-[var(--color-brand-100)] pb-4 last:border-0 last:pb-0">
                                            <a href="{{ route($showRoute, ['slug' => $recent->slug]) }}"
                                               class="block text-[14px] font-semibold leading-snug text-[var(--color-ink-900)] hover:text-[var(--color-primary)]">
                                                {{ $recent->title }}
                                            </a>
                                            <time class="mt-1 block text-[12px] text-[var(--color-ink-500)]">
                                                {{ $recent->published_at?->format('M j, Y') ?? $recent->created_at?->format('M j, Y') }}
                                            </time>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="blog-sidebar-card" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="text-[16px] font-bold text-[var(--color-ink-900)]">{{ __('Explore SPOTMEE') }}</h3>
                            <div class="mt-4 flex flex-col gap-2">
                                @if (! empty($canContribute))
                                    <a href="{{ route($createRoute) }}" class="btn btn-primary btn-sm justify-center">
                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                        {{ __('Write article') }}
                                    </a>
                                @endif
                                @if ($isHostAudience)
                                    <a href="{{ route('become-a-host') }}" class="btn btn-primary btn-sm justify-center">{{ __('Become a host') }}</a>
                                    <a href="{{ route('community.user') }}" class="btn btn-outline btn-sm justify-center">{{ __('User blog') }}</a>
                                @else
                                    <a href="{{ route('find-a-gym') }}" class="btn btn-primary btn-sm justify-center">{{ __('Find a gym') }}</a>
                                    @if (auth()->user()?->canViewHostBlog())
                                        <a href="{{ route('community.host') }}" class="btn btn-outline btn-sm justify-center">{{ __('Host blog') }}</a>
                                    @else
                                        <a href="{{ route('become-a-host') }}" class="btn btn-outline btn-sm justify-center">{{ __('Become a host') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </aside>
                </div>
            @else
                <div class="mx-auto max-w-xl rounded-[28px] border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] px-8 py-14 text-center"
                     data-aos="fade-up">
                    <span class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white text-[var(--color-primary)] shadow-[var(--shadow-sm)]">
                        <i class="fa-solid fa-newspaper text-2xl"></i>
                    </span>
                    <h2 class="text-[22px] font-bold text-[var(--color-ink-900)]">{{ __('No articles yet') }}</h2>
                    <p class="mt-3 text-[15px] text-[var(--color-ink-500)]">
                        {{ __('Check back soon for new posts on this blog.') }}
                    </p>
                </div>
            @endif
        </section>
    </main>
@endsection
