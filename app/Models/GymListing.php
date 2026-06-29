<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'host_tier',
    'host_price_1_hour',
    'pt_pricing_tier',
    'person_limit',
    'service_type',
    'name',
    'slug',
    'description',
    'address',
    'city',
    'state',
    'postal_code',
    'phone',
    'email',
    'website',
    'facility_type',
    'area_size',
    'service_options',
    'pets_policy',
    'check_in_method',
    'equipment',
    'amenities',
    'main_image_path',
    'gallery_paths',
    'intro_video_path',
    'availability_schedule',
    'personal_training_available',
    'personal_training_cert_path',
    'personal_training_cpr_cert_path',
    'personal_training_availability',
    'pt_trainer_levels',
    'is_published',
    'approved_at',
    'rejected_at',
    'rejection_message',
])]
class GymListing extends Model
{
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tier key for guest pricing / PT settings (matches ApplicationSetting tier fields).
     */
    public function hostTierKey(): string
    {
        $t = strtolower((string) ($this->host_tier ?? ''));

        return in_array($t, ['gold', 'platinum'], true) ? $t : 'silver';
    }

    public function usesCustomHostPricing(): bool
    {
        return is_numeric($this->host_price_1_hour) && (float) $this->host_price_1_hour > 0;
    }

    public function hostSessionBasePrice1hr(): ?float
    {
        if (! $this->usesCustomHostPricing()) {
            return null;
        }

        return round((float) $this->host_price_1_hour, 2);
    }

    public function guestSessionCommissionPct(): float
    {
        return ApplicationSetting::instance()->platformCommissionPct();
    }

    public function guestSessionRate1hr(): ?float
    {
        $base = $this->hostSessionBasePrice1hr();
        if ($base === null) {
            return null;
        }

        return ApplicationSetting::tierTotalWithCommission($base, $this->guestSessionCommissionPct());
    }

    /**
     * @return array{rate_1hr: ?float, host_base_1hr: ?float, commission_pct: float}
     */
    public function publicGuestSessionPricing(): array
    {
        return [
            'rate_1hr' => $this->guestSessionRate1hr(),
            'host_base_1hr' => $this->hostSessionBasePrice1hr(),
            'commission_pct' => $this->guestSessionCommissionPct(),
        ];
    }

    /**
     * Tier used for personal-trainer add-on guest pricing (admin can override per listing).
     * When {@see $pt_pricing_tier} is null, defaults to junior trainer pricing (silver).
     */
    public function ptPricingTierKey(): string
    {
        $raw = $this->pt_pricing_tier;
        if ($raw === null || $raw === '') {
            return 'silver';
        }
        $t = strtolower((string) $raw);

        return in_array($t, ['gold', 'platinum'], true) ? $t : 'silver';
    }

