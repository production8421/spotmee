<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'email_verified_at', 'stripe_connect_account_id', 'profile_photo_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GymListing, $this>
     */
    public function gymListings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GymListing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<MediaAsset, $this>
     */
    public function mediaAssets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<HostApplication, $this>
     */
    public function hostApplications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HostApplication::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<HostBankingDetail, $this>
     */
    public function hostBankingDetail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HostBankingDetail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GymReview, $this>
     */
    public function gymReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GymReview::class);
    }

    public function canViewHostBlog(): bool
    {
        return $this->hasRole(UserRole::Host->value)
            || $this->hasRole(UserRole::Administrator->value);
    }

    public function profilePhotoUrl(): ?string
    {
        return GymListing::publicStorageUrl($this->profile_photo_path);
    }

    public function profileInitials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
