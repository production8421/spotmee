<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\MediaAsset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateFrontendHomeHeroRequest extends FormRequest
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

        $rules = [
            'home_hero_heading' => ['nullable', 'string', 'max:200'],
            'home_hero_background_type' => ['required', 'string', Rule::in(['color', 'image', 'video'])],
            'home_hero_background_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'home_hero_background_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'home_hero_button1_label' => ['nullable', 'string', 'max:120'],
            'home_hero_button1_url' => ['nullable', 'string', 'max:2048'],
            'home_hero_button2_label' => ['nullable', 'string', 'max:120'],
            'home_hero_button2_url' => ['nullable', 'string', 'max:2048'],
            'how_heading' => ['nullable', 'string', 'max:200'],
            'how_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'why_heading' => ['nullable', 'string', 'max:200'],
            'why_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'why_description' => ['nullable', 'string', 'max:600'],
            'why_cta_label' => ['nullable', 'string', 'max:120'],
            'why_cta_url' => ['nullable', 'string', 'max:2048'],
            'earn_heading' => ['nullable', 'string', 'max:200'],
            'earn_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'earn_description' => ['nullable', 'string', 'max:2500'],
            'earn_footnote' => ['nullable', 'string', 'max:500'],
            'earn_cta_label' => ['nullable', 'string', 'max:120'],
            'earn_cta_url' => ['nullable', 'string', 'max:2048'],
            'earn_media_id' => ['nullable', 'integer', $imageMediaRule],
            'community_heading' => ['nullable', 'string', 'max:200'],
            'community_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'community_description' => ['nullable', 'string', 'max:600'],
            'community_cta_label' => ['nullable', 'string', 'max:120'],
            'community_cta_url' => ['nullable', 'string', 'max:2048'],
            'promo_banner_heading' => ['nullable', 'string', 'max:200'],
            'promo_banner_heading_emphasis' => ['nullable', 'string', 'max:80'],
            'promo_banner_cta_label' => ['nullable', 'string', 'max:120'],
            'promo_banner_cta_url' => ['nullable', 'string', 'max:2048'],
            'promo_banner_media_id' => ['nullable', 'integer', $imageMediaRule],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $rules["how_step_{$i}_badge"] = ['nullable', 'string', 'max:60'];
            $rules["how_step_{$i}_title"] = ['nullable', 'string', 'max:200'];
            $rules["how_step_{$i}_body"] = ['nullable', 'string', 'max:2500'];
            $rules["how_step_{$i}_media_id"] = ['nullable', 'integer', $imageMediaRule];
        }

        for ($i = 1; $i <= 4; $i++) {
            $rules["why_feature_{$i}_link_label"] = ['nullable', 'string', 'max:80'];
            $rules["why_feature_{$i}_link_url"] = ['nullable', 'string', 'max:2048'];
            $rules["why_feature_{$i}_text"] = ['nullable', 'string', 'max:300'];
            $rules["why_feature_{$i}_media_id"] = ['nullable', 'integer', $imageMediaRule];
        }

        for ($i = 1; $i <= 3; $i++) {
            $rules["earn_point_{$i}"] = ['nullable', 'string', 'max:200'];
        }

        for ($i = 1; $i <= 4; $i++) {
            $rules["community_card_{$i}_title"] = ['nullable', 'string', 'max:200'];
            $rules["community_card_{$i}_body"] = ['nullable', 'string', 'max:600'];
            $rules["community_card_{$i}_media_id"] = ['nullable', 'integer', $imageMediaRule];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $type = (string) $this->input('home_hero_background_type');

            if ($type === 'color') {
                $color = (string) $this->input('home_hero_background_color', '');
                if ($color === '' || ! preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                    $validator->errors()->add('home_hero_background_color', __('Enter a valid hex color (e.g. #7366FF).'));
                }

                return;
            }

            $settings = ApplicationSetting::instance();
            $hasMediaId = $this->filled('home_hero_background_media_id');
            $keepsExisting = filled($settings->home_hero_background_path)
                && (string) $settings->home_hero_background_type === $type;

            if (! $hasMediaId && ! $keepsExisting) {
                $validator->errors()->add(
                    'home_hero_background_media_id',
                    __('Choose an image or video from the media library.'),
                );

                return;
            }

            if (! $hasMediaId) {
                return;
            }

            $asset = MediaAsset::query()->find($this->integer('home_hero_background_media_id'));
            if ($asset === null) {
                return;
            }

            if ($type === 'image' && ! $asset->isImage()) {
                $validator->errors()->add('home_hero_background_media_id', __('Selected file must be an image.'));

                return;
            }

            if ($type === 'image' && str_contains((string) $asset->mime_type, 'svg')) {
                $validator->errors()->add('home_hero_background_media_id', __('SVG images cannot be used as a hero background.'));

                return;
            }

            if ($type === 'video' && ! $asset->isVideo()) {
                $validator->errors()->add('home_hero_background_media_id', __('Selected file must be a video.'));
            }
        });

        $validator->after(function ($validator): void {
            $this->validateHeroCtaPair(
                $validator,
                'home_hero_button1_label',
                'home_hero_button1_url',
            );
            $this->validateHeroCtaPair(
                $validator,
                'home_hero_button2_label',
                'home_hero_button2_url',
            );
            $this->validateHeroCtaPair(
                $validator,
                'why_cta_label',
                'why_cta_url',
            );
            $this->validateHeroCtaPair(
                $validator,
                'earn_cta_label',
                'earn_cta_url',
            );
            $this->validateHeroCtaPair(
                $validator,
                'community_cta_label',
                'community_cta_url',
            );
            $this->validateHeroCtaPair(
                $validator,
                'promo_banner_cta_label',
                'promo_banner_cta_url',
            );
            for ($i = 1; $i <= 4; $i++) {
                $this->validateOptionalLinkPair(
                    $validator,
                    "why_feature_{$i}_link_label",
                    "why_feature_{$i}_link_url",
                );
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

    private function validateOptionalLinkPair(Validator $validator, string $labelKey, string $urlKey): void
    {
        $label = trim((string) $this->input($labelKey, ''));
        $url = trim((string) $this->input($urlKey, ''));

        if ($url === '') {
            return;
        }

        if ($label === '') {
            $validator->errors()->add($labelKey, __('Enter text for this link, or clear the URL.'));

            return;
        }

        if (ApplicationSetting::normalizeHeroPublicUrl($url) === null) {
            $validator->errors()->add($urlKey, __('Use a full http(s) URL or a path starting with / (for example /login).'));
        }
    }
}
