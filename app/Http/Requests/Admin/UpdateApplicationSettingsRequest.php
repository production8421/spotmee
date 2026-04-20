<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        $imageMediaRule = Rule::exists('media_assets', 'id')->where(function ($query): void {
            $query->where('mime_type', 'like', 'image/%')
                ->where('mime_type', 'not like', 'image/svg%');
        });

        return [
            'remove_header_logo' => ['nullable', 'boolean'],
            'remove_footer_logo' => ['nullable', 'boolean'],
            'header_logo_media_id' => ['nullable', 'integer', $imageMediaRule],
            'footer_logo_media_id' => ['nullable', 'integer', $imageMediaRule],
            'home_hero_heading' => ['nullable', 'string', 'max:255'],
            'home_hero_background_type' => ['nullable', Rule::in(['color', 'image'])],
            'home_hero_background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'home_hero_button1_label' => ['nullable', 'string', 'max:60'],
            'home_hero_button1_url' => ['nullable', 'url', 'max:2048'],
            'home_hero_button2_label' => ['nullable', 'string', 'max:60'],
            'home_hero_button2_url' => ['nullable', 'url', 'max:2048'],
            'remove_home_hero_background' => ['nullable', 'boolean'],
            'home_hero_background_media_id' => ['nullable', 'integer', $imageMediaRule],
        ];
    }
}
