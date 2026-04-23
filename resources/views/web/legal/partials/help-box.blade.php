{{-- Shared "Need help?" card used at the bottom of every legal page --}}
<div class="mt-12 overflow-hidden rounded-[24px] border border-[var(--color-brand-100)] bg-gradient-to-br from-[var(--color-brand-50)] to-white p-8 sm:p-10"
     data-aos="fade-up">
    <div class="grid grid-cols-1 items-center gap-6 md:grid-cols-12">
        <div class="md:col-span-8">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[var(--color-primary)] shadow-sm ring-1 ring-[var(--color-brand-100)]">
                <i class="fa-solid fa-headset text-lg"></i>
            </span>
            <h3 class="mt-4 text-[20px] font-bold text-[var(--color-ink-900)] sm:text-[24px]">
                {{ __('Questions about this page?') }}
            </h3>
            <p class="mt-2 max-w-xl text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                {{ __('Our team is happy to walk you through anything — policies, safety, cancellations, or how bookings work on SPOTMEE.') }}
            </p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row md:col-span-4 md:justify-end">
            <a href="{{ route('contact') }}" class="btn btn-primary justify-center">
                <i class="fa-solid fa-envelope text-[12px]"></i>
                {{ __('Contact us') }}
            </a>
            <a href="{{ route('faq') }}" class="btn btn-outline justify-center">
                {{ __('Visit FAQ') }}
                <i class="fa-solid fa-arrow-right text-[12px]"></i>
            </a>
        </div>
    </div>
</div>
