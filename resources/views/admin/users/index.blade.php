@extends('layouts.cuba.app')

@section('title', __('Users').' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Users') }}</h3>
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
                <li class="breadcrumb-item active">{{ __('Users') }}</li>
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

    @error('delete')
        <div class="alert alert-danger outline alert-dismissible fade show" role="alert">
            {{ $message }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @enderror

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('All users') }}</h5>
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.users.create') }}">{{ __('Add user') }}</a>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Role') }}</th>
                                    <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-light btn-sm" href="{{ route('admin.users.edit', $user) }}">{{ __('Edit') }}</a>
                                            @if ($user->is(auth()->user()))
                                                <span class="text-muted small ms-1">{{ __('(you)') }}</span>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal"
                                                    data-delete-url="{{ route('admin.users.destroy', $user) }}"
                                                    data-confirm-message="{{ e(__('Delete user :name? This cannot be undone.', ['name' => $user->name])) }}"
                                                >{{ __('Delete') }}</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">{{ __('No users found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 pb-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">{{ __('Delete user') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 text-body" id="deleteUserModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn btn-danger" type="button" id="deleteUserModalConfirm">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <form id="adminDeleteUserForm" method="post" class="d-none" tabindex="-1" aria-hidden="true">
        @csrf
        @method('DELETE')
    </form>

    <script>
        (function () {
            var modalEl = document.getElementById('deleteUserModal');
            var messageEl = document.getElementById('deleteUserModalMessage');
            var form = document.getElementById('adminDeleteUserForm');
            var confirmBtn = document.getElementById('deleteUserModalConfirm');
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
