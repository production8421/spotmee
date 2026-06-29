<?php

namespace App\Models;

use App\Enums\HostPayoutStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class GymBooking extends Model
{
    protected $fillable = [
        'gym_listing_id',
        'user_id',
        'booking_date',
        'start_time',
        'end_time',
        'time_slots',
        'duration_hours',
        'number_of_persons',
        'guest_name',
        'guest_email',
        'guest_phone',
        'notes',
        'personal_trainer_requested',
        'trainer_per_slot',
        'trainer_slot_count',
        'pt_trainer_level_keys',
        'pt_trainer_levels_per_slot',
        'pt_free_trial',
        'pt_free_trial_slot',
        'total_price',
        'currency',
        'status',
        'confirmation_code',
        'stripe_payment_intent_id',
        'coupon_id',
        'coupon_discount',
        'coupon_applied_slots',
        'host_payout_scheduled_at',
        'host_payout_amount',
        'host_payout_status',
        'host_payout_processed_at',
        'stripe_transfer_id',
        'host_payout_skip_reason',
        'host_payout_failure_reason',
        'host_payout_split_enabled',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'personal_trainer_requested' => 'boolean',
            'trainer_per_slot' => 'array',
            'pt_trainer_level_keys' => 'array',
            'pt_trainer_levels_per_slot' => 'array',
            'time_slots' => 'array',
            'pt_free_trial' => 'boolean',
            'total_price' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'coupon_applied_slots' => 'integer',
            'host_payout_scheduled_at' => 'datetime',
            'host_payout_amount' => 'decimal:2',
            'host_payout_processed_at' => 'datetime',
            'host_payout_split_enabled' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Coupon, $this>
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return BelongsTo<GymListing, $this>
     */
    public function gymListing(): BelongsTo
    {
        return $this->belongsTo(GymListing::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateConfirmationCode(): string
    {
        return 'RYJ-'.strtoupper(substr(bin2hex(random_bytes(6)), 0, 10));
    }

    public function bookingStartAt(): Carbon
    {
        $dateStr = $this->booking_date instanceof \DateTimeInterface
            ? $this->booking_date->format('Y-m-d')
            : (string) $this->booking_date;
        $timeRaw = $this->start_time;
        if ($timeRaw instanceof \DateTimeInterface) {
            $timeStr = $timeRaw->format('H:i:s');
        } else {
            $timeStr = is_string($timeRaw) ? substr($timeRaw, 0, 8) : '00:00:00';
        }

        return Carbon::parse($dateStr.' '.$timeStr, config('app.timezone'));
    }

    public function isCancellable(): bool
    {
        if (($this->status ?? '') !== 'confirmed') {
            return false;
        }

        return $this->bookingStartAt()->isFuture();
    }

    public function signedCancelUrl(): string
    {
        return URL::temporarySignedRoute(
            'public.gym-bookings.cancel',
            $this->bookingStartAt(),
            ['booking' => $this->id],
            absolute: true,
        );
    }

    public function hostPayoutStatusEnum(): ?HostPayoutStatus
    {
        return HostPayoutStatus::tryFrom((string) ($this->host_payout_status ?? ''));
    }

    public function hostPayoutStatusLabel(): string
    {
        $status = $this->hostPayoutStatusEnum();

        return $status?->label() ?? '—';
    }

    public function isHostPayoutSplitEnabled(): bool
    {
        if (! \Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'host_payout_split_enabled')) {
            return true;
        }

        return (bool) ($this->host_payout_split_enabled ?? true);
    }

    public function canChangeHostPayoutSplitToggle(): bool
    {
        $status = $this->hostPayoutStatusEnum();

        return ! in_array($status, [HostPayoutStatus::Paid, HostPayoutStatus::Processing], true);
    }

    /**
     * Stripe PaymentIntent used for this checkout (primary row in a date-range batch stores it).
     */
    public function stripePaymentIntentIdForStripe(): ?string
    {
        if (filled($this->stripe_payment_intent_id)) {
            return (string) $this->stripe_payment_intent_id;
        }

        if (! filled($this->guest_email) || ! $this->gym_listing_id || ! $this->user_id) {
            return null;
        }

        $createdAt = $this->created_at;
        if (! $createdAt instanceof \DateTimeInterface) {
            return null;
        }

        $windowStart = Carbon::parse($createdAt)->subMinutes(2);
        $windowEnd = Carbon::parse($createdAt)->addMinutes(2);

        $siblingPi = static::query()
            ->where('gym_listing_id', $this->gym_listing_id)
            ->where('user_id', $this->user_id)
            ->where('guest_email', $this->guest_email)
            ->where('start_time', $this->start_time)
            ->where('end_time', $this->end_time)
            ->whereNotNull('stripe_payment_intent_id')
            ->whereBetween('created_at', [$windowStart, $windowEnd])
            ->whereKeyNot($this->id ?? 0)
            ->orderBy('id')
            ->value('stripe_payment_intent_id');

        return filled($siblingPi) ? (string) $siblingPi : null;
    }
}
