<?php

namespace App\Support;

/**
 * Human-readable file sizes without requiring the PHP intl extension
 * (Laravel's Number::fileSize() uses intl).
 */
final class HumanFileSize
{
    public static function format(int|float $bytes, int $precision = 1): string
    {
        $bytes = max(0, (float) $bytes);

        if ($bytes < 1) {
            return '0 B';
        }

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min(max($i, 0), count($sizes) - 1);

        return round($bytes / (1024 ** $i), $precision).' '.$sizes[$i];
    }
}
