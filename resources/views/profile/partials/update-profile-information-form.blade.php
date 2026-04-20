<div>
    <h5 class="mb-2">{{ __('Profile information') }}</h5>
    <p class="text-muted small mb-4">{{ __("Update your account's profile information and email address.") }}</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="theme-form">
        @csrf
        @method('patch')

        <div class="form-group">
            <label class="col-form-label" for="name">{{ __('Name') }}</label>
            <input
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="col-form-label" for="email">{{ __('Email address') }}</label>
            <input
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="small text-muted mt-2 mb-0">
                    {{ __('Your email address is unverified.') }}
                    <button type="submit" form="send-verification" class="btn btn-link btn-sm p-0 align-baseline text-decoration-underline">
                        {{ __('Resend verification email') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="small text-success mt-2 mb-0">{{ __('A new verification link has been sent.') }}</p>
                @endif
            @endif
        </div>

        <div class="form-group mb-0 d-flex flex-wrap align-items-center gap-3">
            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="small text-success">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</div>
