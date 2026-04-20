<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\MediaAsset;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHowItWorksPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $c = trim((string) $this->input('how_it_works_hero_background_color', ''));
        if ($c === '') {
            $this->merge(['how_it_works_hero_background_color' => null]);
        }
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
            'how_it_works_hero_title' => ['nullable', 'string', 'max:200'],
            'how_it_works_hero_background_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'intro_heading' => ['nullable', 'string', 'max:200'],
            'intro_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'intro_subtitle' => ['nullable', 'string', 'max:200'],
            'intro_description_1' => ['nullable', 'string', 'max:8000'],
            'intro_description_2' => ['nullable', 'string', 'max:8000'],
            'intro_button1_label' => ['nullable', 'string', 'max:120'],
            'intro_button1_url' => ['nullable', 'string', 'max:2048'],
            'intro_button2_label' => ['nullable', 'string', 'max:120'],
            'intro_button2_url' => ['nullable', 'string', 'max:2048'],
            'intro_media_id' => ['nullable', 'integer', $imageMediaRule],
            'intro_remove_image' => ['nullable', 'boolean'],
            'approach_title' => ['nullable', 'string', 'max:200'],
            'approach_title_emphasis' => ['nullable', 'string', 'max:80'],
            'approach_description' => ['nullable', 'string', 'max:8000'],
            'approach_media_id' => ['nullable', 'integer', $imageMediaRule],
            'approach_remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->validateHeroCtaPair($validator, 'intro_button1_label', 'intro_button1_url');
            $this->validateHeroCtaPair($validator, 'intro_button2_label', 'intro_button2_url');

            if ($this->filled('intro_media_id')) {
                $asset = MediaAsset::query()->find($this->integer('intro_media_id'));
                if ($asset !== null && str_contains((string) $asset->mime_type, 'svg')) {
                    $validator->errors()->add('intro_media_id', __('SVG images cannot be used here.'));
                }
            }

            if ($this->filled('approach_media_id')) {
                $approachAsset = MediaAsset::query()->find($this->integer('approach_media_id'));
                if ($approachAsset !== null && str_contains((string) $approachAsset->mime_type, 'svg')) {
                    $validator->errors()->add('approach_media_id', __('SVG images cannot be used here.'));
                }
            }
        });
    }

    private function validateHeroCtaPair(Validator $validator, string $labelKey, string $urlKey): void
    {
        $label = trim((string) $this->input($labelKey, ''));
        $url = trim((string) $this->input($urlKey, ''));

        if ($label === '' && $url === '') {
            return;
        }

        if ($label === '' && $url !== '') {
            $validator->errors()->add($labelKey, __('Enter a label for this button, or clear the URL.'));

            return;
        }

        if ($label !== '' && $url === '') {
            $validator->errors()->add($urlKey, __('Enter a URL for this button, or clear the label.'));

            return;
        }

        if (ApplicationSetting::normalizeHeroPublicUrl($url) === null) {
            $validator->errors()->add($urlKey, __('Use a full http(s) URL or a path starting with / (for example /login).'));
        }
    }
}
