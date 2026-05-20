<?php

namespace App\Models;

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
            'time_slots' => 'array',
            'pt_free_trial' => 'boolean',
            'total_price' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'coupon_applied_slots' => 'integer',
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
}
