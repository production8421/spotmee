<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        $imageMediaRule = Rule::exists('media_assets', 'id')->where(function ($query): void {
            $query->where('mime_type', 'like', 'image/%')
                ->where('mime_type', 'not like', 'image/svg%');
        });

        $notificationRules = [
            'notification_email' => ['nullable', 'array'],
        ];
        foreach (SiteEmailTemplateService::allTemplateKeys() as $key) {
            $notificationRules["notification_email.$key"] = ['nullable', 'array'];
            $notificationRules["notification_email.$key.subject"] = ['nullable', 'string', 'max:255'];
            $notificationRules["notification_email.$key.body_html"] = ['nullable', 'string', 'max:65000'];
        }

        $smtpOn = fn (): bool => $this->boolean('smtp_enabled');

        return array_merge($notificationRules, [
            'smtp_enabled' => ['nullable', 'boolean'],
            'smtp_host' => [Rule::requiredIf($smtpOn), 'nullable', 'string', 'max:255'],
            'smtp_port' => [Rule::requiredIf($smtpOn), 'nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_encryption' => [Rule::requiredIf($smtpOn), 'nullable', Rule::in(['tls', 'ssl', 'none'])],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:500'],
            'smtp_from_address' => [Rule::requiredIf($smtpOn), 'nullable', 'email', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
            'host_registration_auto_approve' => ['nullable', 'boolean'],
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
        ]);
    }
}
