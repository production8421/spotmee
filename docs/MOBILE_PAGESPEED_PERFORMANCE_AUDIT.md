# Mobile PageSpeed — Performance audit (lab)

**Document type:** Performance-only (Lighthouse / PageSpeed Insights, **mobile** emulation)  
**Created:** 2026-04-25  
**Scope:** Read-only capture of one detailed PSI run + mapping to this repository where filenames and stack match. **No code changes** are part of this document.

---

## 1. Source material

| Item | Detail |
|------|--------|
| **Tool** | Google PageSpeed Insights (Lighthouse mobile) |
| **Emulated device** | Moto G4 |
| **Network** | Slow 4G (150 ms RTT, 1.6 Mbps down, 750 Kbps up) |
| **CPU** | 4× slowdown |
| **Screenshot asset (workspace)** | `assets/...screencapture-pagespeed-web-dev-analysis-https-spotmee-sitestaginglink-com-25iqnjofnf-2026-04-25-04_33_50-0b11de5d-75cc-4142-82c6-419e505d063e.png` |
| **Report URL (from narrative)** | `http://localhost:3000/` — often a **Vite / Node dev server** port; Laravel’s default `php artisan serve` is usually **:8000**. If your team ran PSI against **3000**, treat asset names below as that build’s output; staging hostname in the filename may be a **different** run—compare both. |

---

## 2. Overall score (mobile)

| Category | Score | Band |
|----------|------:|------|
| **Performance** | **54** | Needs improvement (orange) |

*Other Lighthouse categories (Accessibility, Best Practices, SEO) were not the focus of this capture; they are omitted here.*

---

## 3. Core metrics (lab)

| Metric | Value | Lighthouse color (as reported) |
|--------|------:|----------------------------------|
| **First Contentful Paint (FCP)** | 1.6 s | Moderate |
| **Speed Index** | 4.8 s | Slow |
| **Largest Contentful Paint (LCP)** | 3.5 s | Moderate |
| **Time to Interactive (TTI)** | 6.8 s | Slow |
| **Total Blocking Time (TBT)** | 610 ms | Slow |
| **Cumulative Layout Shift (CLS)** | 0.124 | Moderate |

**Interpretation (short):** Main thread and JS execution are heavy (**TBT 610 ms**, **TTI 6.8 s**). **CLS 0.124** is materially higher than the “~0.007” figure seen on another SPOTMEE staging report—suggests **different page, cache state, or missing dimensions on images** on this URL. Worth re-running on the **exact production/staging URL** you care about.

---

## 4. Opportunities (estimated savings)

| Opportunity | Est. saving | Likely codebase tie-in |
|-------------|------------:|-------------------------|
| Eliminate render-blocking resources | **1.62 s** | `resources/views/layouts/web/master.blade.php`: sync CSS (Google Fonts, Font Awesome, AOS, Slick) + sync JS block (jQuery, AOS, Slick) before Vite. |
| Properly size images | **1.05 s** | Large PNGs under `public/images/` (e.g. hero/banners). Report cited **`banner-img-1.png`** (~285 KiB) and **`faq-banner.png`** (~207 KiB)—verify paths exist on the scanned site; SPOTMEE blades often use `banner-img.png` / inner-banner styles. |
| Serve images in next-gen formats | **0.90 s** | Same assets; no WebP/AVIF pipeline in static `asset('images/...')` usage reviewed earlier. |
| Reduce unused JavaScript | **0.35 s** | Global **Slick + jQuery + AOS + Alpine**; **React booking bundle** loaded on every `master` page (`gym-booking-form/main.jsx`). |
| Efficiently encode images | **0.28 s** | PNG compression / metadata. |
| Minify CSS | **0.15 s** | Third-party CSS (FA, AOS, Slick) is already minified; custom Tailwind/Vite output is built minified in production—dev builds may differ. |

---

## 5. Diagnostics (technical)

### 5.1 Fonts / FOIT

| Finding | Notes |
|---------|--------|
| **Text not visible during webfont load** | Font Awesome + Google Fonts may load without **`font-display: swap`** on all faces (third-party). Causes **FOIT**; contributes to perceived slowness and can interact with LCP. |

