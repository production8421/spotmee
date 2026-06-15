@push('styles')
    <style>
        .blog-masthead {
            background: linear-gradient(180deg, var(--color-brand-50) 0%, #fff 100%);
            border-bottom: 1px solid var(--color-brand-100);
        }
        .blog-masthead__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 9999px;
            background: #fff;
            border: 1px solid var(--color-brand-100);
            padding: 0.35rem 0.85rem;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--color-primary);
        }
        .blog-subnav {
            position: sticky;
            top: 0;
            z-index: 40;
            border-bottom: 1px solid var(--color-brand-100);
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
        }
        .site-header--scrolled ~ main .blog-subnav { top: 0; }
        .blog-subnav__list {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.35rem;
            overflow-x: auto;
            padding: 0.75rem 0;
            margin: 0;
            list-style: none;
            scrollbar-width: none;
        }
        .blog-subnav__list::-webkit-scrollbar { display: none; }
        .blog-subnav__link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            white-space: nowrap;
            border-radius: 9999px;
            border: 1px solid transparent;
            padding: 0.55rem 1rem;
            font-size: 13px;
            font-weight: 600;
            color: var(--color-ink-600);
            transition: all 0.2s ease;
        }
        .blog-subnav__link:hover {
            color: var(--color-primary);
            background: var(--color-brand-50);
        }
        .blog-subnav__link.is-active {
            color: #fff;
            background: var(--color-primary);
            border-color: var(--color-primary);
            box-shadow: var(--shadow-sm);
        }
        .blog-subnav--host .blog-subnav__link.is-active {
            background: var(--color-ink-900);
            border-color: var(--color-ink-900);
        }
        .blog-featured {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid var(--color-brand-100);
            background: #fff;
            box-shadow: var(--shadow-md);
        }
        @media (min-width: 768px) {
            .blog-featured { grid-template-columns: 1.15fr 1fr; min-height: 360px; }
        }
        .blog-featured__media { position: relative; min-height: 240px; background: var(--color-brand-50); }
        .blog-featured__media img { width: 100%; height: 100%; object-fit: cover; }
        .blog-featured__body { display: flex; flex-direction: column; justify-content: center; padding: 1.75rem; }
        @media (min-width: 768px) { .blog-featured__body { padding: 2.25rem 2rem 2.25rem 0.5rem; } }
        .blog-card-list { display: flex; flex-direction: column; gap: 1.25rem; }
        .blog-card-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            overflow: hidden;
            border-radius: 22px;
            border: 1px solid var(--color-brand-100);
            background: #fff;
            box-shadow: var(--shadow-sm);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .blog-card-row:hover {
            transform: translateY(-2px);
            border-color: var(--color-brand-200);
            box-shadow: var(--shadow-lg);
        }
        @media (min-width: 640px) {
            .blog-card-row { grid-template-columns: 220px 1fr; }
        }
        .blog-card-row__media { min-height: 180px; background: var(--color-brand-50); }
        @media (min-width: 640px) { .blog-card-row__media { min-height: 100%; } }
        .blog-card-row__media img { width: 100%; height: 100%; object-fit: cover; }
        .blog-card-row__body { padding: 1.25rem 1.25rem 1.5rem; }
        @media (min-width: 640px) { .blog-card-row__body { padding: 1.5rem 1.5rem 1.5rem 0.25rem; } }
        .blog-sidebar-card {
            border-radius: 22px;
            border: 1px solid var(--color-brand-100);
            background: #fff;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
        }
        .blog-sidebar-card + .blog-sidebar-card { margin-top: 1rem; }
        .blog-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 9999px;
            background: var(--color-brand-50);
            padding: 0.25rem 0.65rem;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--color-primary);
        }
        .blog-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            font-size: 13px;
            color: var(--color-ink-500);
        }
        .blog-meta__dot { width: 4px; height: 4px; border-radius: 9999px; background: var(--color-ink-300); }
    </style>
@endpush
