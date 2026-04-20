<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateFooterRequest extends FormRequest
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
        $rules = [];
        foreach (ApplicationSetting::footerSocialPlatformKeys() as $key) {
            $rules["footer_social_urls.{$key}"] = ['nullable', 'string', 'max:2048'];
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $urls = $this->input('footer_social_urls', []);
            if (! is_array($urls)) {
                return;
            }
            foreach (ApplicationSetting::footerSocialPlatformKeys() as $key) {
                $raw = isset($urls[$key]) ? trim((string) $urls[$key]) : '';
                if ($raw === '') {
                    continue;
                }
                if (ApplicationSetting::normalizeHeroPublicUrl($raw) === null) {
                    $validator->errors()->add(
                        "footer_social_urls.{$key}",
                        __('Use a full http(s) URL (for example https://instagram.com/yourpage).'),
                    );
                }
            }
        });
    }
}
