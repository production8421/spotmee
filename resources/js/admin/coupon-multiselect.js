import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';
import '../../css/admin/coupon-multiselect.css';

function initCouponCodeGenerator() {
    const btn = document.getElementById('coupon-generate-code');
    const input = document.getElementById('coupon-code');
    if (!btn || !input) {
        return;
    }

    btn.addEventListener('click', async () => {
        const url = btn.getAttribute('data-url');
        if (!url) {
            return;
        }
        btn.disabled = true;
        try {
            const res = await fetch(url, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) {
                throw new Error('Request failed');
            }
            const data = await res.json();
            if (data && typeof data.code === 'string') {
                input.value = data.code;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        } catch {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            let fallback = '';
            for (let i = 0; i < 10; i++) {
                fallback += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            input.value = fallback;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        } finally {
            btn.disabled = false;
        }
    });
}

function initCouponMultiselects() {
    const base = (el) => ({
        plugins: ['remove_button', 'dropdown_input'],
        persist: false,
        create: false,
        maxItems: null,
        hideSelected: true,
        closeAfterSelect: false,
        placeholder: el.getAttribute('data-placeholder') || '',
    });

    const hostEl = document.getElementById('coupon-host-ids');
    if (hostEl && !hostEl.dataset.tsInited) {
        new TomSelect(hostEl, base(hostEl));
        hostEl.dataset.tsInited = '1';
    }

    const gymEl = document.getElementById('coupon-gym-ids');
    if (gymEl && !gymEl.dataset.tsInited) {
        new TomSelect(gymEl, base(gymEl));
        gymEl.dataset.tsInited = '1';
    }
}

function initCouponFormPage() {
    initCouponMultiselects();
    initCouponCodeGenerator();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCouponFormPage);
} else {
    initCouponFormPage();
}
