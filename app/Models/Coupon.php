<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RuntimeException;

class Coupon extends Model
{
    public const TYPE_PERCENT = 'percent';

    public const TYPE_FIXED = 'fixed';

    /** Discount applies to the full gym slot booking total. */
    public const APPLIES_FULL_BOOKING = 'full_booking';

    /** Discount applies only to personal trainer add-on fees (requires PT on the booking). */
    public const APPLIES_PERSONAL_TRAINING = 'personal_training';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'applies_to',
        'max_redemptions',
        'times_used',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_redemptions' => 'integer',
            'times_used' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public static function normalizeCode(string $code): string
    {
        return strtoupper(preg_replace('/\s+/', '', trim($code)));
    }

    /**
     * Random uppercase code using A–Z and 2–9 (no 0/O or 1/I ambiguity). Unique in `coupons.code`.
     *
     * @param  int|null  $ignoreCouponId  When editing, exclude this row so a new code can differ from the current one only against others.
     */
    public static function makeUniqueRandomCode(int $length = 10, ?int $ignoreCouponId = null): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $maxIdx = strlen($chars) - 1;

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, $maxIdx)];
            }

            $query = self::query()->where('code', $code);
            if ($ignoreCouponId !== null) {
                $query->where('id', '!=', $ignoreCouponId);
            }
            if (! $query->exists()) {
                return $code;
            }
        }

        throw new RuntimeException('Unable to generate a unique coupon code.');
    }

    public function appliesToPersonalTrainingOnly(): bool
    {
        return $this->applies_to === self::APPLIES_PERSONAL_TRAINING;
    }

    /**
     * Hosts this coupon is limited to (empty = any host).
     *
     * @return BelongsToMany<User, $this>
     */
    public function hosts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_host')->withTimestamps();
    }

    /**
     * Gym listings this coupon is limited to (empty = any gym).
     *
     * @return BelongsToMany<GymListing, $this>
     */
    public function gymListings(): BelongsToMany
    {
        return $this->belongsToMany(GymListing::class, 'coupon_gym_listing')->withTimestamps();
    }

    /**
     * Whether this coupon may be used for the given gym listing (host + gym restrictions).
     * Empty restrictions mean "any". If both are set, the listing must satisfy both.
     */
    public function appliesToListing(GymListing $listing): bool
    {
        if ($this->relationLoaded('hosts')) {
            if ($this->hosts->isNotEmpty() && ! $this->hosts->contains('id', (int) $listing->user_id)) {
                return false;
            }
        } elseif ($this->hosts()->exists() && ! $this->hosts()->whereKey((int) $listing->user_id)->exists()) {
            return false;
        }

        if ($this->relationLoaded('gymListings')) {
            if ($this->gymListings->isNotEmpty() && ! $this->gymListings->contains('id', (int) $listing->id)) {
                return false;
            }
        } elseif ($this->gymListings()->exists() && ! $this->gymListings()->whereKey((int) $listing->id)->exists()) {
            return false;
        }

        return true;
    }
}
