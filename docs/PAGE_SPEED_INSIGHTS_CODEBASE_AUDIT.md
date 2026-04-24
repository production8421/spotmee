# PageSpeed Insights ↔ Codebase Audit

**Date:** 2026-04-25  
**Last updated:** 2026-04-25 (supplemental screenshots + deeper PSI narrative)  
**Scope:** Read-only mapping of Google PageSpeed Insights **mobile** runs and attached long-page screenshots to this repository. **No application code was changed** for this document (fixes tracked separately).

**Note:** Lighthouse was run against staging (`spotmee.sitestaginglink.com`). Exact scores vary by URL, cache, and network. This file answers: *“Do we have the kinds of issues PSI reported?”*

**Assets referenced (workspace):**  
- PageSpeed (earlier): `assets/...screencapture-pagespeed-web-dev-analysis-https-spotmee-sitestaginglink-com-25iqnjofnf-2026-04-25-04_22_59-....png`  
- PageSpeed (**follow-up / more detail**): `assets/...screencapture-pagespeed-web-dev-analysis-https-spotmee-sitestaginglink-com-25iqnjofnf-2026-04-25-04_31_42-....png`  
- Long mobile page (qualitative UX): `assets/...image-74672dd8-9a56-4c90-9cef-abab78189331.png` (description notes a very long scroll, list+thumbnail rows, and a large FAQ region).

---

## 1. Performance

### 1.1 Render-blocking resources

| PSI theme | Present in codebase? | Evidence |
|-----------|----------------------|----------|
| Render-blocking CSS/JS | **Yes** | `resources/views/layouts/web/master.blade.php` loads synchronously in `<head>`: Google Fonts stylesheet, Font Awesome 6 CSS, AOS CSS, Slick theme + Slick core CSS, then `@vite(['resources/css/app.css'])`. In `<body>` end: jQuery, AOS JS, Slick JS (all blocking parser until executed), then Vite bundles. |

### 1.2 Font loading

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Third-party font CSS blocking render | **Yes** | `fonts.googleapis.com` / `fonts.gstatic.com` preconnect exists, but the main `fonts.googleapis.com/css2?family=Plus+Jakarta+Sans...` link is a classic render-blocking stylesheet. |

### 1.3 Images (size, format, encoding)

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Large / unoptimized hero or marketing images | **Likely** | Many views use `asset('images/...')` PNG/JPG (e.g. `banner-img.png`, home gym slider in `resources/views/web/home/index.blade.php`). No `loading="lazy"` or `srcset` on several marketing images. Next-gen formats (WebP/AVIF) are not used in those static paths in the reviewed templates. |
| Decorative icons with `alt=""` | **Yes** | `resources/views/web/find-a-gym/gym-main-page.blade.php` uses multiple `<img ... alt="">` for small icons (acceptable for decorative if truly decorative; PSI sometimes still counts empty alt in certain audits). |

### 1.4 JavaScript / main thread

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Heavy third-party JS | **Yes** | Global inclusion of **jQuery 3.7.1**, **AOS 2.3.4**, **Slick 1.9.0** on every page using `master.blade.php`. |
| React booking bundle on all pages | **Yes** | `master.blade.php` always runs `@vite('resources/js/gym-booking-form/main.jsx')` even on pages that never open the booking modal (e.g. FAQ, legal). That increases parse/compile cost sitewide. |
| Alpine.js | **Yes** | `resources/js/app.js` starts Alpine on every page load. |
| AOS refresh work on load | **Yes** | Inline script registers multiple `load` handlers, `document.fonts.ready`, per-`img` listeners, and `setTimeout(..., 400)` calling `AOS.refreshHard()` — extra main-thread work after load. |

### 1.5 DOM size (“Avoid an excessive DOM size”)

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Large DOM on rich pages | **Plausible** | Home (`resources/views/web/home/index.blade.php`), gym detail (`gym-main-page.blade.php`), and long FAQ (`resources/views/web/faq/index.blade.php`) nest many nodes. A single Lighthouse number (e.g. ~1,215 elements) is URL-specific but the codebase supports large trees on key landing pages. |

### 1.6 Server response time (TTFB)

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Slow TTFB | **Environment / ops** | Not determinable from frontend source alone. Typical causes: PHP-FPM, DB, cold cache, staging hardware, missing HTTP cache headers. Worth measuring on the exact staging host. |

### 1.7 Scroll / UI “bugs” vs performance

