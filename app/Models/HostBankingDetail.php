<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'host_application_id',
    'account_holder_name',
    'bank_name',
    'account_type',
    'routing_number',
    'account_number',
    'bank_country',
    'notes',
])]
class HostBankingDetail extends Model
{
    /**
     * @var list<string>
     */
    protected $hidden = [
        'routing_number',
        'account_number',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<HostApplication, $this>
     */
    public function hostApplication(): BelongsTo
    {
        return $this->belongsTo(HostApplication::class);
    }

    public function maskedAccountNumber(): string
    {
        return self::maskSensitiveValue($this->account_number);
    }

    public function maskedRoutingNumber(): string
    {
        return self::maskSensitiveValue($this->routing_number);
    }

    public function accountTypeLabel(): string
    {
        return match (strtolower((string) $this->account_type)) {
            'checking' => __('Checking'),
            'savings' => __('Savings'),
            default => $this->account_type ? ucfirst((string) $this->account_type) : '—',
        };
    }

    private static function maskSensitiveValue(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '—';
        }

        $length = strlen($value);
        if ($length <= 4) {
            return str_repeat('•', $length);
        }

        return str_repeat('•', max(4, $length - 4)).substr($value, -4);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'routing_number' => 'encrypted',
            'account_number' => 'encrypted',
        ];
    }
}
