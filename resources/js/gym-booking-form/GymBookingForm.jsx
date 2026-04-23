import React, { useState, useEffect, useRef } from "react";

const __ = (s) => s;
import {
  ConfigProvider,
  Form,
  Input,
  InputNumber,
  DatePicker,
  Select,
  Button,
  App,
  Card,
  Row,
  Col,
  Alert,
  Spin,
  Result,
  Steps,
  Divider,
  Checkbox,
  Radio,
} from "antd";
import {
  UserOutlined,
  MailOutlined,
  PhoneOutlined,
  CalendarOutlined,
  ClockCircleOutlined,
  CheckCircleOutlined,
  CreditCardOutlined,
  TeamOutlined,
} from "@ant-design/icons";
import enUS from "antd/locale/en_US";
import dayjs from "dayjs";

/** Map Laravel `bookingBootstrap` JSON to the shape this form expects. */
function buildLocalizedFromBootstrap(b) {
  if (!b || typeof b !== "object") return null;
  return {
    blockedUrl: b.blockedUrl,
    storeUrl: b.storeUrl,
    quote_url: typeof b.quoteUrl === "string" ? b.quoteUrl.trim() : "",
    csrf: b.csrf,
    gym_title: b.gymTitle || "Gym",
    availability: b.availability || {},
    personal_trainer_schedule: b.personalTrainingAvailability || {},
    personal_trainer_available: b.personalTrainingAvailable ? "yes" : "no",
    pt_addon_enabled: !!b.ptAddonEnabled,
    pricing: {
      rate_40min: Number(b.rate40) || 0,
      rate_1hr: Number(b.rate1hr) || 0,
      hourly_rate: Number(b.rate1hr) || 0,
    },
    personal_trainer_price: Number(b.ptSlotPrice) || 0,
    personal_trainer_commission: 0,
    terms_url: b.termsUrl || "",
    privacy_url: b.privacyUrl || "",
    stripe_publishable_key: b.stripePublishableKey || "",
    payment_intent_url: b.paymentIntentUrl || "",
    confirm_payment_url: b.confirmPaymentUrl || "",
    listing_person_limit:
      b.listingPersonLimit != null && b.listingPersonLimit !== ""
        ? Number(b.listingPersonLimit)
        : null,
    pt_icon_url: typeof b.ptIconUrl === "string" ? b.ptIconUrl.trim() : "",
    is_subscriber: !!b.isSubscriber,
    user_email: typeof b.userEmail === "string" ? b.userEmail.trim() : "",
  };
}

