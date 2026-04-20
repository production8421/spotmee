<?php

namespace App\Models;

use App\Enums\HostApplicationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'full_name',
    'date_of_birth',
    'social_security_number',
    'phone',
    'email',
    'street_address',
    'city',
    'state',
    'postal_code',
    'description',
    'status',
    'approved_at',
    'approved_by',
])]
class HostApplication extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'social_security_number' => 'encrypted',
            'status' => HostApplicationStatus::class,
            'approved_at' => 'datetime',
        ];
    }

    public function isApproved(): bool
    {
        return $this->status === HostApplicationStatus::Approved;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
