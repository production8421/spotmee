@php
    /** @var \App\Models\BlogPost|null $post */
    $isEdit = $post instanceof \App\Models\BlogPost;
@endphp

<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_title">{{ __('Blog title') }}</label>
    <input
        class="form-control @error('title') is-invalid @enderror"
        type="text"
        name="title"
        id="blog_title"
        value="{{ old('title', $post?->title) }}"
        maxlength="200"
        required
        autocomplete="off"
        placeholder="{{ __('Enter blog title') }}"
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_featured_image">{{ __('Featured image') }}</label>
    @if ($isEdit && $post->featuredImageUrl())
        <div class="mb-3">
            <img src="{{ $post->featuredImageUrl() }}"
                 alt=""
                 class="rounded border object-fit-cover"
                 style="max-width: 240px; max-height: 160px;">
        </div>
        <div class="form-check mb-2">
            <input
                class="form-check-input"
                type="checkbox"
                name="remove_featured_image"
                id="remove_featured_image"
                value="1"
                @checked(old('remove_featured_image'))
            >
            <label class="form-check-label" for="remove_featured_image">{{ __('Remove current image') }}</label>
        </div>
    @endif
    <input
        class="form-control @error('featured_image') is-invalid @enderror"
        type="file"
        name="featured_image"
        id="blog_featured_image"
        accept="image/jpeg,image/png"
    >
    @error('featured_image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <p class="text-muted small mb-0 mt-1">{{ __('Optional. JPG or PNG only. Max 1 MB.') }}</p>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold" for="blog_body">{{ __('Content') }}</label>
    <textarea
        class="form-control js-blog-post-body @error('body') is-invalid @enderror"
        name="body"
        id="blog_body"
        rows="14"
        spellcheck="true"
    >{{ old('body', $post?->body) }}</textarea>
    @error('body')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <p class="text-muted small mb-0 mt-1">
        {{ __('Use the toolbar for headings, fonts, colors, lists, links, and more.') }}
    </p>
</div>

<div class="card border mt-2 mb-0">
    <div class="card-header border-bottom py-3">
        <h6 class="mb-0 fw-semibold">{{ __('Publishing') }}</h6>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <label class="form-label fw-semibold d-block" for="publish_for">{{ __('Community page') }}</label>
            <select
                class="form-select @error('publish_for') is-invalid @enderror"
                name="publish_for"
                id="publish_for"
                required
            >
                @foreach (\App\Models\BlogPost::publishForOptions() as $value => $label)
                    <option value="{{ $value }}" @selected(old('publish_for', $post?->publish_for ?? \App\Models\BlogPost::PUBLISH_FOR_BOTH) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('publish_for')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <p class="text-muted small mb-0 mt-1">
                {{ __('Choose which community page shows this post when it is published — the separate user blog or the host blog, or both.') }}
            </p>
        </div>

        <div class="rounded border bg-light p-3 mb-0">
            <div class="form-check form-switch ps-0">
                <input
                    class="form-check-input ms-0 me-2"
                    type="checkbox"
                    role="switch"
                    name="is_published"
                    id="is_published"
                    value="1"
                    style="width: 2.75rem; height: 1.4rem; cursor: pointer;"
                    @checked(old('is_published', $post?->is_published))
                >
                <label class="form-check-label fw-semibold fs-6 mb-0 user-select-none" for="is_published" style="cursor: pointer;">
                    {{ __('Publish on save') }}
                </label>
            </div>
            <p class="text-muted small mb-0 mt-2 ps-1">
                {{ __('Turn on to publish immediately when you save. Leave off to keep the post as a draft.') }}
            </p>
        </div>
    </div>
</div>
