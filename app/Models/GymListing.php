<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'host_tier',
    'pt_pricing_tier',
    'person_limit',
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

    /**
     * Tier used for personal-trainer add-on guest pricing (admin can override host session tier).
     * When {@see $pt_pricing_tier} is null, pricing follows {@see hostTierKey()}.
     */
    public function ptPricingTierKey(): string
    {
        $raw = $this->pt_pricing_tier;
        if ($raw === null || $raw === '') {
            return $this->hostTierKey();
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
        return self::publicStorageUrl($this->main_image_path);
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        $paths = $this->gallery_paths ?? [];

        return array_values(array_filter(array_map(
            fn (mixed $p) => is_string($p) ? self::publicStorageUrl($p) : null,
            $paths
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
            'person_limit' => 'integer',
        ];
    }
}