### 5.2 Caching (localhost)

| Finding | Notes |
|---------|--------|
| **Cache TTL 0 ms** on many assets | **Expected on `localhost`** (dev server sends no long-cache headers). **Not** representative of production CDN/nginx cache unless the same config is used on staging. |

### 5.3 Network payload

| Finding | Value |
|---------|------:|
| **Total transfer size** | **~3,745 KiB (~3.7 MB)** |

**Large files named in the report:**

| Asset (as named in PSI) | Approx. size |
|-------------------------|-------------:|
| `slick.min.js` | ~422 KiB |
| `main.85304675.js` (hashed bundle) | ~340 KiB |
| `banner-img-1.png` | ~285 KiB |
| `faq-banner.png` | ~207 KiB |

**Spotmee repo correlation:**

- **Slick** is loaded from CDN in `master.blade.php` (~matches size order of magnitude for minified slick + source map if any).
- **`main.*.js`** matches a **Vite/Rollup** hashed entry name (e.g. production or analyzed build of `app.js` + dependencies); exact hash changes per build.
- **Images:** grep the repo for `banner-img-1` / `faq-banner` — if absent, the audited page may be a **branch or alternate public folder** not identical to current `resources/views`.

### 5.4 DOM

| Finding | Value |
|---------|------:|
| **Total DOM elements** | **1,155** |

Long marketing pages + FAQ + many `data-aos` nodes fit this order of magnitude (see `docs/PAGE_SPEED_INSIGHTS_CODEBASE_AUDIT.md` §1.5).

### 5.5 Main-thread work

| Bucket | Time |
|--------|------:|
| **Total main-thread work** | **3.8 s** |
| Script evaluation | ~1,114 ms |
| Style & layout | ~659 ms |
| Rendering | ~258 ms |

**JS execution:** ~**1.6 s** total; **`main.85304675.js` ~865 ms** attributed in the report — consistent with a **large bundled app chunk** (e.g. jQuery plugins + app + partial React tree if not split).

### 5.6 Layout shift drivers

| Finding | Impact |
|---------|--------|
| **Images without explicit `width` and `height`** | Named: `logo.png`, `banner-img-1.png`, SVG icons, etc. Missing intrinsic dimensions → **CLS**; aligns with **CLS 0.124** in this run. |
| **Non-composited animations** | **1** animation flagged — can cause **jank** with scroll; may relate to AOS transforms or carousel transitions. |

### 5.7 Long tasks

| Finding | Detail |
|---------|--------|
| **Long main-thread tasks** | **7** long tasks during load — pairs with high TBT and Speed Index. |

---

## 6. Passed audits (highlights from report)

- Offscreen images are deferred (lazy or equivalent behavior).  
- JavaScript is minified (for the bundles Lighthouse recognized).  
- **Initial server response (TTFB)** was short in this run (dev/local often is).  
- **`<meta name="viewport">`** present.  
- Avoids `document.write()`.

---

## 7. Action backlog (for later — not implemented in this doc)

Prioritized **performance-only** follow-ups aligned with this audit:

1. **Reduce render-blocking:** defer non-critical CSS/JS; load Slick/AOS only on pages that need them; self-host subset of Font Awesome if possible.  
2. **Images:** add **width/height** on above-the-fold images; compress PNGs; add **WebP/AVIF** variants + `<picture>` or `img` `srcset`.  
3. **Code splitting:** load `gym-booking-form/main.jsx` only on gym detail (or when modal opens).  
4. **Fonts:** ensure `font-display: swap` for self-hosted CSS; for Google Fonts URL, use `&display=swap`.  
5. **Re-measure** on **production/staging HTTPS** with same throttling—**localhost cache TTL** will always look “bad” in PSI.

---

## 8. Cross-reference

See also: **`docs/PAGE_SPEED_INSIGHTS_CODEBASE_AUDIT.md`** — broader mapping (accessibility, SEO, AOS/FAQ scroll context) for SPOTMEE staging.

---

*End of mobile performance audit document.*
