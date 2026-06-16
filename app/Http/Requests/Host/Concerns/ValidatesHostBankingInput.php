<?php

namespace App\Http\Requests\Host\Concerns;

use App\Services\Host\HostBankingInputSanitizer;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesHostBankingInput
{
    protected function prepareHostBankingForValidation(): void
    {
        $sanitizer = app(HostBankingInputSanitizer::class);

        $this->merge([
            'account_holder_name' => $sanitizer->sanitizeAccountHolderName((string) $this->input('account_holder_name', '')),
            'bank_name' => $sanitizer->sanitizeBankName((string) $this->input('bank_name', '')),
            'routing_number' => $sanitizer->digitsOnly((string) $this->input('routing_number', '')),
            'account_number' => $sanitizer->digitsOnly((string) $this->input('account_number', '')),
            'account_number_confirmation' => $sanitizer->digitsOnly((string) $this->input('account_number_confirmation', '')),
            'notes' => $sanitizer->sanitizeNotes($this->input('notes') !== null ? (string) $this->input('notes') : null),
            'bank_country' => 'US',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function hostBankingRules(): array
    {
        return [
            'account_holder_name' => ['required', 'string', 'max:200', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'bank_name' => ['required', 'string', 'max:200', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'account_type' => ['required', 'string', Rule::in(['checking', 'savings'])],
            'routing_number' => ['required', 'string', 'regex:/^\d{9}$/'],
            'account_number' => ['required', 'string', 'regex:/^\d{4,17}$/'],
            'account_number_confirmation' => ['required', 'same:account_number'],
            'bank_country' => ['required', 'string', 'in:US'],
            'notes' => ['nullable', 'string', 'max:1000', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
        ];
    }

    protected function registerHostBankingValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $routingNumber = (string) $this->input('routing_number', '');
            if ($routingNumber === '' || $validator->errors()->has('routing_number')) {
                return;
            }

            if (! app(HostBankingInputSanitizer::class)->isValidUsRoutingNumber($routingNumber)) {
                $validator->errors()->add('routing_number', __('Enter a valid US routing number.'));
            }
        });
    }
}
