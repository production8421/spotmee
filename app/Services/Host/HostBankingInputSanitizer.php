<?php

namespace App\Services\Host;

use Illuminate\Support\Str;

class HostBankingInputSanitizer
{
    public function sanitizeAccountHolderName(string $value): string
    {
        return $this->sanitizeText($value, 200);
    }

    public function sanitizeBankName(string $value): string
    {
        return $this->sanitizeText($value, 200);
    }

    public function sanitizeNotes(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $notes = $this->sanitizeText($value, 1000);

        return $notes === '' ? null : $notes;
    }

    public function digitsOnly(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $value);

        return is_string($digits) && $digits !== '' ? $digits : null;
    }

    public function isValidUsRoutingNumber(string $routingNumber): bool
    {
        if (! preg_match('/^\d{9}$/', $routingNumber)) {
            return false;
        }

        $digits = array_map('intval', str_split($routingNumber));
        $checksum = (
            3 * ($digits[0] + $digits[3] + $digits[6])
            + 7 * ($digits[1] + $digits[4] + $digits[7])
            + ($digits[2] + $digits[5] + $digits[8])
        ) % 10;

        return $checksum === 0;
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