    /**
     * Max concurrent guests overlapping a booking window for a day.
     * When {@see $person_limit} is set, it overrides {@see $daySchedule} `personLimit` for capacity checks.
     *
     * @param  array<string, mixed>  $daySchedule
     */
    public function effectivePersonLimit(array $daySchedule): int
    {
        $fromSchedule = max(1, (int) ($daySchedule['personLimit'] ?? 1));
        if ($this->person_limit !== null && (int) $this->person_limit > 0) {
            return max(1, min(100, (int) $this->person_limit));
        }

        return $fromSchedule;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GymBooking, $this>
     */
    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GymBooking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GymReview, $this>
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GymReview::class);
    }

    /**
     * Has the given user completed (or at least paid for) a booking at this listing?
     * Used to gate who can post a review.
     */
    public function hasBookingByUser(?User $user): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        if (! Schema::hasTable('gym_bookings')) {
            return false;
        }

        return $this->bookings()
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();
    }

    /**
     * Eager-load aggregates (`reviews_count`, `reviews_avg_rating`) so list
     * views can render rating badges without N+1 queries. Safe when the
     * `gym_reviews` table has not been migrated yet.
     *
     * @param  Builder<GymListing>  $query
     * @return Builder<GymListing>
     */
    public function scopeWithReviewAggregates(Builder $query): Builder
    {
        if (! Schema::hasTable('gym_reviews')) {
            return $query;
        }

        return $query
            ->withCount(['reviews as reviews_count' => fn (Builder $q) => $q->whereNotNull('approved_at')])
            ->withAvg([
                'reviews as reviews_avg_rating' => fn (Builder $q) => $q->whereNotNull('approved_at'),
            ], 'rating');
    }

    public function pendingHostApproval(): bool
    {
        return $this->user_id !== null
            && $this->approved_at === null
            && $this->rejected_at === null;
    }

    public function rejectedByAdmin(): bool
    {
        return $this->user_id !== null
            && $this->approved_at === null
            && $this->rejected_at !== null;
    }

    public function approvedForHost(): bool
    {
        return $this->user_id !== null && $this->approved_at !== null;
    }

    /**
     * Host-owned listing not yet approved (pending or declined); administrators may publish.
     */
    public function canBeApprovedByAdmin(): bool
    {
        return $this->user_id !== null && $this->approved_at === null;
    }

    public static function makeUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'listing';
        }

        $slug = $base;
        $i = 1;
        while (static::query()
            ->where('slug', $slug)
            ->when($exceptId !== null, fn (Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /**
     * Public URL for a path on the `public` disk.
     *
     * Uses a root-relative URL (/storage/...) so images work when the browser host/port
     * differs from APP_URL (e.g. 127.0.0.1:8000 vs APP_URL=http://localhost).
     */
    public static function publicStorageUrl(mixed $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $segments = array_filter(explode('/', $path), fn (string $s) => $s !== '');
        $encoded = implode('/', array_map(rawurlencode(...), $segments));

        $base = '';
        if (! app()->runningInConsole()) {
            $bp = request()->getBasePath();
            if (is_string($bp) && $bp !== '' && $bp !== '/') {
                $base = rtrim($bp, '/');
            }
        }

        return $base.'/storage/'.$encoded;
    }

    public function mainImageUrl(): ?string
    {
        if (! $this->storagePathExists($this->main_image_path)) {
            return null;
        }

        return self::publicStorageUrl($this->main_image_path);
    }

    public function storagePathExists(?string $path): bool
    {
        return is_string($path) && $path !== '' && Storage::disk('public')->exists($path);
    }

    /**
     * @return list<string>
     */
    public function existingGalleryPaths(): array
    {
        $paths = $this->gallery_paths ?? [];
        if (! is_array($paths)) {
            return [];
        }

        return array_values(array_filter(
            $paths,
            fn (mixed $path): bool => is_string($path) && $this->storagePathExists($path)
        ));
    }

    /**
     * Remove gallery paths whose files are missing from storage.
     */
    public function syncPrunedGalleryPaths(): bool
    {
        $pruned = $this->existingGalleryPaths();
        $current = array_values(is_array($this->gallery_paths) ? $this->gallery_paths : []);
        if ($pruned === $current) {
            return false;
        }

        $this->gallery_paths = $pruned;
        $this->saveQuietly();

        return true;
    }

    /**
     * Clear main image path when the file no longer exists.
     */
    public function syncMissingMainImagePath(): bool
    {
        if ($this->main_image_path === null || $this->main_image_path === '') {
            return false;
        }

        if ($this->storagePathExists($this->main_image_path)) {
            return false;
        }

        $this->main_image_path = null;
        $this->saveQuietly();

        return true;
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        return array_values(array_filter(array_map(
            fn (string $path): ?string => self::publicStorageUrl($path),
            $this->existingGalleryPaths()
        )));
    }

    public function introVideoUrl(): ?string
    {
        return self::publicStorageUrl($this->intro_video_path);
    }

    public function deleteStoredMedia(): void
    {
        $disk = Storage::disk('public');
        if ($this->main_image_path) {
            $disk->delete($this->main_image_path);
        }
        foreach ($this->gallery_paths ?? [] as $path) {
            $disk->delete($path);
        }
        if ($this->intro_video_path) {
            $disk->delete($this->intro_video_path);
        }
        if ($this->personal_training_cert_path) {
            $disk->delete($this->personal_training_cert_path);
        }
        if ($this->personal_training_cpr_cert_path) {
            $disk->delete($this->personal_training_cpr_cert_path);
        }
    }

    public function personalTrainingCertUrl(): ?string
    {
        return self::publicStorageUrl($this->personal_training_cert_path);
    }

    public function personalTrainingCprCertUrl(): ?string
    {
        return self::publicStorageUrl($this->personal_training_cpr_cert_path);
    }

    protected static function booted(): void
    {
        static::deleting(function (GymListing $listing): void {
            $listing->deleteStoredMedia();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'is_published' => 'boolean',
            'service_options' => 'array',
            'equipment' => 'array',
            'amenities' => 'array',
            'gallery_paths' => 'array',
            'availability_schedule' => 'array',
            'personal_training_available' => 'boolean',
            'personal_training_availability' => 'array',
            'pt_trainer_levels' => 'array',
            'person_limit' => 'integer',
            'host_price_1_hour' => 'decimal:2',
        ];
    }

    /**
     * @return list<string> silver|gold|platinum keys enabled for this listing
     */
    public function ptTrainerLevelKeys(): array
    {
        $raw = $this->pt_trainer_levels;
        if (! is_array($raw)) {
            return [];
        }

        $allowed = array_keys(config('gym_listing.pt_trainer_levels', []));

        return array_values(array_unique(array_filter(
            array_map('strval', $raw),
            fn (string $key): bool => in_array($key, $allowed, true)
        )));
    }

    /**
     * Guest-facing PT levels with label and price per slot (from site settings).
     *
     * @return list<array{key: string, label: string, price_per_slot: float}>
     */
    public function ptTrainerLevelsForGuest(): array
    {
        if (! $this->personal_training_available) {
            return [];
        }

        $settings = ApplicationSetting::instance();
        $out = [];

        foreach ($this->ptTrainerLevelKeys() as $key) {
            $price = $settings->publicPtSlotCustomerPrice($key);
            if ($price <= 0) {
                continue;
            }
            $out[] = [
                'key' => $key,
                'label' => (string) (config("gym_listing.pt_trainer_levels.{$key}.label") ?? ucfirst($key)),
                'price_per_slot' => round($price, 2),
            ];
        }

        return $out;
    }
}
