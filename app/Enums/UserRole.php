<?php

namespace App\Enums;

/**
 * Application roles (Spatie). Names are stored in the `roles` table.
 */
enum UserRole: string
{
    case Administrator = 'Administrator';
    case Host = 'Host';
    case Subscriber = 'Subscriber';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
