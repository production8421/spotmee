@php
    /** @var int $step Current step: 1 = overview, 2 = application, 3 = complete */
    $step = max(1, min(3, (int) ($step ?? 1)));
    $steps = [
        1 => __('Overview'),
        2 => __('Application'),
        3 => __('Complete'),
    ];
@endphp

@once
    @push('styles')
        <style>
            .host-apply-progress {
                --hap-primary: var(--theme-default, #7366ff);
                --hap-done: #198754;
                --hap-muted: #94a3b8;
                --hap-line: #e2e8f0;
            }
            .host-apply-progress__list {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                list-style: none;
                margin: 0;
                padding: 0;
                gap: 0.25rem;
            }
            .host-apply-progress__item {
                flex: 1 1 0;
                min-width: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                position: relative;
            }
            .host-apply-progress__item:not(:last-child)::after {
                content: '';
                position: absolute;
                top: 1rem;
                left: calc(50% + 1.1rem);
                width: calc(100% - 2.2rem);
                height: 2px;
                background: var(--hap-line);
                z-index: 0;
            }
            .host-apply-progress__item.is-done:not(:last-child)::after {
                background: var(--hap-done);
            }
            .host-apply-progress__marker {
                width: 2rem;
                height: 2rem;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8125rem;
                font-weight: 600;
                border: 2px solid var(--hap-line);
                background: #fff;
                color: var(--hap-muted);
                position: relative;
                z-index: 1;
                flex-shrink: 0;
            }
            .host-apply-progress__item.is-active .host-apply-progress__marker {
                border-color: var(--hap-primary);
                background: var(--hap-primary);
                color: #fff;
                box-shadow: 0 0 0 4px rgba(115, 102, 255, 0.2);
            }
            .host-apply-progress__item.is-done .host-apply-progress__marker {
                border-color: var(--hap-done);
                background: var(--hap-done);
                color: #fff;
            }
            .host-apply-progress__label {
                margin-top: 0.5rem;
                font-size: 0.75rem;
                line-height: 1.25;
                color: var(--hap-muted);
                max-width: 6.5rem;
            }
            .host-apply-progress__item.is-active .host-apply-progress__label,
            .host-apply-progress__item.is-done .host-apply-progress__label {
                color: #334155;
                font-weight: 600;
            }
            .host-apply-progress__caption {
                font-size: 0.8125rem;
                color: var(--hap-muted);
                text-align: center;
            }
            @media (max-width: 380px) {
                .host-apply-progress__label {
                    font-size: 0.6875rem;
                }
            }
        </style>
    @endpush
@endonce

<nav class="host-apply-progress mb-4" aria-label="{{ __('Host registration progress') }}">
    <ol class="host-apply-progress__list">
        @foreach ($steps as $num => $label)
            @php
                $state = $num < $step ? 'is-done' : ($num === $step ? 'is-active' : '');
            @endphp
            <li class="host-apply-progress__item {{ $state }}" @if ($num === $step) aria-current="step" @endif>
                <span class="host-apply-progress__marker" aria-hidden="true">
                    @if ($num < $step)
                        <i class="fa-solid fa-check" style="font-size: 0.75rem;"></i>
                    @else
                        {{ $num }}
                    @endif
                </span>
                <span class="host-apply-progress__label">{{ $label }}</span>
            </li>
        @endforeach
    </ol>
    <p class="host-apply-progress__caption mt-2 mb-0">
        {{ __('Step :current of :total', ['current' => $step, 'total' => count($steps)]) }}
        @if ($step === 1)
            <span class="d-none d-sm-inline"> — {{ __('Review requirements and accept terms') }}</span>
        @elseif ($step === 2)
            <span class="d-none d-sm-inline"> — {{ __('Enter your details') }}</span>
        @else
            <span class="d-none d-sm-inline"> — {{ __('Application received') }}</span>
        @endif
    </p>
</nav>
