<div>
    <h5 class="mb-2 text-danger">{{ __('Delete account') }}</h5>
    <p class="text-muted small mb-4">
        {{ __('Once your account is deleted, all data is permanently removed. Download anything you need first.') }}
    </p>

    <button
        class="btn btn-outline-danger"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#confirmUserDeletionModal"
    >{{ __('Delete account') }}</button>

    <div
        class="modal fade @if ($errors->userDeletion->isNotEmpty()) show d-block @endif"
        id="confirmUserDeletionModal"
        tabindex="-1"
        aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="{{ $errors->userDeletion->isNotEmpty() ? 'false' : 'true' }}"
        @if ($errors->userDeletion->isNotEmpty()) style="background-color: rgba(0,0,0,0.5);" @endif
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Confirm deletion') }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">{{ __('Enter your password to permanently delete your account.') }}</p>
                        <div class="form-group mb-0">
                            <label class="col-form-label" for="delete_account_password">{{ __('Password') }}</label>
                            <input
                                class="form-control @if ($errors->userDeletion->has('password')) is-invalid @endif"
                                id="delete_account_password"
                                name="password"
                                type="password"
                                placeholder="{{ __('Password') }}"
                                autocomplete="current-password"
                            >
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn btn-danger" type="submit">{{ __('Delete account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($errors->userDeletion->isNotEmpty())
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
