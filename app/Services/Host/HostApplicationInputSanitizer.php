<?php

namespace App\Services\Host;

use Illuminate\Support\Str;

class HostApplicationInputSanitizer
{
    public function sanitizeName(string $value): string
    {
        return $this->sanitizeText($value, 255);
    }

    public function sanitizeEmail(string $value): string
    {
        return Str::lower(trim(strip_tags(str_replace("\0", '', $value))));
    }

    public function sanitizePhone(string $value): string
    {
        return trim(strip_tags(str_replace("\0", '', $value)));
    }

    public function sanitizeAddressLine(string $value, int $max = 255): string
    {
        return $this->sanitizeText($value, $max);
    }

    public function sanitizeDescription(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $description = $this->sanitizeText($value, 5000);

        return $description === '' ? null : $description;
    }

    private function sanitizeText(string $value, int $maxLength): string
    {
        $value = str_replace("\0", '', $value);
        $value = strip_tags($value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);

        return Str::limit($value, $maxLength, '');
    }
}