// Inner component that uses App context
const BookingFormContent = ({ localizedData }) => {
  const [form] = Form.useForm();
  /** Single source of truth with DatePicker (avoids stale React state vs Form field). */
  const bookingDate = Form.useWatch("bookingDate", form);
  /** Re-render slot options when party size / selection changes (parent must subscribe; nested Form fields alone do not). */
  const watchedNumberOfPersons = Form.useWatch("numberOfPersons", form);
  const watchedTimeSlots = Form.useWatch("timeSlot", form);
  const watchedCouponCode = Form.useWatch("couponCode", form);
  const { message } = App.useApp();
  const msgSlotsMustBeConsecutive = __(
    "Selected slots must be consecutive with no gaps.",
    "rent-your-jim"
  );
  const [loading, setLoading] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [confirmationCode, setConfirmationCode] = useState("");
  const [cancelBookingUrl, setCancelBookingUrl] = useState("");
  const [availability, setAvailability] = useState({});
  const [personalTrainerSchedule, setPersonalTrainerSchedule] = useState(
    localizedData?.personal_trainer_schedule ?? {}
  );
  const [blockedTimes, setBlockedTimes] = useState([]);
  const [calculatedPrice, setCalculatedPrice] = useState(null);
  const [currentStep, setCurrentStep] = useState(0);
  const [paymentIntent, setPaymentIntent] = useState(null);
  const [stripe, setStripe] = useState(null);
  const [elements, setElements] = useState(null);
  const [paymentElement, setPaymentElement] = useState(null);
  const [applyingCoupon, setApplyingCoupon] = useState(false);
  const stripeRef = useRef(null);
  const paymentElementRef = useRef(null);
  const quoteRequestIdRef = useRef(0);

  const blockedUrl = localizedData?.blockedUrl;
  const storeUrl = localizedData?.storeUrl;
  const csrfToken = localizedData?.csrf || "";
  const gymTitle = localizedData?.gym_title || "Gym";
  const pricing = localizedData?.pricing || {
    hourly_rate: 30,
    rate_1hr: 30,
    rate_40min: 22,
    tier: "silver",
  };
  const stripePublishableKey = localizedData?.stripe_publishable_key || "";
  const paymentIntentUrl = localizedData?.payment_intent_url || "";
  const confirmPaymentUrl = localizedData?.confirm_payment_url || "";
  const quoteUrl = localizedData?.quote_url || "";
  const listingPersonLimit =
    localizedData?.listing_person_limit != null &&
    Number.isFinite(Number(localizedData.listing_person_limit)) &&
    Number(localizedData.listing_person_limit) > 0
      ? Math.min(100, Math.max(1, parseInt(String(localizedData.listing_person_limit), 10)))
      : null;
  const termsUrl = localizedData?.terms_url || "#";
  const privacyUrl = localizedData?.privacy_url || "#";
  const personalTrainerAvailable =
    localizedData?.personal_trainer_available === "yes" && !!localizedData?.pt_addon_enabled;
  const personalTrainerIconUrl =
    typeof localizedData?.pt_icon_url === "string" && localizedData.pt_icon_url.trim() !== ""
      ? localizedData.pt_icon_url.trim()
      : null;
  const isSubscriber = !!localizedData?.is_subscriber;
  const subscriberAccountEmail =
    typeof localizedData?.user_email === "string" ? localizedData.user_email.trim() : "";
  // Object mapping slot values to trainer selection: { "09:00|10:00": true, "10:00|11:00": false }
  const [trainerPerSlot, setTrainerPerSlot] = useState({});
  // Personal trainer add-on type: 'paid' or 'free_trial' (trial is 1 slot, $0, enforced once-per-gym on Continue to Payment)
  const [ptAddOnType, setPtAddOnType] = useState("paid");
  const [ptFreeTrialSlot, setPtFreeTrialSlot] = useState(null);
  const [selectedDurationType, setSelectedDurationType] = useState(null);
  const [step0Error, setStep0Error] = useState(null);
  const [step1Error, setStep1Error] = useState(null);

  // Function to check available durations for a date
  const getAvailableDurations = (date) => {
    if (!date) return [];
    const operatingHours = getOperatingHours(date);
    if (!operatingHours || !operatingHours.slotDuration) return [];
    
    const duration = operatingHours.slotDuration;
    if (Array.isArray(duration)) {
        return duration.map(d => parseInt(d, 10)); // return [40, 60] etc
    }
    return [parseInt(duration, 10)];
  };

  // Initialize Stripe
  useEffect(() => {
    if (stripePublishableKey && typeof window !== 'undefined' && window.Stripe) {
      const stripeInstance = window.Stripe(stripePublishableKey);
      setStripe(stripeInstance);
      stripeRef.current = stripeInstance;
    }
  }, [stripePublishableKey]);

  // Initialize Stripe Elements when payment intent is ready
  useEffect(() => {
    if (stripe && paymentIntent && currentStep === 1) {
      const elementsInstance = stripe.elements({
        clientSecret: paymentIntent.clientSecret,
        appearance: {
          theme: 'stripe',
          variables: {
            colorPrimary: '#006d77',
            colorBackground: '#ffffff',
            colorText: '#0a1f23',
            colorDanger: '#e11d48',
            fontFamily: '"Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            spacingUnit: '4px',
            borderRadius: '10px',
          },
        },
      });

      setElements(elementsInstance);

      // Create Payment Element (modern Stripe UI)
      const payment = elementsInstance.create('payment', {
        layout: 'tabs',
      });

      // Handle payment element events
      payment.on('ready', () => {
        console.log('Stripe Payment Element ready');
      });

      payment.on('change', (event) => {
        const displayError = document.getElementById('stripe-payment-errors');
        if (event.error) {
          displayError.textContent = event.error.message;
        } else {
          displayError.textContent = '';
        }
      });

      setPaymentElement(payment);
      paymentElementRef.current = payment;
    }
  }, [stripe, paymentIntent, currentStep]);

  // Availability from Laravel bootstrap; blocked times from public JSON route
  useEffect(() => {
    if (
      localizedData?.personal_trainer_schedule &&
      typeof localizedData.personal_trainer_schedule === "object"
    ) {
      setPersonalTrainerSchedule(localizedData.personal_trainer_schedule);
    }
    if (localizedData?.availability && Object.keys(localizedData.availability).length > 0) {
      setAvailability(localizedData.availability);
    }

    if (blockedUrl) {
      fetch(blockedUrl, { headers: { Accept: "application/json" } })
        .then((r) => r.json())
        .then((data) => {
          setBlockedTimes(Array.isArray(data) ? data : []);
        })
        .catch(() => {
          setBlockedTimes([]);
        });
    }
  }, [blockedUrl, localizedData]);

  // Check if a day is available based on gym schedule
  const isDayAvailable = (date) => {
    if (!availability || Object.keys(availability).length === 0) {
      return false;
    }

    const dayNames = [
      "sunday",
      "monday",
      "tuesday",
      "wednesday",
      "thursday",
      "friday",
      "saturday",
    ];
    const dayName = dayNames[date.day()];

    const daySchedule = availability[dayName];

    // If no schedule for this day, treat as closed
    if (!daySchedule) {
      return false;
    }

    const isClosed =
      daySchedule.isClosed === true ||
      daySchedule.isClosed === "true" ||
      daySchedule.is_closed === true ||
      daySchedule.is_closed === "true";
    if (isClosed) {
      return false;
    }

    // Also check if start/end times are set
    if (!daySchedule.startTime || !daySchedule.endTime) {
      return false;
    }

    return true;
  };

  // Disable past dates and unavailable days
  const disabledDate = (current) => {
    // Disable past dates
    if (current && current < dayjs().startOf("day")) {
      return true;
    }
    // Disable days that are closed
    if (current && !isDayAvailable(current)) {
      return true;
    }
    return false;
  };

  // Get operating hours for selected date
  const getOperatingHours = (date) => {
    if (!date) return null;
    const dayNames = [
      "sunday",
      "monday",
      "tuesday",
      "wednesday",
      "thursday",
      "friday",
      "saturday",
    ];
    const dayName = dayNames[date.day()];
    return availability[dayName];
  };

  // Get slot duration for selected date (in minutes)
  const getSlotDuration = () => {
    // If user has explicitly selected a duration type, use it
    if (selectedDurationType) {
        return parseInt(selectedDurationType, 10);
    }

    const operatingHours = getOperatingHours(bookingDate);
    if (!operatingHours) return 60; // Default to 1 hour
    
    let duration = operatingHours.slotDuration;
    
    // Handle array of durations (from checkboxes)
    if (Array.isArray(duration)) {
        if (duration.includes('40') || duration.includes(40)) return 40;
        return 60;
    }

    // slotDuration is stored as string "40" or "60"
    const parsedDuration = parseInt(duration, 10);
    return parsedDuration === 40 ? 40 : 60; // Default to 60 if invalid
  };

  /** Gym offers 60-minute slots — required before PT add-on (same rule as `RyjGymSchedule::gymAvailabilityAllowsOneHourPt`). */
  const gymAllowsOneHourPt = () => {
    if (!bookingDate) return false;
    const row = getOperatingHours(bookingDate);
    if (!row) return false;
    const closed =
      row.isClosed === true ||
      row.isClosed === "true" ||
      row.isClosed === 1 ||
      row.is_closed === true ||
      row.is_closed === "true";
    if (closed) return false;
    const durs = row.slotDuration;
    const arr = Array.isArray(durs) ? durs.map(String) : durs != null && durs !== "" ? [String(durs)] : [];
    return arr.includes("60");
  };

  /**
   * Max persons per overlapping booking window (matches Laravel `GymListing::effectivePersonLimit`).
   * When `listingPersonLimit` is set in bootstrap (admin table), it overrides the day's schedule `personLimit`.
   */
  const getPersonLimitForDate = (date) => {
    if (!date) return 1;
    const operatingHours = getOperatingHours(date);
    let fromSchedule = 1;
    if (operatingHours) {
      const n = parseInt(operatingHours.personLimit, 10);
      fromSchedule = n > 0 ? n : 1;
    }
    if (listingPersonLimit != null) {
      return listingPersonLimit;
    }
    return fromSchedule;
  };

  const getPersonLimit = () => getPersonLimitForDate(bookingDate);

  // Get disabled hours for time picker based on gym availability
  const getDisabledHours = (isEndTime = false) => {
    const operatingHours = getOperatingHours(bookingDate);
    if (!operatingHours || !operatingHours.startTime || !operatingHours.endTime) {
      return []; // No restrictions if no schedule
    }

    const startHour = parseInt(operatingHours.startTime.split(":")[0], 10);
    const endHour = parseInt(operatingHours.endTime.split(":")[0], 10);

    const disabledHours = [];
    
    // Check if this is an overnight schedule (end time is next day)
    const isOvernight = endHour < startHour || (endHour === startHour && 
      parseInt(operatingHours.endTime.split(":")[1], 10) < parseInt(operatingHours.startTime.split(":")[1], 10));

    if (isOvernight) {
      // Overnight schedule (e.g., 12:01 PM - 12:30 AM)
      // For same-day booking, only allow hours from startHour to 23
      for (let i = 0; i < startHour; i++) {
        disabledHours.push(i);
      }
      // Don't disable hours after midnight for now - users should book within same day
    } else {
      // Regular schedule (e.g., 9:00 AM - 6:00 PM)
      // Disable hours before opening
      for (let i = 0; i < startHour; i++) {
        disabledHours.push(i);
      }
      
      // Disable hours after closing
      // For end time: allow selecting the closing hour itself
      const lastAllowedHour = isEndTime ? endHour : endHour;
      for (let i = lastAllowedHour + 1; i < 24; i++) {
        disabledHours.push(i);
      }
    }

    return disabledHours;
  };

  // Get disabled minutes for time picker
  const getDisabledMinutes = (selectedHour, isEndTime = false) => {
    const operatingHours = getOperatingHours(bookingDate);
    if (!operatingHours || selectedHour === null || selectedHour === undefined) {
      return [];
    }

    if (!operatingHours.startTime || !operatingHours.endTime) {
      return [];
    }

    const startHour = parseInt(operatingHours.startTime.split(":")[0], 10);
    const startMinute = parseInt(operatingHours.startTime.split(":")[1], 10);
    const endHour = parseInt(operatingHours.endTime.split(":")[0], 10);
    const endMinute = parseInt(operatingHours.endTime.split(":")[1], 10);

    const disabledMinutes = [];

    // If selected hour is the opening hour, disable minutes before opening
    if (selectedHour === startHour) {
      for (let i = 0; i < startMinute; i++) {
        disabledMinutes.push(i);
      }
    }

    // If selected hour is the closing hour (and not overnight), disable minutes after closing
    const isOvernight = endHour < startHour;
    if (!isOvernight && selectedHour === endHour) {
      for (let i = endMinute + 1; i < 60; i++) {
        disabledMinutes.push(i);
      }
    }

    return disabledMinutes;
  };

  // `personal_trainer_price` is already the final guest-facing PT slot price from backend.
  // Do not re-apply commission in frontend (prevents inflated totals, e.g. 120.01).
  const personalTrainerPrice = Math.max(0, Number(localizedData?.personal_trainer_price) || 0);
  const personalTrainerPriceText = personalTrainerPrice.toFixed(2);

  // Count how many slots have trainer selected
  const getTrainerSlotCount = (trainerSelections) => {
    return Object.values(trainerSelections || {}).filter(Boolean).length;
  };

  // Calculate total price based on slots and number of persons
  // Each slot (40 min or 60 min) is charged at its respective rate
  const calculateTotalPrice = (startTime, endTime, numberOfPersons = 1, trainerSelections = {}, applyPtFreeTrial = false) => {
    if (!startTime || !endTime) {
      setCalculatedPrice(null);
      return;
    }

    const start = dayjs.isDayjs(startTime) ? startTime.hour() * 60 + startTime.minute() : dayjs(startTime, "HH:mm").hour() * 60 + dayjs(startTime, "HH:mm").minute();
    const end = dayjs.isDayjs(endTime) ? endTime.hour() * 60 + endTime.minute() : dayjs(endTime, "HH:mm").hour() * 60 + dayjs(endTime, "HH:mm").minute();
    
    if (end <= start) {
      setCalculatedPrice(null);
      return;
    }

    const persons = numberOfPersons || 1;
    const durationMinutes = end - start;
    const slotDuration = getSlotDuration(); // 40 or 60 minutes
    
    // Calculate number of slots (must be exact multiple due to validation)
    const numberOfSlots = Math.round(durationMinutes / slotDuration);
    
    // Select price based on slot duration (40 min or 1 hour)
    const pricePerSlot = slotDuration === 40 
      ? (pricing.rate_40min || pricing.hourly_rate * 0.75) 
      : (pricing.rate_1hr || pricing.hourly_rate);
    
    const pricePerPerson = numberOfSlots * pricePerSlot;
    const basePrice = pricePerPerson * persons;
    
    // Add personal trainer fee based on number of selected trainer slots
    const trainerSlotCount = getTrainerSlotCount(trainerSelections);
    const trainerFee = applyPtFreeTrial && trainerSlotCount > 0 ? 0 : trainerSlotCount * personalTrainerPrice;
    const totalPrice = basePrice + trainerFee;

    setCalculatedPrice({
      slots: numberOfSlots,
      slotDuration: slotDuration,
      durationMinutes: durationMinutes,
      persons: persons,
      pricePerSlot: pricePerSlot.toFixed(2),
      pricePerPerson: pricePerPerson.toFixed(2),
      gymSubtotalBeforeCoupon: basePrice.toFixed(2),
      basePrice: basePrice.toFixed(2),
      trainerFee: trainerFee.toFixed(2),
      trainerSlotCount: trainerSlotCount,
      includesTrainer: trainerSlotCount > 0,
      ptFreeTrial: applyPtFreeTrial && trainerSlotCount > 0,
      total: totalPrice.toFixed(2),
      currentRate: pricePerSlot.toFixed(2),
      rateType: slotDuration === 40 ? '40 min' : '1 hour',
      startTime: dayjs.isDayjs(startTime) ? startTime.format("HH:mm") : startTime,
      endTime: dayjs.isDayjs(endTime) ? endTime.format("HH:mm") : endTime,
    });
  };

  // Recalculate price when trainer selections or watched slots / party size change
  useEffect(() => {
    const timeSlots = Array.isArray(watchedTimeSlots) ? watchedTimeSlots : [];
    if (timeSlots.length === 0) return;
    if (!slotsAreContiguousRaw(timeSlots)) {
      setCalculatedPrice(null);
      return;
    }

    const sortedSlots = [...timeSlots].sort((a, b) => {
      const [aStart] = a.split("|");
      const [bStart] = b.split("|");
      return aStart.localeCompare(bStart);
    });
    const [firstStart] = sortedSlots[0].split("|");
    const [, lastEnd] = sortedSlots[sortedSlots.length - 1].split("|");

    const trialNorm = ptFreeTrialSlot ? normalizeSlotValue(ptFreeTrialSlot) : "";
    const trialStillIn =
      trialNorm && timeSlots.some((s) => normalizeSlotValue(String(s)) === trialNorm);
    const applyPtFreeTrial = ptAddOnType === "free_trial" && !!ptFreeTrialSlot && trialStillIn;
    const headcount = Math.max(1, parseInt(String(watchedNumberOfPersons ?? 1), 10) || 1);
    calculateTotalPrice(firstStart, lastEnd, headcount, trainerPerSlot, applyPtFreeTrial);
  }, [trainerPerSlot, ptAddOnType, ptFreeTrialSlot, watchedTimeSlots, watchedNumberOfPersons]);

  // Handle date change
  const handleDateChange = (date) => {
    setCalculatedPrice(null);
    setTrainerPerSlot({});
    setPtAddOnType("paid");
    setPtFreeTrialSlot(null);
    
    // Set default duration type for new date
    if (date) {
        const availableDurations = getAvailableDurations(date);
        // Default to 40 if available, else 60, else whatever is there
        if (availableDurations.includes(40)) {
            setSelectedDurationType(40);
        } else if (availableDurations.includes(60)) {
            setSelectedDurationType(60);
        } else if (availableDurations.length > 0) {
            setSelectedDurationType(availableDurations[0]);
        } else {
            setSelectedDurationType(null);
        }
    } else {
        setSelectedDurationType(null);
    }

    // Clear times when date changes; clamp persons to new day's / listing cap
    const limit = date ? getPersonLimitForDate(date) : 1;
    const currentPersons = form.getFieldValue("numberOfPersons") || 1;
    form.setFieldsValue({
      timeSlot: null,
      numberOfPersons: Math.min(Math.max(1, currentPersons), limit),
    });
  };

  // Get availability display text for selected date
  const getAvailabilityText = () => {
    if (!bookingDate) return null;
    const operatingHours = getOperatingHours(bookingDate);
    if (!operatingHours) return null;

    const formatTime = (timeStr) => {
      const [hours, minutes] = timeStr.split(":");
      const hour = parseInt(hours, 10);
      const ampm = hour >= 12 ? "PM" : "AM";
      const displayHour = hour % 12 || 12;
      return `${displayHour}:${minutes} ${ampm}`;
    };

    const slotDuration = getSlotDuration();
    const slotText = slotDuration === 40 ? "40 min" : "1 hour";

    return `Available: ${formatTime(operatingHours.startTime)} - ${formatTime(operatingHours.endTime)} • ${slotText} slots`;
  };

  // Normalize time string to HH:mm for comparison (handles "4:37:00" or "04:37")
  const normalizeTimeStr = (t) => {
    if (!t) return "00:00";
    const parts = String(t).trim().split(":");
    const h = parseInt(parts[0], 10) || 0;
    const m = parseInt(parts[1], 10) || 0;
    return `${String(h).padStart(2, "0")}:${String(m).padStart(2, "0")}`;
  };

  // Check if two time ranges overlap (strings HH:mm)
  const timeRangesOverlap = (s1, e1, s2, e2) => {
    const a = normalizeTimeStr(s1);
    const b = normalizeTimeStr(e1);
    const c = normalizeTimeStr(s2);
    const d = normalizeTimeStr(e2);
    return a < d && b > c;
  };

  /** First start → last end (HH:mm), sorted by start — matches Laravel `boundsFromSlots`. */
  const boundsFromRawSlots = (slotValues) => {
    if (!slotValues || slotValues.length === 0) return ["00:00", "00:00"];
    const sorted = [...slotValues].sort((a, b) =>
      normalizeTimeStr(String(a).split("|")[0]).localeCompare(normalizeTimeStr(String(b).split("|")[0]))
    );
    const first = String(sorted[0]).split("|");
    const last = String(sorted[sorted.length - 1]).split("|");
    return [normalizeTimeStr(first[0].trim()), normalizeTimeStr(last[1].trim())];
  };

  /** Contiguous HH:mm|HH:mm chain (same rule as Laravel booking). */
  const slotsAreContiguousRaw = (slotValues) => {
    if (!slotValues || slotValues.length <= 1) return true;
    const sorted = [...slotValues].sort((a, b) =>
      normalizeTimeStr(String(a).split("|")[0]).localeCompare(normalizeTimeStr(String(b).split("|")[0]))
    );
    let prevEnd = null;
    for (const s of sorted) {
      const p = String(s).split("|");
      if (p.length < 2) return false;
      const st = normalizeTimeStr(p[0].trim());
      const en = normalizeTimeStr(p[1].trim());
      if (prevEnd !== null && st !== prevEnd) return false;
      prevEnd = en;
    }
    return true;
  };

  // Sum persons already booked overlapping [start, end] on selected date (same idea as Laravel `sumPersonsOverlapping`)
  const getAlreadyBookedForSlot = (slotStartStr, slotEndStr) => {
    if (!bookingDate || !blockedTimes || !Array.isArray(blockedTimes)) return 0;
    const dateStr = bookingDate.format("YYYY-MM-DD");
    let sum = 0;
    blockedTimes.forEach((b) => {
      if (b.date !== dateStr) return;
      if (!timeRangesOverlap(slotStartStr, slotEndStr, b.start, b.end)) return;
      sum += b.number_of_persons != null ? parseInt(b.number_of_persons, 10) : 1;
    });
    return sum;
  };

  // Normalize slot value "H:mm|H:mm" or "HH:mm|HH:mm" to "HH:mm|HH:mm" for comparison
  const normalizeSlotValue = (slotStr) => {
    if (!slotStr || typeof slotStr !== "string") return "";
    return slotStr
      .split("|")
      .map((p) => {
        const [h, m] = String(p).trim().split(":");
        const hour = parseInt(h, 10);
        const min = parseInt(m, 10) || 0;
        return `${String(hour).padStart(2, "0")}:${String(min).padStart(2, "0")}`;
      })
      .join("|");
  };

  const boundsMinutesFromNormalizedSlot = (normSlot) => {
    const parts = String(normSlot || "").split("|");
    if (parts.length < 2) return null;
    const ns = normalizeTimeStr(parts[0].trim());
    const ne = normalizeTimeStr(parts[1].trim());
    const [sh, sm] = ns.split(":").map((x) => parseInt(x, 10));
    const [eh, em] = ne.split(":").map((x) => parseInt(x, 10));
    const a = sh * 60 + sm;
    const b = eh * 60 + em;
    if (!(b > a)) return null;
    return { start: a, end: b };
  };

  /** Guest segment fully inside a one-hour PT window (WP / Laravel PT rows are 60 minutes). */
  const guestSlotContainedInPtHourSlot = (guestNorm, ptSlotRaw) => {
    const ptn = normalizeSlotValue(ptSlotRaw);
    const g = boundsMinutesFromNormalizedSlot(guestNorm);
    const p = boundsMinutesFromNormalizedSlot(ptn);
    if (!g || !p || p.end - p.start !== 60) return false;
    return g.start >= p.start && g.end <= p.end;
  };

  const slotHasPersonalTrainingAvailable = (slotValue) => {
    if (!personalTrainerAvailable || !bookingDate || !slotValue) return false;
    if (!gymAllowsOneHourPt()) return false;
    const dayNames = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
    const dayName = dayNames[bookingDate.day()];
    const ptRow = personalTrainerSchedule?.[dayName];
    if (!ptRow) return false;
    const ptDayClosed =
      ptRow.isClosed === true || ptRow.isClosed === "true" || ptRow.isClosed === 1;
    if (ptDayClosed) return false;
    const ptTimeSlots = ptRow.timeSlots;
    if (!Array.isArray(ptTimeSlots) || ptTimeSlots.length === 0) return false;
    const guestNorm = normalizeSlotValue(slotValue);
    for (const s of ptTimeSlots) {
      if (typeof s !== "string") continue;
      if (guestSlotContainedInPtHourSlot(guestNorm, s)) return true;
    }
    return false;
  };

  const isSlotPtAvailable = (slotValue) => slotHasPersonalTrainingAvailable(slotValue);

  // Party size change: match Laravel capacity (merged slot range). Clear slots if no longer fits.
  const handlePersonsChange = (value) => {
    const timeSlots = form.getFieldValue("timeSlot");
    if (!timeSlots || timeSlots.length === 0) return;

    const limit = getPersonLimit();
    const parsed = parseInt(String(value ?? form.getFieldValue("numberOfPersons") ?? 1), 10);
    const headcount = Math.max(1, Math.min(limit, Number.isFinite(parsed) && parsed > 0 ? parsed : 1));

    if (!slotsAreContiguousRaw(timeSlots)) {
      message.destroy();
      message.warning(
        __("Please select consecutive time slots. Your selection was cleared.", "rent-your-jim")
      );
      form.setFieldsValue({ timeSlot: [] });
      setTrainerPerSlot({});
      setCalculatedPrice(null);
      return;
    }

    const sorted = [...timeSlots].sort((a, b) =>
      normalizeTimeStr(String(a).split("|")[0]).localeCompare(normalizeTimeStr(String(b).split("|")[0]))
    );
    const [rangeStart, rangeEnd] = boundsFromRawSlots(sorted);
    const already = getAlreadyBookedForSlot(rangeStart, rangeEnd);
    const spotsLeft = Math.max(0, limit - already);
    if (headcount > spotsLeft) {
      message.destroy();
      message.warning(
        __(
          `Not enough capacity for ${headcount} people in the selected time range (${spotsLeft} spot(s) left). Please choose different slots.`,
          "rent-your-jim"
        )
      );
      form.setFieldsValue({ timeSlot: [] });
      setTrainerPerSlot({});
      setCalculatedPrice(null);
      return;
    }

    const [firstStart] = sorted[0].split("|");
    const [, lastEnd] = sorted[sorted.length - 1].split("|");
    const trialNorm = ptFreeTrialSlot ? normalizeSlotValue(ptFreeTrialSlot) : "";
    const trialStillSelected =
      trialNorm &&
      timeSlots.some((s) => normalizeSlotValue(String(s)) === trialNorm);
    const currentTrainerSelections =
      ptAddOnType === "free_trial" && ptFreeTrialSlot && trialStillSelected
        ? { [ptFreeTrialSlot]: true }
        : trainerPerSlot;
    const applyPtFreeTrial = ptAddOnType === "free_trial" && !!ptFreeTrialSlot;
    calculateTotalPrice(firstStart, lastEnd, headcount, currentTrainerSelections, applyPtFreeTrial);
  };

  /** "9:00", "09:00:00", "9:00:00 AM" → minutes from midnight (WP / Laravel schedules use mixed formats). */
  const timeStrToMinutes = (t) => {
    const raw = String(t ?? "").trim();
    if (!raw) return 0;
    const upper = raw.toUpperCase();
    if (upper.includes("AM") || upper.includes("PM")) {
      const isPm = upper.includes("PM");
      const core = raw.replace(/AM|PM/gi, "").trim();
      const p = core.split(":");
      let h = parseInt(p[0], 10) || 0;
      const m = parseInt(p[1], 10) || 0;
      if (isPm && h !== 12) h += 12;
      if (!isPm && h === 12) h = 0;
      return h * 60 + m;
    }
    const p = raw.split(":");
    const h = parseInt(p[0], 10) || 0;
    const m = parseInt(p[1], 10) || 0;
    return h * 60 + m;
  };

  // Generate available time slots for dropdown (port of WP rent-your-jim `generateTimeSlots` + Laravel capacity)
  const generateTimeSlots = () => {
    if (!bookingDate) return [];
    const operatingHours = getOperatingHours(bookingDate);
    if (!operatingHours || !operatingHours.startTime || !operatingHours.endTime) return [];
    if (operatingHours.isClosed === true || operatingHours.isClosed === "true" || operatingHours.isClosed === 1) {
      return [];
    }

    const slotDuration = getSlotDuration(); // 40 or 60 minutes
    const slots = [];
    const personLimit = getPersonLimit();
    const numberOfPersons = Math.max(
      1,
      parseInt(String(watchedNumberOfPersons ?? 1), 10) || 1
    );
    const selectedTimeSlots = Array.isArray(watchedTimeSlots) ? watchedTimeSlots : [];
    const selectedSet = new Set(selectedTimeSlots);
    const sel = selectedTimeSlots;

    const startMinutes = timeStrToMinutes(operatingHours.startTime);
    const endMinutes = timeStrToMinutes(operatingHours.endTime);

    // Format time for display (12-hour format)
    const formatTimeDisplay = (totalMinutes) => {
      const hours = Math.floor(totalMinutes / 60);
      const mins = totalMinutes % 60;
      const ampm = hours >= 12 ? 'PM' : 'AM';
      const displayHour = hours % 12 || 12;
      return `${displayHour}:${mins.toString().padStart(2, '0')} ${ampm}`;
    };

    // Generate slots
    let currentStart = startMinutes;
    while (currentStart + slotDuration <= endMinutes) {
      const slotEnd = currentStart + slotDuration;
      const startTimeStr = `${Math.floor(currentStart / 60).toString().padStart(2, '0')}:${(currentStart % 60).toString().padStart(2, '0')}`;
      const endTimeStr = `${Math.floor(slotEnd / 60).toString().padStart(2, '0')}:${(slotEnd % 60).toString().padStart(2, '0')}`;
      const slotValue = `${startTimeStr}|${endTimeStr}`;

      // Same capacity model as Laravel: one merged window for the whole contiguous selection,
      // not per 40-minute slice (that double-counted overlap and showed false "Fully booked").
      const normSlot = normalizeSlotValue(slotValue);
      const normSel = sel.map(normalizeSlotValue).filter(Boolean);
      const mergedForCapacity =
        sel.length > 0 && normSel.includes(normSlot) && slotsAreContiguousRaw(sel);
      let rangeStart;
      let rangeEnd;
      if (mergedForCapacity) {
        const sortedSel = [...sel].sort((a, b) =>
          normalizeTimeStr(String(a).split("|")[0]).localeCompare(normalizeTimeStr(String(b).split("|")[0]))
        );
        [rangeStart, rangeEnd] = boundsFromRawSlots(sortedSel);
      } else {
        rangeStart = normalizeTimeStr(startTimeStr);
        rangeEnd = normalizeTimeStr(endTimeStr);
      }
      const alreadyBooked = getAlreadyBookedForSlot(rangeStart, rangeEnd);
      const spotsLeft = Math.max(0, personLimit - alreadyBooked);
      const isFullyBooked = spotsLeft < numberOfPersons;
      const isAlreadySelected = selectedSet.has(slotValue);
      // Do not disable already-selected options: Ant Design Select can drop disabled
      // selected values, which clears the field when party size changes.
      const disableOption = isFullyBooked && !isAlreadySelected;

      const baseLabel = `${formatTimeDisplay(currentStart)} - ${formatTimeDisplay(slotEnd)}`;
      let label = isFullyBooked
        ? `${baseLabel} (${__("Fully booked", "rent-your-jim")})`
        : baseLabel;
      if (!isFullyBooked && slotHasPersonalTrainingAvailable(slotValue)) {
        label += ` (${__("personal training available", "rent-your-jim")})`;
      }

      slots.push({
        value: slotValue,
        label,
        startTime: startTimeStr,
        endTime: endTimeStr,
        disabled: disableOption,
      });
      currentStart += slotDuration;
    }

    return slots;
  };

  // Handle slot selection (multiple slots)
  const handleSlotChange = (values) => {
    if (!values || values.length === 0) {
      setCalculatedPrice(null);
      setTrainerPerSlot({});
      setPtFreeTrialSlot(null);
      return;
    }
    
    // If free trial mode and selected slots no longer include the trial slot, clear it
    const trialNorm = ptFreeTrialSlot ? normalizeSlotValue(ptFreeTrialSlot) : "";
    const trialStillIn =
      trialNorm && values.some((s) => normalizeSlotValue(String(s)) === trialNorm);
    if (ptAddOnType === "free_trial" && ptFreeTrialSlot && !trialStillIn) {
      setPtFreeTrialSlot(null);
      setTrainerPerSlot({});
    }

    // Paid mode: merge trainer flags synchronously so calculateTotalPrice is not stale vs setState.
    // New PT-eligible slots default to ON so totals match "paid personal training" unless the guest opts out.
    let mergedTrainerForPrice = trainerPerSlot;
    if (ptAddOnType === "paid") {
      const newTrainerSlots = {};
      values.forEach((slot) => {
        const prevVal = trainerPerSlot[slot];
        if (isSlotPtAvailable(slot)) {
          newTrainerSlots[slot] = prevVal !== undefined ? prevVal : true;
        } else {
          newTrainerSlots[slot] = false;
        }
      });
      mergedTrainerForPrice = newTrainerSlots;
      setTrainerPerSlot(newTrainerSlots);
    } else {
      // free trial mode: trainerPerSlot is managed by the radio selection
      setTrainerPerSlot((prev) => {
        if (ptFreeTrialSlot && trialStillIn) {
          return { [ptFreeTrialSlot]: true };
        }
        return {};
      });
    }

    if (!slotsAreContiguousRaw(values)) {
      setCalculatedPrice(null);
      return;
    }

    setStep0Error((prev) => (prev === msgSlotsMustBeConsecutive ? null : prev));

    const sortedSlots = [...values].sort((a, b) => {
      const [aStart] = a.split('|');
      const [bStart] = b.split('|');
      return aStart.localeCompare(bStart);
    });

    const [firstStart] = sortedSlots[0].split('|');
    const [, lastEnd] = sortedSlots[sortedSlots.length - 1].split('|');

    const numberOfPersons = form.getFieldValue("numberOfPersons") || 1;

    const currentTrainerSelections = {};
    if (ptAddOnType === "free_trial") {
      if (ptFreeTrialSlot && trialStillIn) currentTrainerSelections[ptFreeTrialSlot] = true;
    } else {
      values.forEach((slot) => {
        currentTrainerSelections[slot] = !!mergedTrainerForPrice[slot];
      });
    }

    const applyPtFreeTrial = ptAddOnType === "free_trial" && !!ptFreeTrialSlot && trialStillIn;
    calculateTotalPrice(firstStart, lastEnd, numberOfPersons, currentTrainerSelections, applyPtFreeTrial);
  };

  /**
   * Laravel booking JSON body (store + payment-intent), or quote preview when `forQuote` is true
   * (omits `accept_terms`; guest fields may be empty for preview).
   */
  const buildBookingPayload = (values, opts = {}) => {
    const forQuote = opts.forQuote === true;
    if (!values?.timeSlot?.length) {
      throw new Error("Missing time slots");
    }
    const sortedSlots = [...values.timeSlot].sort((a, b) => {
      const [aStart] = a.split("|");
      const [bStart] = b.split("|");
      return aStart.localeCompare(bStart);
    });
    const isPtFreeTrial = ptAddOnType === "free_trial" && !!ptFreeTrialSlot;
    const rawTrainer =
      isPtFreeTrial && ptFreeTrialSlot
        ? { [ptFreeTrialSlot]: true }
        : Object.fromEntries(
            Object.entries(trainerPerSlot || {}).filter(([slot]) => isSlotPtAvailable(slot))
          );
    const trainerPerSlotFiltered = {};
    Object.keys(rawTrainer).forEach((k) => {
      trainerPerSlotFiltered[normalizeSlotValue(k)] = rawTrainer[k];
    });
    let pt_addon = "none";
    if (isPtFreeTrial) {
      pt_addon = "free_trial";
    } else if (ptAddOnType === "paid" && getTrainerSlotCount(trainerPerSlotFiltered) > 0) {
      pt_addon = "paid";
    }
    const slotDur = getSlotDuration();
    const normalizedSlots = sortedSlots.map((s) => normalizeSlotValue(s));
    const guestEmailRaw = String(values.guestEmail ?? "").trim();
    const guest_email =
      guestEmailRaw ||
      (isSubscriber && subscriberAccountEmail ? subscriberAccountEmail : "");
    const payload = {
      guest_name: values.guestName || "",
      guest_email,
      guest_phone: values.guestPhone || "",
      notes: values.notes || "",
      booking_date: values.bookingDate.format("YYYY-MM-DD"),
      slot_duration_minutes: slotDur,
      time_slots: normalizedSlots,
      number_of_persons: values.numberOfPersons ?? 1,
      trainer_per_slot: trainerPerSlotFiltered,
      pt_addon,
      pt_free_trial_slot:
        pt_addon === "free_trial" && ptFreeTrialSlot
          ? normalizeSlotValue(ptFreeTrialSlot)
          : null,
    };
    if (!forQuote) {
      payload.accept_terms = !!values.agreeTerms;
    }
    const cc = String(values.couponCode ?? "").trim();
    if (cc) {
      payload.coupon_code = cc;
    }
    return payload;
  };

  const postJson = async (url, body) => {
    const res = await fetch(url, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify(body),
    });
    const data = await res.json().catch(() => ({}));
    return { res, data };
  };

  const firstErrorMessageFromPayload = (data, fallbackMessage) => {
    const parts = [];
    if (data?.errors && typeof data.errors === "object") {
      Object.keys(data.errors).forEach((k) => {
        const arr = data.errors[k];
        if (Array.isArray(arr)) parts.push(arr.join(" "));
      });
    }
    return parts.join(" ") || data?.message || fallbackMessage;
  };

  const applyQuoteToCalculatedPrice = (data, couponFallback = "") => {
    const disc = Number(data.coupon_discount);
    setCalculatedPrice((prev) => ({
      ...(prev || {}),
      slots: data.slots,
      slotDuration: data.slot_duration,
      persons: data.persons,
      pricePerSlot: Number(data.price_per_slot).toFixed(2),
      pricePerPerson: Number(data.price_per_person).toFixed(2),
      gymSubtotalBeforeCoupon: Number(
        data.gym_subtotal_before_coupon ?? data.full_gym_base_before_coupon ?? data.base_price
      ).toFixed(2),
      basePrice: Number(data.base_price).toFixed(2),
      trainerFee: Number(data.trainer_fee).toFixed(2),
      trainerSlotCount: data.trainer_slot_count,
      includesTrainer: !!data.includes_trainer,
      ptFreeTrial: !!data.pt_free_trial,
      total: Number(data.total_price).toFixed(2),
      couponDiscount: Number.isFinite(disc) ? disc.toFixed(2) : "0.00",
      couponCodeApplied: data.coupon_code || couponFallback,
      couponAppliedSlots: Math.max(
        0,
        parseInt(String(data.coupon_applied_slots ?? 0), 10) || 0
      ),
      subtotalBeforeCoupon: Number(data.subtotal_before_coupon).toFixed(2),
    }));
  };

  const validateBookingServerSide = async (values) => {
    if (!quoteUrl) {
      return { ok: true };
    }
    let payload;
    try {
      payload = buildBookingPayload(values, { forQuote: true });
    } catch {
      return { ok: false, error: __("Please complete booking details first.", "rent-your-jim") };
    }

    const { res, data } = await postJson(quoteUrl, payload);
    if (!res.ok || !data?.success) {
      return {
        ok: false,
        error: firstErrorMessageFromPayload(
          data,
          __("Please review your booking details and try again.", "rent-your-jim")
        ),
      };
    }

    applyQuoteToCalculatedPrice(data, String(values.couponCode ?? "").trim());
    return { ok: true };
  };

  /**
   * POST quote with current form values (including coupon_code when the field is non-empty).
   * @returns {"ok"|"error"|"cancelled"|"noop"}
   */
  async function performQuoteRequest() {
    if (!quoteUrl) return "error";
    const values = form.getFieldsValue(true);
    if (!values.bookingDate || !Array.isArray(values.timeSlot) || values.timeSlot.length === 0) {
      return "noop";
    }
    if (!slotsAreContiguousRaw(values.timeSlot)) {
      return "noop";
    }
    const couponTrim = String(values.couponCode ?? "").trim();
    if (!couponTrim) return "noop";

    const guestEmailForCoupon = String(values.guestEmail ?? "").trim();
    if (!isSubscriber && !guestEmailForCoupon) {
      return "noop";
    }

    let payload;
    try {
      payload = buildBookingPayload(values, { forQuote: true });
    } catch {
      return "noop";
    }
    const requestId = ++quoteRequestIdRef.current;
    const { res, data } = await postJson(quoteUrl, payload);
    if (requestId !== quoteRequestIdRef.current) return "cancelled";
    if (!res.ok) {
      message.error(
        firstErrorMessageFromPayload(
          data,
          __("Could not apply that promo code.", "rent-your-jim")
        )
      );
      return "error";
    }
    if (!data.success) {
      message.error(
        firstErrorMessageFromPayload(data, __("Could not apply that promo code.", "rent-your-jim"))
      );
      return "error";
    }
    applyQuoteToCalculatedPrice(data, couponTrim);
    return "ok";
  }

  const handleApplyCoupon = async () => {
    const values = form.getFieldsValue(true);
    const couponTrim = String(values.couponCode ?? "").trim();
    if (!couponTrim) {
      message.warning(__("Enter a coupon code first.", "rent-your-jim"));
      return;
    }
    if (!quoteUrl) {
      message.warning(__("Promo codes are not available for this listing.", "rent-your-jim"));
      return;
    }
    if (!values.bookingDate || !Array.isArray(values.timeSlot) || values.timeSlot.length === 0) {
      message.warning(__("Select date and time slots first.", "rent-your-jim"));
      return;
    }
    if (!slotsAreContiguousRaw(values.timeSlot)) {
      message.warning(msgSlotsMustBeConsecutive);
      return;
    }
    const guestEmailRaw = String(values.guestEmail ?? "").trim();
    if (!isSubscriber && !guestEmailRaw) {
      message.warning(
        __("Enter your email address before applying a coupon.", "rent-your-jim")
      );
      return;
    }
    setApplyingCoupon(true);
    try {
      const result = await performQuoteRequest();
      if (result === "ok") {
        message.success(__("Coupon applied.", "rent-your-jim"));
      }
    } finally {
      setApplyingCoupon(false);
    }
  };

  // When a promo code is entered, ask the server for the authoritative breakdown (coupon rules, caps, etc.).
  useEffect(() => {
    if (!quoteUrl) return;
    const couponTrim =
      watchedCouponCode != null && watchedCouponCode !== undefined
        ? String(watchedCouponCode).trim()
        : "";
    if (!couponTrim) return;

    const timeSlots = Array.isArray(watchedTimeSlots) ? watchedTimeSlots : [];
    if (!bookingDate || timeSlots.length === 0) return;
    if (!slotsAreContiguousRaw(timeSlots)) return;

    const delayMs = 450;
    const timer = setTimeout(() => {
      void performQuoteRequest();
    }, delayMs);

    return () => {
      clearTimeout(timer);
      quoteRequestIdRef.current += 1;
    };
  }, [
    quoteUrl,
    watchedCouponCode,
    bookingDate,
    watchedTimeSlots,
    watchedNumberOfPersons,
    trainerPerSlot,
    ptAddOnType,
    ptFreeTrialSlot,
    selectedDurationType,
    form,
    message,
  ]);

  /** Create booking without Stripe (cash / zero-amount server path). */
  const submitStoreBooking = async (payload) => {
    if (!storeUrl) {
      throw new Error("Booking endpoint is not configured.");
    }
    const { res, data } = await postJson(storeUrl, payload);
    if (!res.ok) {
      const parts = [];
      if (data.errors) {
        Object.keys(data.errors).forEach((k) => {
          const arr = data.errors[k];
          if (Array.isArray(arr)) parts.push(arr.join(" "));
        });
      }
      throw new Error(parts.join(" ") || data.message || "Request failed");
    }
    if (!data.success) {
      throw new Error(data.message || "Failed to create booking");
    }
    setConfirmationCode(data.confirmation_code || "");
    setCancelBookingUrl(
      typeof data.cancel_booking_url === "string" ? data.cancel_booking_url.trim() : ""
    );
    setSubmitted(true);
    message.success(__("Booking confirmed!", "rent-your-jim"));
  };

  // Handle form submission with payment
  const handleSubmit = async (values) => {
    if (currentStep === 0) {
      setStep0Error(null);
      // Validate booking details first
      if (!values.bookingDate || !values.timeSlot || values.timeSlot.length === 0) {
        setStep0Error(__("Please select date and at least one time slot", "rent-your-jim"));
        return;
      }
      if (!slotsAreContiguousRaw(values.timeSlot)) {
        setStep0Error(msgSlotsMustBeConsecutive);
        return;
      }

      const effectiveGuestEmailStep =
        String(values.guestEmail ?? "").trim() ||
        (isSubscriber && subscriberAccountEmail ? subscriberAccountEmail : "");
      if (!values.guestName || !effectiveGuestEmailStep) {
        setStep0Error(__("Please fill in all required guest information", "rent-your-jim"));
        return;
      }

      if (!calculatedPrice || calculatedPrice.total == null) {
        setStep0Error(__("Please select date and time to calculate price", "rent-your-jim"));
        return;
      }

      setLoading(true);
      try {
        const serverValidation = await validateBookingServerSide(values);
        if (!serverValidation.ok) {
          setStep0Error(serverValidation.error || __("Please review booking details.", "rent-your-jim"));
          return;
        }
      } catch (error) {
        setStep0Error(error?.message || __("Please review booking details.", "rent-your-jim"));
        return;
      } finally {
        setLoading(false);
      }

      const payload = buildBookingPayload(values);

      if (!stripePublishableKey || !stripe) {
        setLoading(true);
        try {
          await submitStoreBooking(payload);
          setStep0Error(null);
        } catch (error) {
          console.error("Booking error:", error);
          setStep0Error(error?.message || "Failed to create booking. Please try again.");
        } finally {
          setLoading(false);
        }
        return;
      }

      const totalNum = Number(calculatedPrice.total);
      if (!Number.isFinite(totalNum) || totalNum <= 0) {
        setLoading(true);
        try {
          await submitStoreBooking(payload);
          setStep0Error(null);
        } catch (error) {
          console.error("Booking error:", error);
          setStep0Error(error?.message || "Failed to create booking. Please try again.");
        } finally {
          setLoading(false);
        }
        return;
      }

      if (!paymentIntentUrl) {
        setStep0Error(__("Payment URL is not configured.", "rent-your-jim"));
        return;
      }

      setLoading(true);
      setStep0Error(null);
      try {
        const { res, data } = await postJson(paymentIntentUrl, payload);
        if (!res.ok) {
          if (data.zero_amount) {
            await submitStoreBooking(payload);
            setStep0Error(null);
            return;
          }
          const parts = [];
          if (data.errors) {
            Object.keys(data.errors).forEach((k) => {
              const arr = data.errors[k];
              if (Array.isArray(arr)) parts.push(arr.join(" "));
            });
          }
          throw new Error(
            parts.join(" ") || data.message || __("Failed to continue to payment.", "rent-your-jim")
          );
        }
        if (!data.client_secret || !data.payment_intent_id) {
          throw new Error(__("Invalid payment response.", "rent-your-jim"));
        }
        setPaymentIntent({
          clientSecret: data.client_secret,
          paymentIntentId: data.payment_intent_id,
        });
        setStep0Error(null);
        setCurrentStep(1);
      } catch (error) {
        console.error("Payment intent error:", error);
        setStep0Error(error?.message || __("Failed to continue to payment. Please try again.", "rent-your-jim"));
      } finally {
        setLoading(false);
      }
      return;
    }

    // Step 1: Process payment
    if (currentStep === 1) {
      setStep1Error(null);
      if (!stripe || !paymentElement || !paymentIntent || !elements) {
        setStep1Error(__("Payment system not initialized. Please wait a moment and try again.", "rent-your-jim"));
        return;
      }

      setLoading(true);
      setStep1Error(null);

      try {
        // Confirm payment with Stripe Payment Element
        const { error: stripeError, paymentIntent: confirmedIntent } = await stripe.confirmPayment({
          elements: elements,
          confirmParams: {
            return_url: window.location.href,
            payment_method_data: {
              billing_details: {
                name: values.guestName,
                email: values.guestEmail,
                phone: values.guestPhone || undefined,
              },
            },
          },
          redirect: 'if_required', // Don't redirect, handle in place
        });

        if (stripeError) {
          setStep1Error(stripeError.message || __("Payment failed", "rent-your-jim"));
          setLoading(false);
          return;
        }

        if (confirmedIntent && confirmedIntent.status === "succeeded") {
          if (!confirmPaymentUrl) {
            setStep1Error(__("Confirm URL is not configured.", "rent-your-jim"));
            setLoading(false);
            return;
          }
          const { res, data } = await postJson(confirmPaymentUrl, {
            payment_intent_id: confirmedIntent.id,
          });
          if (!res.ok) {
            const parts = [];
            if (data.errors) {
              Object.keys(data.errors).forEach((k) => {
                const arr = data.errors[k];
                if (Array.isArray(arr)) parts.push(arr.join(" "));
              });
            }
            throw new Error(
              parts.join(" ") || data.message || __("Could not finalize booking.", "rent-your-jim")
            );
          }
          if (data.success) {
            setStep1Error(null);
            setConfirmationCode(data.confirmation_code || "");
            setCancelBookingUrl(
              typeof data.cancel_booking_url === "string" ? data.cancel_booking_url.trim() : ""
            );
            setSubmitted(true);
            message.success(__("Booking confirmed!", "rent-your-jim"));
          } else {
            setStep1Error(data.message || __("Could not finalize booking.", "rent-your-jim"));
          }
        } else {
          setStep1Error(__("Payment was not successful", "rent-your-jim"));
        }
      } catch (error) {
        console.error("Payment error:", error);
        setStep1Error(error?.message || __("Payment processing failed. Please try again.", "rent-your-jim"));
      } finally {
        setLoading(false);
      }
    }
  };

  // Mount Stripe Payment Element
  useEffect(() => {
    if (paymentElement && currentStep === 1) {
      const paymentContainer = document.getElementById('stripe-payment-element');
      if (paymentContainer && !paymentContainer.hasChildNodes()) {
        paymentElement.mount('#stripe-payment-element');
      }
    }
    
    return () => {
      if (paymentElement) {
        paymentElement.unmount();
      }
    };
  }, [paymentElement, currentStep]);

  // Show success view after submission
  if (submitted) {
    return (
      <div className="ryj-booking-success">
        <Result
          status="success"
          icon={<CheckCircleOutlined style={{ color: "#006d77" }} />}
          title="Booking Confirmed & Payment Processed!"
          subTitle={
            <>
              <p>Your confirmation code is:</p>
              <strong className="ryj-confirmation-code">{confirmationCode}</strong>
              <p style={{ marginTop: 16 }}>
                Your booking has been confirmed and payment has been processed successfully.
              </p>
              <p style={{ marginTop: 12, padding: "12px 16px", background: "#edf6f9", borderRadius: 10, borderLeft: "4px solid #006d77", fontSize: 14, color: "#0a1f23" }}>
                {__("A confirmation email has been sent to you with your booking details and a link to cancel if needed.", "rent-your-jim")}
              </p>
              {cancelBookingUrl ? (
                <p style={{ marginTop: 16 }}>
                  <a
                    href={cancelBookingUrl}
                    style={{ color: "#b32d2e", fontWeight: 600 }}
                  >
                    {__("Cancel this booking", "rent-your-jim")}
                  </a>
                  <span style={{ display: "block", marginTop: 8, fontSize: 13, color: "#666" }}>
                    {__(
                      "Opens a secure page to cancel and request a card refund (if you paid online). Valid until your session start time.",
                      "rent-your-jim"
                    )}
                  </span>
                </p>
              ) : null}
            </>
          }
          extra={[
            <Button
              type="primary"
              key="another"
              onClick={() => {
                setSubmitted(false);
                setCurrentStep(0);
                setPaymentIntent(null);
                setCalculatedPrice(null);
                setCancelBookingUrl("");
                form.resetFields();
              }}
            >
              Book Another Time
            </Button>,
          ]}
        />
      </div>
    );
  }

  const steps = [
    {
      title: 'Booking Details',
      icon: <CalendarOutlined />,
    },
    {
      title: 'Payment',
      icon: <CreditCardOutlined />,
    },
  ];

  const selectedBookingSlots = Array.isArray(watchedTimeSlots) ? watchedTimeSlots : [];

  return (
    <div className="ryj-booking-form-container">
      <Card className="ryj-booking-card">
        <div className="ryj-booking-header">
          <h2>
            <CalendarOutlined /> Book This Space
          </h2>
          <p>Fill out the form below to book and pay for your session</p>
        </div>

        <Steps current={currentStep} items={steps} style={{ marginBottom: 30 }} />

        <Form
          form={form}
          layout="vertical"
          onFinish={handleSubmit}
          requiredMark="optional"
          initialValues={{
            guestEmail: isSubscriber && subscriberAccountEmail ? subscriberAccountEmail : undefined,
          }}
        >
          {/* Date Selection */}
          <Form.Item
            label="Select Date"
            name="bookingDate"
            rules={[{ required: true, message: "Please select a date" }]}
          >
            <DatePicker
              style={{ width: "100%" }}
              format="MMMM D, YYYY"
              disabledDate={disabledDate}
              placeholder="Choose a date"
              size="large"
              onChange={handleDateChange}
            />
          </Form.Item>

          {/* Availability Info */}
          {bookingDate && getAvailabilityText() && (
            <Alert
              message={getAvailabilityText()}
              type="success"
              showIcon
              style={{ marginBottom: 16, marginTop: -8 }}
            />
          )}

          {/* Time Slot Selection */}
          {bookingDate && (
            <>
              {/* Duration Type Selection (only if multiple available) */}
              {getAvailableDurations(bookingDate).length > 1 && (
                <div style={{ marginBottom: 16 }}>
                  <div style={{ marginBottom: 8, fontWeight: 500 }}>
                    <ClockCircleOutlined /> Choose Slot Duration:
                  </div>
                  <Radio.Group 
                    value={selectedDurationType} 
                    onChange={(e) => {
                      setSelectedDurationType(e.target.value);
                      form.setFieldsValue({ timeSlot: [] });
                      setCalculatedPrice(null);
                      setTrainerPerSlot({});
                      setPtFreeTrialSlot(null);
                    }}
                    buttonStyle="solid"
                  >
                    {getAvailableDurations(bookingDate).includes(40) && (
                      <Radio.Button value={40}>40 Min</Radio.Button>
                    )}
                    {getAvailableDurations(bookingDate).includes(60) && (
                      <Radio.Button value={60}>1 Hour</Radio.Button>
                    )}
                  </Radio.Group>
                </div>
              )}

              <Form.Item
                label={<><ClockCircleOutlined /> Select Time Slots</>}
                name="timeSlot"
                rules={[
                  { required: true, message: "Please select at least one time slot" },
                  {
                    validator: (_, value) => {
                      const arr = Array.isArray(value) ? value : [];
                      if (arr.length > 1 && !slotsAreContiguousRaw(arr)) {
                        return Promise.reject(new Error(msgSlotsMustBeConsecutive));
                      }
                      return Promise.resolve();
                    },
                  },
                ]}
                validateTrigger={["onChange", "onSubmit"]}
                extra={__(
                  "Select multiple slots in one uninterrupted row (no gaps).",
                  "rent-your-jim"
                )}
              >
                <Select
                  key={`slots-${bookingDate ? bookingDate.format("YYYY-MM-DD") : "none"}-${selectedDurationType ?? "x"}`}
                  mode="multiple"
                  style={{ width: "100%" }}
                  size="large"
                  placeholder="Choose one or more time slots"
                  options={generateTimeSlots()}
                  onChange={handleSlotChange}
                  notFoundContent="No available slots for this day"
                />
              </Form.Item>
              {selectedBookingSlots.length > 1 &&
                !slotsAreContiguousRaw(selectedBookingSlots) && (
                  <Alert
                    type="warning"
                    showIcon
                    style={{ marginBottom: 16 }}
                    message={msgSlotsMustBeConsecutive}
                    description={__(
                      "Pricing and promo codes apply only after your gym slots form one continuous block.",
                      "rent-your-jim"
                    )}
                  />
                )}
            </>
          )}

          {/* Number of Persons */}
          {bookingDate && (
            <Form.Item
              label={<><TeamOutlined /> Number of Persons</>}
              name="numberOfPersons"
              initialValue={1}
              rules={[
                { required: true, message: "Enter number of persons" },
                {
                  validator: (_, value) => {
                    const limit = getPersonLimit();
                    if (value && value > limit) {
                      return Promise.reject(new Error(`Maximum ${limit} person(s) allowed`));
                    }
                    return Promise.resolve();
                  }
                }
              ]}
              extra={
                listingPersonLimit != null
                  ? `Maximum ${getPersonLimit()} person(s) allowed at this gym`
                  : `Maximum ${getPersonLimit()} person(s) allowed for this day`
              }
            >
              <InputNumber
                min={1}
                max={getPersonLimit()}
                style={{ width: "100%" }}
                size="large"
                placeholder="Number of persons"
                onChange={handlePersonsChange}
              />
            </Form.Item>
          )}

          {/* Personal Trainer Option - Per Slot (only slots with PT available) */}
          {bookingDate &&
            personalTrainerAvailable &&
            selectedBookingSlots.length > 0 &&
            selectedBookingSlots.some(isSlotPtAvailable) && (
            <div className="ryj-trainer-section" style={{ 
              background: '#f8f9fa', 
              padding: '16px', 
              borderRadius: '8px', 
              marginBottom: '16px',
              border: '1px solid #e9ecef'
            }}>
              <div style={{ marginBottom: '12px' }}>
                <span style={{ fontWeight: 600, fontSize: '17px', display: 'inline-flex', alignItems: 'center', gap: '6px' }}>
                  {personalTrainerIconUrl ? (
                    <img src={personalTrainerIconUrl} alt="" style={{ width: 85, height: 100, objectFit: 'contain' }} />
                  ) : (
                    <span>🏋️</span>
                  )}
                  Personal Trainer Option
                </span>
                <div style={{ fontSize: '12px', color: '#666', marginTop: '4px' }}>
                  {ptAddOnType === "free_trial"
                    ? __("Choose one slot for your free personal training trial (1 per gym).", "rent-your-jim")
                    : `Select which slots you'd like a personal trainer (+$${personalTrainerPriceText}/slot)`}
                </div>
              </div>
              <div style={{ marginBottom: 12 }}>
                <Radio.Group
                  value={ptAddOnType}
                  onChange={(e) => {
                    const next = e.target.value;
                    setPtAddOnType(next);
                    setPtFreeTrialSlot(null);
                    if (next === "paid") {
                      const slots = form.getFieldValue("timeSlot");
                      const arr = Array.isArray(slots) ? slots : [];
                      const nextMap = {};
                      arr.forEach((slot) => {
                        if (isSlotPtAvailable(slot)) {
                          nextMap[slot] = true;
                        }
                      });
                      setTrainerPerSlot(nextMap);
                    } else {
                      setTrainerPerSlot({});
                    }
                  }}
                >
                  <Radio value="paid">
                    {__("Paid personal training", "rent-your-jim")}{" "}
                    <span style={{ color: "#52c41a" }}>{`(+$${personalTrainerPriceText}/slot)`}</span>
                  </Radio>
                  <Radio value="free_trial" style={{ display: "block", marginTop: 8 }}>
                    {__("Use free personal training trial (one slot)", "rent-your-jim")}{" "}
                    <span style={{ color: "#52c41a" }}>{__("FREE", "rent-your-jim")}</span>
                  </Radio>
                </Radio.Group>
                {ptAddOnType === "free_trial" && (
                  <div style={{ marginTop: 8, fontSize: 12, color: "#666" }}>
                    {__("Free trial is limited to 1 per gym. Eligibility is checked when you click Continue to Payment.", "rent-your-jim")}
                  </div>
                )}
              </div>

              {ptAddOnType === "paid" ? (
                selectedBookingSlots.filter(isSlotPtAvailable).map((slot) => {
                  const [startTimeStr, endTimeStr] = slot.split('|');
                  const formatTimeDisplay = (timeStr) => {
                    const [hours, mins] = timeStr.split(':').map(Number);
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const displayHour = hours % 12 || 12;
                    return `${displayHour}:${mins.toString().padStart(2, '0')} ${ampm}`;
                  };
                  const slotLabel = `${formatTimeDisplay(startTimeStr)} - ${formatTimeDisplay(endTimeStr)}`;

                  return (
                    <div key={slot} style={{ marginBottom: '8px' }}>
                      <Checkbox
                        checked={trainerPerSlot[slot] || false}
                        onChange={(e) => {
                          setTrainerPerSlot((prev) => ({
                            ...prev,
                            [slot]: e.target.checked,
                          }));
                        }}
                        style={{ fontSize: '13px' }}
                      >
                        <span>{slotLabel}</span>
                        <span style={{ color: '#52c41a', marginLeft: '8px', fontSize: '12px' }}>
                          +${personalTrainerPriceText}
                        </span>
                      </Checkbox>
                    </div>
                  );
                })
              ) : (
                <Radio.Group
                  value={ptFreeTrialSlot}
                  onChange={(e) => {
                    const slot = e.target.value;
                    setPtFreeTrialSlot(slot);
                    setTrainerPerSlot({ [slot]: true });
                  }}
                >
                  {selectedBookingSlots.filter(isSlotPtAvailable).map((slot) => {
                    const [startTimeStr, endTimeStr] = slot.split('|');
                    const formatTimeDisplay = (timeStr) => {
                      const [hours, mins] = timeStr.split(':').map(Number);
                      const ampm = hours >= 12 ? 'PM' : 'AM';
                      const displayHour = hours % 12 || 12;
                      return `${displayHour}:${mins.toString().padStart(2, '0')} ${ampm}`;
                    };
                    const slotLabel = `${formatTimeDisplay(startTimeStr)} - ${formatTimeDisplay(endTimeStr)}`;

                    return (
                      <div key={slot} style={{ marginBottom: 8 }}>
                        <Radio value={slot} style={{ fontSize: 13 }}>
                          <span>{slotLabel}</span>
                          <span style={{ color: '#52c41a', marginLeft: 8, fontSize: 12 }}>
                            {__("FREE TRIAL", "rent-your-jim")}
                          </span>
                        </Radio>
                      </div>
                    );
                  })}
                </Radio.Group>
              )}
            </div>
          )}

          <div className="ryj-form-section ryj-coupon-section">
            <h3>{__("Coupon", "rent-your-jim")}</h3>
            <div className="ryj-coupon-row">
              <div className="ryj-coupon-input-wrap">
                <Form.Item name="couponCode" noStyle>
                  <Input
                    size="large"
                    placeholder={__("Enter coupon code", "rent-your-jim")}
                    maxLength={64}
                    allowClear
                    autoComplete="off"
                  />
                </Form.Item>
              </div>
              <Button
                type="default"
                size="large"
                className="ryj-coupon-apply-btn"
                loading={applyingCoupon}
                onClick={() => void handleApplyCoupon()}
              >
                {__("Apply Coupon", "rent-your-jim")}
              </Button>
            </div>
          </div>

          {/* Price Calculation Display */}
          {calculatedPrice && (
            <div className="ryj-price-calculation">
              <div className="ryj-price-breakdown">
                <div className="ryj-price-row">
                  <span className="ryj-price-label">Slots:</span>
                  <span className="ryj-price-value">
                    {calculatedPrice.slots} slot{calculatedPrice.slots !== 1 ? 's' : ''} 
                    ({calculatedPrice.slotDuration} min each)
                  </span>
                </div>
                <div className="ryj-price-row">
                  <span className="ryj-price-label">Rate per Slot:</span>
                  <span className="ryj-price-value">${calculatedPrice.pricePerSlot}/slot/person</span>
                </div>
                {calculatedPrice.persons > 1 && (
                  <div className="ryj-price-row">
                    <span className="ryj-price-label">Persons:</span>
                    <span className="ryj-price-value">{calculatedPrice.persons} × ${calculatedPrice.pricePerPerson}</span>
                  </div>
                )}
                {calculatedPrice.includesTrainer && (
                  <>
                    <div className="ryj-price-row">
                      <span className="ryj-price-label">Subtotal:</span>
                      <span className="ryj-price-value">
                        $
                        {calculatedPrice.gymSubtotalBeforeCoupon ?? calculatedPrice.basePrice}
                      </span>
                    </div>
                    <div className="ryj-price-row ryj-trainer-fee">
                      <span className="ryj-price-label">
                        {personalTrainerIconUrl ? (
                          <img src={personalTrainerIconUrl} alt="" style={{ width: 18, height: 18, objectFit: 'contain', verticalAlign: 'middle', marginRight: 6 }} />
                        ) : (
                          <span>🏋️ </span>
                        )}
                        Personal Trainer ({calculatedPrice.trainerSlotCount} slot{calculatedPrice.trainerSlotCount > 1 ? 's' : ''})
                        {calculatedPrice.ptFreeTrial ? ` ${__("(free trial)", "rent-your-jim")}` : ""}:
                      </span>
                      <span className="ryj-price-value">
                        {calculatedPrice.ptFreeTrial ? __("Free", "rent-your-jim") : `+$${calculatedPrice.trainerFee}`}
                      </span>
                    </div>
                  </>
                )}
                {Number(calculatedPrice.couponDiscount) > 0 && (
                  <>
                    <div className="ryj-price-row" style={{ fontSize: 13, color: "#595959" }}>
                      <span className="ryj-price-label">{__("Before promo", "rent-your-jim")}:</span>
                      <span className="ryj-price-value">
                        $
                        {calculatedPrice.gymSubtotalBeforeCoupon ?? calculatedPrice.subtotalBeforeCoupon}
                      </span>
                    </div>
                    <div className="ryj-price-row">
                      <span className="ryj-price-label">
                        {__("Promo", "rent-your-jim")}
                        {calculatedPrice.couponCodeApplied
                          ? ` (${String(calculatedPrice.couponCodeApplied)})`
                          : ""}
                        {(() => {
                          const n = Math.max(
                            0,
                            parseInt(String(calculatedPrice.couponAppliedSlots ?? 0), 10) || 0
                          );
                          if (n <= 0) return "";
                          return n === 1
                            ? ` — ${__("1 free gym slot", "rent-your-jim")}`
                            : ` — ${n} ${__("free gym slots", "rent-your-jim")}`;
                        })()}
                        :
                      </span>
                      <span className="ryj-price-value" style={{ color: "#389e0d" }}>
                        -${calculatedPrice.couponDiscount}
                      </span>
                    </div>
                  </>
                )}
                <div className="ryj-price-row ryj-price-total">
                  <span className="ryj-price-label">Total Price:</span>
                  <span className="ryj-price-value">${calculatedPrice.total}</span>
                </div>
              </div>
            </div>
          )}

          {/* Guest Information */}
          <div className="ryj-form-section">
            <h3>Your Information</h3>

            <Form.Item
              label="Full Name"
              name="guestName"
              rules={[{ required: true, message: "Please enter your name" }]}
            >
              <Input
                prefix={<UserOutlined />}
                placeholder="John Doe"
                size="large"
              />
            </Form.Item>

            <Form.Item
              label="Email Address"
              name="guestEmail"
              rules={[
                { required: true, message: "Please enter your email" },
                { type: "email", message: "Please enter a valid email" },
              ]}
            >
              <Input
                prefix={<MailOutlined />}
                placeholder="john@example.com"
                size="large"
              />
            </Form.Item>

            <Form.Item label="Phone Number" name="guestPhone">
              <Input
                prefix={<PhoneOutlined />}
                placeholder="(555) 123-4567"
                size="large"
              />
            </Form.Item>
          </div>

          {/* Notes */}
          <Form.Item label="Additional Notes" name="notes">
            <Input.TextArea
              placeholder="Any special requests or information for the host..."
              rows={3}
            />
          </Form.Item>

          {/* Payment Step */}
          {currentStep === 1 && (
            <>
              <Divider />
              <div className="ryj-form-section">
                <h3>
                  <CreditCardOutlined /> Payment Information
                </h3>
                
                {calculatedPrice && (
                  <Alert
                    message={`Total Amount: $${calculatedPrice.total}`}
                    description={
                      calculatedPrice.durationMinutes != null
                        ? `${(Number(calculatedPrice.durationMinutes) / 60).toFixed(1)} h • ${calculatedPrice.slots} × ${calculatedPrice.slotDuration} min slots${
                            Number(calculatedPrice.couponDiscount) > 0
                              ? (() => {
                                  const n = Math.max(
                                    0,
                                    parseInt(String(calculatedPrice.couponAppliedSlots ?? 0), 10) || 0
                                  );
                                  if (n > 0) {
                                    return n === 1
                                      ? ` • ${__("1 free gym slot", "rent-your-jim")}`
                                      : ` • ${n} ${__("free gym slots", "rent-your-jim")}`;
                                  }
                                  return ` • ${__("promo applied", "rent-your-jim")}`;
                                })()
                              : ""
                          }`
                        : `${calculatedPrice.slots} × ${calculatedPrice.slotDuration} min slots`
                    }
                    type="info"
                    showIcon
                    style={{ marginBottom: 20 }}
                  />
                )}

                {stripePublishableKey && paymentIntent ? (
                  <div>
                    <Form.Item
                      label="Payment Details"
                      required
                      style={{ marginBottom: 20 }}
                    >
                      <div 
                        id="stripe-payment-element"
                        style={{
                          marginBottom: '12px',
                        }}
                      />
                      <div id="stripe-payment-errors" role="alert" style={{ color: '#fa755a', marginTop: 8, minHeight: '20px' }}></div>
                      <p style={{ fontSize: '12px', color: '#666', marginTop: '8px' }}>
                        Secure payment powered by Stripe
                      </p>
                    </Form.Item>
                  </div>
                ) : stripePublishableKey ? (
                  <Alert
                    message="Loading payment form..."
                    description="Please wait while we initialize the payment system."
                    type="info"
                    showIcon
                  />
                ) : (
                  <Alert
                    message="Stripe is not configured"
                    description="Please contact the administrator to enable payments."
                    type="warning"
                    showIcon
                  />
                )}
              </div>
            </>
          )}

          {/* Terms and Conditions */}
          <Form.Item
            name="agreeTerms"
            valuePropName="checked"
            rules={[
              {
                validator: (_, value) =>
                  value ? Promise.resolve() : Promise.reject(new Error('You must agree to the terms and conditions')),
              },
            ]}
          >
            <Checkbox>
              I agree to the <a href={termsUrl} target="_blank" rel="noopener noreferrer" style={{ color: '#006d77', fontWeight: 600 }}>Terms and Conditions</a> and <a href={privacyUrl} target="_blank" rel="noopener noreferrer" style={{ color: '#006d77', fontWeight: 600 }}>Privacy Policy</a>
            </Checkbox>
          </Form.Item>

          {/* Inline error above Continue to Payment button */}
          {currentStep === 0 && step0Error && (
            <Alert
              message={step0Error}
              type="error"
              showIcon
              closable
              onClose={() => setStep0Error(null)}
              style={{ marginBottom: 16 }}
            />
          )}

          {/* Inline error above Pay & Confirm Booking button */}
          {currentStep === 1 && step1Error && (
            <Alert
              message={step1Error}
              type="error"
              showIcon
              closable
              onClose={() => setStep1Error(null)}
              style={{ marginBottom: 16 }}
            />
          )}

          {/* Submit Button */}
          <Form.Item>
            <div style={{ display: 'flex', gap: 10, justifyContent: 'space-between' }}>
              {currentStep === 1 && (
                <Button
                  onClick={() => {
                    setCurrentStep(0);
                    setPaymentIntent(null);
                    setStep0Error(null);
                    setStep1Error(null);
                  }}
                  size="large"
                >
                  Back
                </Button>
              )}
              <Button
                type="primary"
                htmlType="submit"
                loading={loading}
                size="large"
                block={currentStep === 0}
                className="ryj-submit-btn"
                style={{ flex: currentStep === 1 ? 1 : 'none', marginLeft: currentStep === 1 ? 'auto' : 0 }}
              >
                {loading 
                  ? "Processing..." 
                  : currentStep === 0 
                    ? (stripePublishableKey ? "Continue to Payment" : "Submit Booking Request")
                    : "Pay & Confirm Booking"
                }
              </Button>
            </div>
          </Form.Item>

          {currentStep === 0 && stripePublishableKey && (
            <Alert
              message="You will be redirected to payment after filling out the booking details."
              type="info"
              showIcon
              style={{ marginTop: 16 }}
            />
          )}
          
          {currentStep === 0 && !stripePublishableKey && (
            <Alert
              message="Payment is not configured. Your booking will be submitted as a request."
              type="warning"
              showIcon
              style={{ marginTop: 16 }}
            />
          )}
        </Form>
      </Card>
    </div>
  );
};

