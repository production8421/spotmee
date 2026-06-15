<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_title">{{ __('Title') }}</label>
    <input
        class="w-full rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 text-[15px] text-[var(--color-ink-900)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 @error('title') border-red-400 @enderror"
        type="text"
        name="title"
        id="blog_title"
        value="{{ old('title') }}"
        maxlength="200"
        required
        autocomplete="off"
        placeholder="{{ __('Write your article title') }}"
    >
    @error('title')
        <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_featured_image">{{ __('Featured image') }}</label>
    <input
        class="w-full rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 text-[14px] @error('featured_image') border-red-400 @enderror"
        type="file"
        name="featured_image"
        id="blog_featured_image"
        accept="image/jpeg,image/png"
    >
    @error('featured_image')
        <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
    @enderror
    <p class="mt-1 text-[13px] text-[var(--color-ink-500)]">{{ __('Optional. JPG or PNG only. Max 1 MB.') }}</p>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_body">{{ __('Article') }}</label>
    <textarea
        class="w-full rounded-xl border border-[var(--color-brand-100)] bg-white px-4 py-3 text-[15px] js-blog-post-body @error('body') border-red-400 @enderror"
        name="body"
        id="blog_body"
        rows="14"
        spellcheck="true"
    >{{ old('body') }}</textarea>
    @error('body')
        <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] p-4">
    <label class="inline-flex cursor-pointer items-center gap-3">
        <input
            type="checkbox"
            name="is_published"
            value="1"
            class="h-5 w-5 rounded border-[var(--color-brand-200)] text-[var(--color-primary)] focus:ring-[var(--color-primary)]"
            @checked(old('is_published', true))
        >
        <span class="text-[14px] font-semibold text-[var(--color-ink-900)]">{{ __('Publish immediately') }}</span>
    </label>
    <p class="mt-2 text-[13px] text-[var(--color-ink-500)]">{{ __('Uncheck to save as a draft (visible to you in admin only).') }}</p>
</div>
