<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGymListingPtPricingTierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $raw = $this->input('pt_pricing_tier');
        $this->merge([
            'pt_pricing_tier' => $raw === '' || $raw === null ? null : $raw,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'pt_pricing_tier' => ['nullable', 'string', Rule::in(['silver', 'gold', 'platinum'])],
        ];
    }
}