// Main Booking Form App — mounted from resources/js/gym-booking-form/main.jsx
const BookingFormApp = () => {
  const [localizedData, setLocalizedData] = useState(null);
  const [bootstrapError, setBootstrapError] = useState("");

  useEffect(() => {
    const jsonEl = document.getElementById("spotmee-booking-bootstrap");
    if (!jsonEl || !jsonEl.textContent) {
      setBootstrapError("Booking bootstrap data not found.");
      return;
    }
    try {
      const raw = JSON.parse(jsonEl.textContent);
      const mapped = buildLocalizedFromBootstrap(raw);
      if (!mapped) {
        setBootstrapError("Booking bootstrap payload is invalid.");
        return;
      }
      setLocalizedData(mapped);
    } catch (error) {
      console.error("Error parsing booking bootstrap:", error);
      setBootstrapError("Could not parse booking data payload.");
    }
  }, []);

  return (
    <ConfigProvider
      locale={enUS}
      theme={{
        token: {
          colorPrimary: "#006d77",
          colorInfo: "#006d77",
          colorSuccess: "#1a8d95",
          colorLink: "#006d77",
          borderRadius: 10,
          fontFamily:
            '"Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        },
      }}
    >
      <App>
        {!localizedData && !bootstrapError ? (
          <div className="ryj-booking-form-container" style={{ textAlign: "center", padding: 48 }}>
            <Spin size="large" />
          </div>
        ) : bootstrapError ? (
          <div className="ryj-booking-form-container">
            <Alert
              type="error"
              showIcon
              message="Booking form failed to load"
              description={bootstrapError}
            />
          </div>
        ) : (
          <BookingFormContent localizedData={localizedData} />
        )}
      </App>
    </ConfigProvider>
  );
};

export default BookingFormApp;
