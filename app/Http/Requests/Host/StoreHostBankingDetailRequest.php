<?php

namespace App\Http\Requests\Host;

use App\Models\HostApplication;
use App\Http\Requests\Host\Concerns\ValidatesHostBankingInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreHostBankingDetailRequest extends FormRequest
{
    use ValidatesHostBankingInput;

    public function authorize(): bool
    {
        if (! $this->session()->has('host_application_id')) {
            return false;
        }

        $application = HostApplication::query()->find($this->session()->get('host_application_id'));
        if ($application === null || ! $application->isPending()) {
            return false;
        }

        if ($application->user_id !== null && Auth::id() !== $application->user_id) {
            return false;
        }

        return true;
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
