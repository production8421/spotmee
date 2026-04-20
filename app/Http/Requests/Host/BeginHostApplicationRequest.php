<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;

class BeginHostApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'terms_accepted' => ['accepted'],
        ];
    }
}
