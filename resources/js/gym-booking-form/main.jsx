import { createRoot } from 'react-dom/client';
import BookingFormApp from './GymBookingForm.jsx';
import '../../css/gym-booking-form-react.css';

let bookingRoot = null;

function mountBookingForm() {
  const el = document.getElementById('spotmee-gym-booking-react');
  if (!el) return;

  // Prevent duplicate roots when this function is called multiple times.
  if (bookingRoot) return;

  try {
    el.setAttribute('data-booking-mounted', 'rendering');
    bookingRoot = createRoot(el);
    bookingRoot.render(<BookingFormApp />);
    el.setAttribute('data-booking-mounted', 'true');
  } catch (err) {
    console.error('spotmee-booking-mount-failed', err);
    el.setAttribute('data-booking-mounted', 'failed');
    el.innerHTML = `
      <div style="border:1px solid #fecaca;background:#fff1f2;color:#9f1239;padding:14px 16px;border-radius:12px;font-size:14px;line-height:1.45;">
        Booking form failed to load. Please refresh the page.
      </div>
    `;
  }
}

// Initial mount.
mountBookingForm();

// Safety net: in some browser/layout timing cases the modal subtree can be
// available after initial script evaluation.
document.addEventListener('DOMContentLoaded', mountBookingForm, { once: true });

// Explicit remount trigger when Reserve modal opens.
window.addEventListener('spotmee:booking-modal-open', mountBookingForm);
