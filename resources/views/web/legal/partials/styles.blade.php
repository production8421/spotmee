{{-- Shared styles for all legal pages --}}
<style>
    .legal-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 1024px) {
        .legal-layout { grid-template-columns: 260px minmax(0, 1fr); gap: 3rem; }
    }

    .legal-toc {
        position: relative;
    }
    @media (min-width: 1024px) {
        .legal-toc { position: sticky; top: 110px; align-self: start; }
    }

    .legal-toc__title {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--color-ink-500);
        margin-bottom: 12px;
    }

    .legal-toc__list {
        list-style: none;
        padding: 0;
        margin: 0;
        border-left: 2px solid var(--color-brand-100);
    }

    .legal-toc__list a {
        display: block;
        padding: 8px 14px;
        font-size: 13px;
        line-height: 1.45;
        color: var(--color-ink-500);
        border-left: 2px solid transparent;
        margin-left: -2px;
        text-decoration: none;
        transition: color 0.15s ease, border-color 0.15s ease, background 0.15s ease;
        border-radius: 0 8px 8px 0;
    }
    .legal-toc__list a:hover {
        color: var(--color-primary);
        background: var(--color-brand-50);
    }
    .legal-toc__list a.is-active {
        color: var(--color-primary);
        border-left-color: var(--color-primary);
        font-weight: 600;
    }

    .legal-card {
        background: #ffffff;
        border: 1px solid var(--color-brand-100);
        border-radius: 28px;
        box-shadow: var(--shadow-sm);
        padding: 28px;
    }
    @media (min-width: 640px) {
        .legal-card { padding: 44px 48px; }
    }

    .legal-section {
        scroll-margin-top: 120px;
        padding-top: 28px;
        padding-bottom: 28px;
        border-bottom: 1px dashed var(--color-brand-100);
    }
    .legal-section:first-child { padding-top: 0; }
    .legal-section:last-child { border-bottom: 0; padding-bottom: 0; }

    .legal-section__heading {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.25;
        color: var(--color-ink-900);
        letter-spacing: -0.01em;
        margin-bottom: 12px;
    }
    @media (min-width: 640px) {
        .legal-section__heading { font-size: 22px; }
    }

    .legal-section__num {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 999px;
        background: var(--color-brand-50);
        color: var(--color-primary);
        font-size: 13px;
        font-weight: 800;
        margin-top: 1px;
    }

    .legal-prose {
        font-size: 15px;
        line-height: 1.75;
        color: var(--color-ink-700);
    }
    .legal-prose p { margin: 0 0 14px; }
    .legal-prose p:last-child { margin-bottom: 0; }
    .legal-prose strong { color: var(--color-ink-900); font-weight: 700; }

    .legal-prose ul, .legal-prose ol {
        margin: 10px 0 14px;
        padding-left: 0;
        list-style: none;
    }
    .legal-prose ul > li {
        position: relative;
        padding-left: 28px;
        margin-bottom: 8px;
    }
    .legal-prose ul > li::before {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 3px;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: var(--color-brand-50);
        color: var(--color-primary);
        font-size: 10px;
    }
    .legal-prose ol {
        counter-reset: legal-counter;
    }
    .legal-prose ol > li {
        counter-increment: legal-counter;
        position: relative;
        padding-left: 32px;
        margin-bottom: 8px;
    }
    .legal-prose ol > li::before {
        content: counter(legal-counter) '.';
        position: absolute;
        left: 0;
        top: 0;
        font-weight: 800;
        color: var(--color-primary);
    }

    .legal-callout {
        background: linear-gradient(135deg, var(--color-brand-50) 0%, #ffffff 100%);
        border: 1px solid var(--color-brand-100);
        border-left: 4px solid var(--color-primary);
        border-radius: 16px;
        padding: 18px 20px;
        margin: 18px 0;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }
    .legal-callout__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 999px;
        background: var(--color-primary);
        color: #ffffff;
        font-size: 13px;
        flex-shrink: 0;
    }
    .legal-callout__body p { margin: 0; font-size: 14px; color: var(--color-ink-700); }

    .legal-meta-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 28px;
    }
    .legal-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--color-brand-100);
        background: #ffffff;
        color: var(--color-ink-500);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
    }
</style>
