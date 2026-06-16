<?php

namespace App\Http\Requests\Host;

use App\Enums\UserRole;
use App\Http\Requests\Host\Concerns\ValidatesHostBankingInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateHostBankingDetailRequest extends FormRequest
{
    use ValidatesHostBankingInput;

    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Host->value) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareHostBankingForValidation();
    }

    public function withValidator(Validator $validator): void
    {
        $this->registerHostBankingValidator($validator);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->hostBankingRules();
    }
}
