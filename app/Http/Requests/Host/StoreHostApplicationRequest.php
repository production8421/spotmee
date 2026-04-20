<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $ssn = $this->input('social_security_number');
        if ($ssn === null) {
            $this->merge(['social_security_number' => null]);

            return;
        }

        $trimmed = trim((string) $ssn);
        if ($trimmed === '') {
            $this->merge(['social_security_number' => null]);

            return;
        }

        $digits = preg_replace('/\D/', '', $trimmed);
        $this->merge(['social_security_number' => $digits !== '' ? $digits : null]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'social_security_number' => ['nullable', 'string', 'regex:/^\d{9}$/'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
