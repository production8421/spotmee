@extends('layouts.cuba.app')

@section('title', __('Media').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Media library') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Media') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @php
        $maxUploadBatch = \App\Support\MediaUploadLimits::maxFilesPerRequest();
    @endphp
    @if (session('status'))
        <div class="alert alert-success outline alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Upload media') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        {{ __('Images: JPEG, PNG, GIF, WebP. Videos: MP4, WebM, MOV. Max ~50 MB per file. This server allows up to :max files in one upload (PHP max_file_uploads). Hold Ctrl (Windows) or ⌘ (Mac) to select multiple. If you select more than :max, only the first :max are uploaded—add the rest in another batch.', ['max' => $maxUploadBatch]) }}
                        {{ __('Files are stored privately and only visible to administrators.') }}
                    </p>
                    @if ($maxUploadBatch < \App\Support\MediaUploadLimits::APP_MAX_FILES)
                        <p class="text-muted small mb-3">
                            {{ __('To raise this limit, set max_file_uploads in php.ini, add a :file file under public/, or run: php -d max_file_uploads=64 artisan serve', ['file' => '.user.ini']) }}
                        </p>
                    @endif
                    <form id="admin-media-upload-form" method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="media-file-input">{{ __('Files') }}</label>
                            <input
                                class="form-control @error('files') is-invalid @enderror @error('files.*') is-invalid @enderror"
                                id="media-file-input"
                                name="files[]"
                                type="file"
                                multiple
                                data-max-files="{{ $maxUploadBatch }}"
                                accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime,.jpg,.jpeg,.png,.gif,.webp,.mp4,.webm,.mov"
                                required
                            >
                            <p class="small text-warning mb-0 mt-1 d-none" id="media-upload-batch-hint" role="status"></p>
                            @error('files')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Upload') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Library') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse ($media as $item)
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="card border shadow-none h-100">
                                    <div class="ratio ratio-16x9 bg-light rounded-top overflow-hidden d-flex align-items-center justify-content-center">
                                        @if ($item->isImage())
                                            <img
                                                class="img-fluid object-fit-contain w-100 h-100"
                                                src="{{ route('admin.media.stream', $item) }}"
                                                alt=""
                                                loading="lazy"
                                            >
                                        @elseif ($item->isVideo())
                                            <video
                                                class="w-100 h-100 object-fit-contain"
                                                src="{{ route('admin.media.stream', $item) }}"
                                                controls
                                                preload="metadata"
                                            ></video>
                                        @else
                                            <span class="text-muted small">{{ __('Preview not available') }}</span>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="small mb-1 text-truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                                        <p class="text-muted small mb-2">
                                            {{ \App\Support\HumanFileSize::format($item->size) }}
                                            · {{ $item->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-muted small mb-2">{{ __('Uploaded by') }} {{ $item->user->name ?? '—' }}</p>
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteMediaModal"
                                            data-delete-url="{{ route('admin.media.destroy', $item) }}"
                                            data-confirm-message="{{ e(__('Delete :name? This cannot be undone.', ['name' => $item->original_name])) }}"
                                        >{{ __('Delete') }}</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">{{ __('No media uploaded yet.') }}</div>
                        @endforelse
                    </div>
                    @if ($media->hasPages())
                        <div class="mt-3">
                            {{ $media->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMediaModalLabel">{{ __('Delete media') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 text-body" id="deleteMediaModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn btn-danger" type="button" id="deleteMediaModalConfirm">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <form id="adminDeleteMediaForm" method="post" class="d-none" tabindex="-1" aria-hidden="true">
        @csrf
        @method('DELETE')
    </form>

    <script>
        (function () {
            var input = document.getElementById('media-file-input');
            var hint = document.getElementById('media-upload-batch-hint');
            var uploadForm = document.getElementById('admin-media-upload-form');
            if (!input || !input.dataset.maxFiles) {
                return;
            }
            var max = parseInt(input.dataset.maxFiles, 10);
            if (!max || max < 1) {
                max = 20;
            }
            var truncateMsg = @json(__('Only the first :max files are being uploaded. Add the rest in another batch.', ['max' => $maxUploadBatch]));

            function trimFiles() {
                if (!input.files || input.files.length <= max) {
                    if (hint) {
                        hint.textContent = '';
                        hint.classList.add('d-none');
                    }
                    return;
                }
                var dt = new DataTransfer();
                for (var i = 0; i < max; i++) {
                    dt.items.add(input.files[i]);
                }
                input.files = dt.files;
                if (hint) {
                    hint.textContent = truncateMsg;
                    hint.classList.remove('d-none');
                }
            }

            input.addEventListener('change', trimFiles);

            if (uploadForm) {
                uploadForm.addEventListener('submit', function () {
                    trimFiles();
                });
            }
        })();
    </script>

    <script>
        (function () {
            var modalEl = document.getElementById('deleteMediaModal');
            var messageEl = document.getElementById('deleteMediaModalMessage');
            var form = document.getElementById('adminDeleteMediaForm');
            var confirmBtn = document.getElementById('deleteMediaModalConfirm');
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
