<div>
    <h5 class="mb-2">{{ __('Update password') }}</h5>
    <p class="text-muted small mb-4">{{ __('Use a long, random password to keep your account secure.') }}</p>

    <form method="post" action="{{ route('password.update') }}" class="theme-form">
        @csrf
        @method('put')

        <div class="form-group">
            <label class="col-form-label" for="update_password_current_password">{{ __('Current password') }}</label>
            <input
                class="form-control @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
            >
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="col-form-label" for="update_password_password">{{ __('New password') }}</label>
            <input
                class="form-control @if ($errors->updatePassword->has('password')) is-invalid @endif"
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
            >
            @error('password', 'updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="col-form-label" for="update_password_password_confirmation">{{ __('Confirm password') }}</label>
            <input
                class="form-control @if ($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
            >
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-0 d-flex flex-wrap align-items-center gap-3">
            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
            @if (session('status') === 'password-updated')
                <span class="small text-success">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</div>
