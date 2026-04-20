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

<div class="form-group">
    <label class="col-form-label" for="password">{{ __('Password') }}</label>
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
        <small class="text-muted">{{ __('Leave blank to keep the current password.') }}</small>
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
