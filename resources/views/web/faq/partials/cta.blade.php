{{-- Shared CTA block used at the bottom of each FAQ item --}}
@php
    $ctaKicker = $kicker ?? __('Ready to join the SPOTMEE community?');
@endphp

<div class="faq-cta">
    <p class="faq-cta__kicker">{{ $ctaKicker }}</p>
    <div class="faq-cta__action">
        <span>{{ __('To join as a Host') }}</span>
        <a href="{{ route('become-a-host') }}" class="btn btn-primary">
            {{ __('Click Here') }}
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
        </a>
    </div>
</div>
