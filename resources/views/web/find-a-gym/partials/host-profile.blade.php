@php
    $host = $host ?? $listing->user ?? null;
@endphp

@if ($host)
    <div class="rounded-[24px] border border-[var(--color-brand-100)] bg-white p-7 shadow-[var(--shadow-sm)] sm:p-8"
         data-aos="fade-up">
        <h2 class="flex items-center gap-2 text-[22px] font-bold text-[var(--color-ink-900)]">
            <i class="fa-solid fa-user text-[20px] text-[var(--color-primary)]"></i>
            {{ __('Your host') }}
        </h2>

        <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center">
            @if ($host->profilePhotoUrl())
                <img
                    src="{{ $host->profilePhotoUrl() }}"
                    alt="{{ $host->name }}"
                    class="h-20 w-20 shrink-0 rounded-full border-2 border-[var(--color-brand-100)] object-cover shadow-[var(--shadow-sm)]"
                    width="80"
                    height="80"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div
                    class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full border-2 border-[var(--color-brand-100)] bg-[var(--color-brand-50)] text-[22px] font-bold text-[var(--color-primary)] shadow-[var(--shadow-sm)]"
                    aria-hidden="true"
                >
                    {{ $host->profileInitials() }}
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <p class="text-[18px] font-bold text-[var(--color-ink-900)]">{{ $host->name }}</p>
                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-[12px] font-bold uppercase tracking-wide text-[var(--color-primary)]">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        {{ __('Verified host') }}
                    </span>
                </div>
                <p class="mt-3 text-[14px] leading-relaxed text-[var(--color-ink-500)]">
                    {{ __('Book with confidence — this space is hosted by a verified member of the SPOTMEE community.') }}
                </p>
            </div>
        </div>
    </div>
@endif
