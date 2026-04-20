<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymBooking extends Model
{
    protected $fillable = [
        'gym_listing_id',
        'user_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'number_of_persons',
        'guest_name',
        'guest_email',
        'guest_phone',
        'notes',
        'personal_trainer_requested',
        'trainer_per_slot',
        'trainer_slot_count',
        'pt_free_trial',
        'pt_free_trial_slot',
        'total_price',
        'currency',
        'status',
        'confirmation_code',
        'stripe_payment_intent_id',
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
            'pt_free_trial' => 'boolean',
            'total_price' => 'decimal:2',
        ];
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
}
