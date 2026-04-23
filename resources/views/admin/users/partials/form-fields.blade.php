@php
    /** @var \App\Models\User|null $user */
    $editing = isset($user);
@endphp

<div class="form-group">
    <label class="col-form-label" for="name">{{ __('Name') }}</label>
    <input
        class="form-control @error('name') is-invalid @enderror"
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $user->name ?? '') }}"
        required
        maxlength="255"
        autocomplete="name"
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="col-form-label" for="email">{{ __('Email address') }}</label>
    <input
        class="form-control @error('email') is-invalid @enderror"
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        required
        autocomplete="email"
    >
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@if ($editing)
    <div class="alert alert-light border mb-3" role="status">
        <strong>{{ __('Password security') }}</strong>
        <p class="mb-0 small text-muted">
            {{ __('User passwords are stored with one-way encryption. They cannot be viewed or recovered—not even by administrators. To help a user sign in, set a new password below or ask them to use “Forgot password” on the login page.') }}
        </p>
    </div>
@endif

<div class="form-group">
    <label class="col-form-label" for="password">{{ $editing ? __('New password (optional)') : __('Password') }}</label>
    <input
        class="form-control @error('password') is-invalid @enderror"
        id="password"
        type="password"
        name="password"
        @if(! $editing) required @endif
        autocomplete="{{ $editing ? 'new-password' : 'new-password' }}"
        placeholder="{{ $editing ? __('Leave blank to keep current password') : '' }}"
    >
    @if($editing)
        <small class="text-muted">{{ __('Leave blank to keep the current password. Enter a new value only if you want to change it.') }}</small>
    @endif
    @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="col-form-label" for="password_confirmation">{{ __('Confirm password') }}</label>
    <input
        class="form-control"
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        @if(! $editing) required @endif
        autocomplete="new-password"
    >
</div>

<div class="form-group">
    <label class="col-form-label" for="role">{{ __('Role') }}</label>
    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
        <option value="">{{ __('Select role') }}</option>
        @foreach (\App\Enums\UserRole::cases() as $roleCase)
            <option
                value="{{ $roleCase->value }}"
                @selected(old('role', $user?->roles->first()?->name) === $roleCase->value)
            >
                {{ $roleCase->value }}
            </option>
        @endforeach
    </select>
    @error('role')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
