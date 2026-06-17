<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'profile_photo' => ['nullable', 'image', 'max:5120'],
            'remove_profile_photo' => ['nullable', 'boolean'],
        ];
    }
}
