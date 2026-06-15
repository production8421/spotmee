<section id="comments" class="mt-12 border-t border-[var(--color-brand-100)] pt-10" data-aos="fade-up">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-[22px] font-bold text-[var(--color-ink-900)]">
            {{ __('Comments') }}
            <span class="text-[16px] font-semibold text-[var(--color-ink-500)]">({{ $post->comments->count() }})</span>
        </h2>
    </div>

    @if (session('status') && str_contains(session('status'), 'comment'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[14px] text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($canComment)
        <form method="post" action="{{ route($commentStoreRoute, ['slug' => $post->slug]) }}" class="mb-8 rounded-[22px] border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] p-5 sm:p-6">
            @csrf
            <label class="mb-2 block text-[14px] font-semibold text-[var(--color-ink-900)]" for="comment_body">
                {{ __('Add a comment') }}
            </label>
            <textarea
                id="comment_body"
                name="body"
                rows="4"
                required
                maxlength="5000"
                placeholder="{{ __('Share your thoughts…') }}"
                class="w-full rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 text-[15px] text-[var(--color-ink-900)] placeholder:text-[var(--color-ink-400)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 @error('body') border-red-400 @enderror"
            >{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
            @enderror
            <button type="submit" class="btn btn-primary btn-sm mt-4">
                {{ __('Post comment') }}
            </button>
        </form>
    @else
        <div class="mb-8 rounded-[22px] border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] px-5 py-4 text-[14px] text-[var(--color-ink-600)]">
            @if ($audience === \App\Models\BlogPost::PUBLISH_FOR_HOST)
                {{ __('Only logged-in hosts can comment on the host blog.') }}
                @guest
                    <a href="{{ route('login') }}" class="ms-1 font-semibold text-[var(--color-primary)] hover:underline">{{ __('Log in') }}</a>
                @endguest
            @else
                {{ __('Only logged-in users can comment on the user blog.') }}
                @guest
                    <a href="{{ route('login') }}" class="ms-1 font-semibold text-[var(--color-primary)] hover:underline">{{ __('Log in') }}</a>
                @endguest
            @endif
        </div>
    @endif

    @if ($post->comments->isEmpty())
        <p class="text-[14px] text-[var(--color-ink-500)]">{{ __('No comments yet. Be the first to share your thoughts.') }}</p>
    @else
        <ul class="space-y-4">
            @foreach ($post->comments as $comment)
                <li class="rounded-[20px] border border-[var(--color-brand-100)] bg-white p-5 shadow-[var(--shadow-sm)]">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-[14px] font-bold text-white">
                            {{ mb_strtoupper(mb_substr($comment->user?->name ?? 'U', 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                <span class="text-[14px] font-semibold text-[var(--color-ink-900)]">
                                    {{ $comment->user?->name ?? __('User') }}
                                </span>
                                <time class="text-[12px] text-[var(--color-ink-500)]" datetime="{{ $comment->created_at?->toIso8601String() }}">
                                    {{ $comment->created_at?->format('M j, Y g:i A') }}
                                </time>
                            </div>
                            <p class="mt-2 whitespace-pre-wrap text-[15px] leading-relaxed text-[var(--color-ink-700)]">{{ $comment->body }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
