@extends('layouts.cuba.app')

@section('title', __('Blog posts').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Blog / Community') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Posts') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('Blog posts') }}</h5>
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.blog.create') }}">{{ __('Add blog') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5rem;">{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Community page') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Published') }}</th>
                                    <th>{{ __('Author') }}</th>
                                    <th class="text-muted small">{{ __('Updated') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr>
                                        <td>
                                            @if ($post->featuredImageUrl())
                                                <img src="{{ $post->featuredImageUrl() }}"
                                                     alt=""
                                                     class="rounded object-fit-cover"
                                                     width="56"
                                                     height="56"
                                                     loading="lazy">
                                            @else
                                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted"
                                                      style="width: 56px; height: 56px;">
                                                    <i class="fa-solid fa-image" aria-hidden="true"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $post->title }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 18rem;">/{{ $post->slug }}</div>
                                        </td>
                                        <td class="small">{{ $post->publishForLabel() }}</td>
                                        <td>
                                            @if ($post->is_published)
                                                <span class="badge bg-success">{{ __('Published') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $post->published_at?->format('Y-m-d H:i') ?? '—' }}
                                        </td>
                                        <td class="small">{{ $post->user?->name ?? '—' }}</td>
                                        <td class="text-muted small">{{ $post->updated_at?->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.blog.edit', $post) }}">{{ __('Edit') }}</a>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteBlogPostModal"
                                                    data-delete-url="{{ route('admin.blog.destroy', $post) }}"
                                                    data-confirm-message="{{ e(__('Delete blog post “:title”? This cannot be undone.', ['title' => $post->title])) }}"
                                                >{{ __('Delete') }}</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            {{ __('No blog posts yet.') }}
                                            <a href="{{ route('admin.blog.create') }}">{{ __('Add your first blog post') }}</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($posts->hasPages())
                    <div class="card-footer">
                        {{ $posts->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteBlogPostModal" tabindex="-1" aria-labelledby="deleteBlogPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBlogPostModalLabel">{{ __('Delete blog post') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 text-body" id="deleteBlogPostModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn btn-danger" type="button" id="deleteBlogPostModalConfirm">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <form id="adminDeleteBlogPostForm" method="post" class="d-none" tabindex="-1" aria-hidden="true">
        @csrf
        @method('DELETE')
    </form>

    <script>
        (function () {
            var modalEl = document.getElementById('deleteBlogPostModal');
            var messageEl = document.getElementById('deleteBlogPostModalMessage');
            var form = document.getElementById('adminDeleteBlogPostForm');
            var confirmBtn = document.getElementById('deleteBlogPostModalConfirm');
            if (!modalEl || !messageEl || !form || !confirmBtn) {
                return;
            }
            modalEl.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                if (!trigger || !trigger.getAttribute('data-delete-url')) {
                    return;
                }
                form.setAttribute('action', trigger.getAttribute('data-delete-url'));
                messageEl.textContent = trigger.getAttribute('data-confirm-message') || '';
            });
            confirmBtn.addEventListener('click', function () {
                var action = form.getAttribute('action');
                if (!action) {
                    return;
                }
                form.submit();
            });
        })();
    </script>
@endsection
