import { createRoot } from 'react-dom/client';
import BookingFormApp from './GymBookingForm.jsx';
import '../../css/gym-booking-form-react.css';

let bookingRoot = null;
let retryTimer = null;
let retryCount = 0;
const MAX_RETRIES = 12;

function clearRetryTimer() {
  if (retryTimer) {
    clearTimeout(retryTimer);
    retryTimer = null;
  }
}

function scheduleRetry() {
  if (retryCount >= MAX_RETRIES) return;
  clearRetryTimer();
  retryTimer = setTimeout(() => {
    retryCount += 1;
    mountBookingForm({ force: true });
  }, 250);
}

function mountBookingForm({ force = false } = {}) {
  const el = document.getElementById('spotmee-gym-booking-react');
  if (!el) return;
  const bootstrapEl = document.getElementById('spotmee-booking-bootstrap');

  // Wait for bootstrap payload to exist before mounting.
  if (!bootstrapEl || !bootstrapEl.textContent) {
    el.setAttribute('data-booking-mounted', 'waiting-bootstrap');
    scheduleRetry();
    return;
  }

  // Skip if already mounted unless force-remount is requested.
  if (!force && el.getAttribute('data-booking-mounted') === 'true') return;

  try {
    el.setAttribute('data-booking-mounted', 'rendering');
    if (!bookingRoot) {
      bookingRoot = createRoot(el);
    }
    bookingRoot.render(<BookingFormApp />);
    // Mark as mounted on next frame after React has had a chance to commit.
    requestAnimationFrame(() => {
      el.setAttribute('data-booking-mounted', 'true');
    });
    retryCount = 0;
    clearRetryTimer();
  } catch (err) {
    console.error('spotmee-booking-mount-failed', err);
    el.setAttribute('data-booking-mounted', 'failed');
    bookingRoot = null;
    el.innerHTML = `
      <div style="border:1px solid #fecaca;background:#fff1f2;color:#9f1239;padding:14px 16px;border-radius:12px;font-size:14px;line-height:1.45;">
        Booking form failed to load. Please refresh the page.
      </div>
    `;
    scheduleRetry();
  }
}

// Initial mount.
mountBookingForm();

// Safety net: in some browser/layout timing cases the modal subtree can be
// available after initial script evaluation.
document.addEventListener('DOMContentLoaded', mountBookingForm, { once: true });
window.addEventListener('load', mountBookingForm, { once: true });

// Explicit remount trigger when Reserve modal opens.
window.addEventListener('spotmee:booking-modal-open', () => {
  mountBookingForm({ force: true });
});