| Topic | Present? | Evidence |
|-------|----------|----------|
| FAQ or sections disappearing when scrolling | **Previously related to AOS config** | A second `AOS.init()` in `resources/js/app.js` (with `once: false`) used to override layout init and could re-hide animated blocks. Current `app.js` has that block commented; layout uses `once: true`. If PSI was run before that fix, scroll + AOS could correlate with “disappearing” content. **CLS was reported low (0.007)** — so strict “layout shift” is not the primary story; main-thread / animation state fits better. |

### 1.8 Detailed lab metrics (from follow-up PageSpeed screenshot, mobile)

These numbers come from the **second** PSI capture narrative (`04_31_42`); treat as a snapshot, not a guarantee on every deploy.

| Metric | Reported value | Interpretation |
|--------|----------------|----------------|
| **FCP** | ~2.5s | First paint delay; ties to blocking CSS/fonts/JS (§1.1–1.2). |
| **LCP** | ~2.8s | Largest content paint; often hero/banner image + font (§1.3). |
| **TBT** | ~250ms | Main-thread blocking from JS parse/exec (§1.4). |
| **CLS** | ~0.007 | **Low** — not the main driver of “content vanishing”; prefer animation/scroll-layer bugs over “layout jump” story. |
| **Core Web Vitals** | Pass | Lab/field summary as shown in report. |

**Opportunities (named in report):** initial server response (~0.65s), render-blocking resources (~0.22s), image sizing, next-gen formats, efficient encoding.

**Diagnostics (named in report):** minimize main-thread work (~**2.2s**), reduce JavaScript execution time (~**0.6s**), **DOM size ~1,215 elements** on the URL that was analyzed.

---

## 2. Accessibility (PSI ~55)

Lighthouse flags **categories**; below maps **likely matches** in this repo.

### 2.1 Buttons without accessible names

| Present? | Evidence |
|----------|----------|
| **Yes** | `resources/views/web/home/index.blade.php`: carousel controls `.gym-prev` / `.gym-next` are `<button>` with only `<i class="fa-solid fa-chevron-*">` inside — **no `aria-label`**. Same file: wishlist `<button>` with only `<i class="fa-regular fa-heart">` — **no accessible name**. |
| **Possible elsewhere** | `resources/views/web/find-a-gym/gym-main-page.blade.php`: some buttons use `aria-label` (e.g. close, stars); others (e.g. tab-style controls) should be spot-checked in a full axe pass. |

### 2.2 Links without discernible names

| Present? | Evidence |
|----------|----------|
| **Yes** | `resources/views/web/home/index.blade.php` line ~208: popular gym card wraps content in `<a href="#" class="group block ...">` — **href is `#`** and the **visible title is inside nested elements**; depending on browser/Lighthouse version, the **link purpose** can be scored weakly (generic “learn more” pattern). |
| **Yes** | `resources/views/layouts/web/footer.blade.php`: `<a href="#" class="footer-bar-link">{{ __('Cookies') }}</a>` — **placeholder `href="#"`**; text exists (“Cookies”) so name is usually OK, but **non-crawlable / dead** target may appear under “best practices” or SEO link quality, not always a11y name. |

### 2.3 Form controls and labels

| Present? | Evidence |
|----------|----------|
| **Mixed** | `resources/views/web/contact/index.blade.php` uses explicit `<label for="...">` matching `id` on inputs — **good**. |
| **Risk** | The **React / Ant Design** gym booking form (`resources/js/gym-booking-form/GymBookingForm.jsx`) often relies on Ant Design `Form.Item` + `label` props; Lighthouse sometimes still reports association issues depending on DOM structure and version. **Not fully verified line-by-line in this audit** — flagged as *likely on gym pages where the modal mounts*. |

### 2.4 Images without `[alt]`

| Present? | Evidence |
|----------|----------|
| **Yes (admin / tooling)** | Multiple admin blades use `alt=""` for previews (e.g. `resources/views/admin/settings/edit.blade.php`, `admin/frontend/home/edit.blade.php`). Public site header/footer logos use meaningful `alt`. |
| **Yes (public gym page)** | `gym-main-page.blade.php`: several icon `<img alt="">`; modal placeholder `#ryj-modal-main-img` uses `alt=""` until filled — can trigger audits if the empty state is measured. |

### 2.5 Color contrast / heading order

| Not exhaustively checked | Would require computed styles + design tokens audit beyond static HTML review. |

---

## 3. Best Practices (PSI 100)

