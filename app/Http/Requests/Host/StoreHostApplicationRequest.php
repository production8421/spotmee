<?php

namespace App\Http\Requests\Host;

use App\Services\Host\HostApplicationInputSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreHostApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->session()->get('host_apply_terms_accepted') === true;
    }

    protected function prepareForValidation(): void
    {
        $sanitizer = app(HostApplicationInputSanitizer::class);

        $this->merge([
            'full_name' => $sanitizer->sanitizeName((string) $this->input('full_name', '')),
            'email' => $sanitizer->sanitizeEmail((string) $this->input('email', '')),
            'phone' => $sanitizer->sanitizePhone((string) $this->input('phone', '')),
            'street_address' => $sanitizer->sanitizeAddressLine((string) $this->input('street_address', '')),
            'city' => $sanitizer->sanitizeAddressLine((string) $this->input('city', ''), 120),
            'state' => $sanitizer->sanitizeAddressLine((string) $this->input('state', ''), 120),
            'postal_code' => $sanitizer->sanitizeAddressLine((string) $this->input('postal_code', ''), 32),
            'description' => $sanitizer->sanitizeDescription(
                $this->input('description') !== null ? (string) $this->input('description') : null
            ),
        ]);

        $ssn = $this->input('social_security_number');
        if ($ssn === null) {
            $this->merge(['social_security_number' => null]);

            return;
        }

        $digits = preg_replace('/\D/', '', trim((string) $ssn));
        $this->merge(['social_security_number' => $digits !== '' ? $digits : null]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'social_security_number' => ['nullable', 'string', 'regex:/^\d{9}$/'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'street_address' => ['required', 'string', 'max:255', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'city' => ['required', 'string', 'max:120', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'state' => ['required', 'string', 'max:120', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'postal_code' => ['required', 'string', 'max:32', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'description' => ['nullable', 'string', 'max:5000', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
        ];
    }
}
