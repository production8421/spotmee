<?php

namespace App\Support;

/**
 * Batch upload limits must stay at or below PHP's max_file_uploads (default 20),
 * otherwise PHP emits a startup warning and drops the request before Laravel runs.
 */
final class MediaUploadLimits
{
    /** Hard cap per request to keep memory and request time reasonable. */
    public const APP_MAX_FILES = 50;

    public static function maxFilesPerRequest(): int
    {
        $phpLimit = (int) ini_get('max_file_uploads');

        if ($phpLimit < 1) {
            $phpLimit = 20;
        }

        return min(self::APP_MAX_FILES, $phpLimit);
    }
}
