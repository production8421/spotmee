@php
    $gymRoutePrefix = $gymRoutePrefix ?? 'admin.gym-listings';
@endphp
@if ($gymRoutePrefix === 'host.gym-listings')
    <div class="border rounded p-3 mb-4 bg-light">
        <div class="d-flex gap-3 align-items-start">
            <input
                class="form-check-input mt-1 @error('waiver_terms_accepted') is-invalid @enderror"
                id="waiver_terms_accepted"
                type="checkbox"
                name="waiver_terms_accepted"
                value="1"
                @checked(old('waiver_terms_accepted'))
            >
            <div class="flex-grow-1 min-w-0">
                <label class="form-check-label mb-0" for="waiver_terms_accepted">
                    {{ __('I agree to the') }}
                    <a href="{{ route('legal.waiver-host') }}" class="text-primary text-decoration-underline" target="_blank" rel="noopener noreferrer">{{ __('Host waiver of liability') }}</a>
                    {{ __('and') }}
                    <a href="{{ route('legal.waiver-user') }}" class="text-primary text-decoration-underline" target="_blank" rel="noopener noreferrer">{{ __('User waiver of liability') }}</a>
                </label>
                @error('waiver_terms_accepted')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
@endif
