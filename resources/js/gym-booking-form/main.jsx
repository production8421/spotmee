import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import BookingFormApp from './GymBookingForm.jsx';
import '../../css/gym-booking-form-react.css';

const el = document.getElementById('spotmee-gym-booking-react');
if (el) {
  createRoot(el).render(
    <StrictMode>
      <BookingFormApp />
    </StrictMode>
  );
}