| Topic | In codebase? |
|-------|----------------|
| HTTPS / mixed content | Assumed on staging; not verified from repo. |
| Vulnerable JS libraries | PSI reported clean at scan time; **not re-verified** against current CDN subresource integrity hashes in `master.blade.php` (SRI is present on several tags — good practice). |

---

## 4. SEO (PSI ~92)

| PSI theme | Present? | Evidence |
|-----------|----------|----------|
| Links without descriptive text | **Partially** | Same **`href="#"`** gym cards on home (`<a href="#">` wrapping entire card) hurt **meaningful destinations** and may trigger “generic link” SEO hints. Footer Cookies `href="#"` is a stub. |
| Duplicate / thin routes | **N/A** | Not audited in depth. |

---

## 5. Summary table

| Lighthouse bucket | Issue type | Found in this repo? |
|-------------------|------------|----------------------|
| Performance | Render-blocking CSS/JS (fonts, FA, AOS, Slick, Vite order) | **Yes** |
| Performance | Large legacy PNG/JPEG assets | **Likely** |
| Performance | Heavy global JS (jQuery + Slick + AOS + Alpine + React chunk on every page) | **Yes** |
| Performance | Post-load main-thread work (AOS refresh listeners) | **Yes** |
| Performance | Large DOM on key templates | **Plausible** |
| Performance | Slow TTFB | **Ops / server** (not proven in repo) |
| Accessibility | Icon-only `<button>` without name (home carousel, wishlist) | **Yes** |
| Accessibility | Some `alt=""` / decorative images on public gym page | **Yes** |
| Accessibility | Contact form labels | **No issue** (labels present) |
| Accessibility | Ant Design booking form | **Possible** (needs page-specific Lighthouse on `/gyms/{slug}`) |
| SEO | `href="#"` on important interactive cards | **Yes** (home popular gyms) |

---

## 6. Supplemental long-page screenshot (UX / layout hypotheses)

The long mobile screenshot shows a **very tall page**: hero, many list rows (thumbnail + title + copy), a **large FAQ block**, then footer. The automated description also referenced a **non-SPOTMEE** brand name (“SURGEVINE”) and **numbered FAQ boxes** with pale headers.

**Reconciliation with this repo**

| Screenshot cue | SPOTMEE `resources/views/web/faq/index.blade.php` |
|----------------|--------------------------------------------------|
| Numbered FAQ rows | Current FAQ is **`<details>` accordions** with a **teal gradient** summary — **not** the same visual as “numbered pale header boxes” in the description. |
| Conclusion | Either the PNG is **another site / mock**, an **old build**, or a **different route** than `/faq`. **Before “fixing to match screenshot,” confirm the exact URL** that was captured. |

**If** the observed behavior on staging is still “FAQ hard to reach / disappears while scrolling,” these **code-linked** factors remain relevant (no fix applied here — documentation only):

| Symptom (from screenshot narrative) | Hypothesis | Repo evidence |
|-------------------------------------|------------|---------------|
| Long scroll, heavy middle sections | Large DOM + many images + AOS | About + FAQ + home use many `data-aos` nodes; PSI cited **~1,215** DOM elements. |
| FAQ feels clipped or “hides” when scrolling | Scroll + **overflow** + animation | `.faq-item { overflow: hidden; }` in `faq/index.blade.php` inline styles; combined with scroll-driven animation bugs (historically **double `AOS.init`**) can produce “unreachable” content. **Current `app.js`:** second `AOS.init` is commented — retest staging after deploy. |
| Text tight against thumbnails | Layout density | If reproduced on SPOTMEE home/gym cards, inspect `padding`/`gap` on grid rows (e.g. popular gyms slider in `home/index.blade.php`). |

---

## 7. How to use this document

1. Re-run PageSpeed on the **same URL** after each deploy; compare `04_22_59` vs `04_31_42` style captures for **variance**, not absolutes.  
2. For Accessibility, run **axe DevTools** or Lighthouse on: `/`, `/faq`, `/about`, `/contact`, and a representative `/gyms/{slug}`.  
3. For Performance, profile **Network** (blocking), **Coverage** (unused JS), and consider **code-splitting** the gym booking React entry so it loads only on gym pages.  
4. For **FAQ scroll bugs**, confirm **one** `AOS.init` path and, if issues persist, reproduce on **`/faq` specifically** with DevTools → Layers / `overflow` on ancestors.

---

*End of audit document.*
