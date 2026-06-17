<?php

namespace App\Enums;

enum HostPayoutStatus: string
{
    case Pending = 'pending';
    case Skipped = 'skipped';
    case Processing = 'processing';
    case Paid = 'paid';
    case Failed = 'failed';
    case AwaitingConnect = 'awaiting_connect';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Scheduled'),
            self::Skipped => __('Skipped'),
            self::Processing => __('Processing'),
            self::Paid => __('Paid to host'),
            self::Failed => __('Failed'),
            self::AwaitingConnect => __('Awaiting bank link'),
        };
    }
}
