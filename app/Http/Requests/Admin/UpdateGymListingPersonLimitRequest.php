<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymListingPersonLimitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $v = $this->input('person_limit');
        if ($v === '' || $v === null) {
            $this->merge(['person_limit' => null]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'person_limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
