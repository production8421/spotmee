<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
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

        $smtpPasswordRequiredIf = function (): bool {
            if (! $this->boolean('smtp_enabled')) {
                return false;
            }
            if ($this->filled('smtp_password')) {
                return false;
            }
            if (! Schema::hasTable('application_settings')) {
                return true;
            }
            $row = ApplicationSetting::query()->first();
            $existing = $row?->smtp_password;

            return ! is_string($existing) || $existing === '';
        };

        return array_merge($notificationRules, [
            'smtp_enabled' => ['nullable', 'boolean'],
            'smtp_host' => [Rule::requiredIf($smtpOn), 'nullable', 'string', 'max:255'],
            'smtp_port' => [Rule::requiredIf($smtpOn), 'nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_encryption' => [Rule::requiredIf($smtpOn), 'nullable', Rule::in(['tls', 'ssl', 'none'])],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => [Rule::requiredIf($smtpPasswordRequiredIf), 'nullable', 'string', 'max:500'],
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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'smtp_password.required' => __('Enter the SMTP password. For Gmail with 2-step verification, create an App Password in your Google account and paste it here.'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->boolean('smtp_enabled')) {
                return;
            }
            $host = strtolower(trim((string) $this->input('smtp_host', '')));
            if (! str_contains($host, 'gmail.com')) {
                return;
            }
            $enc = strtolower(trim((string) $this->input('smtp_encryption', '')));
            $port = (int) $this->input('smtp_port', 0);
            if ($enc === 'ssl' && $port !== 465) {
                $validator->errors()->add(
                    'smtp_port',
                    __('Gmail: with SSL encryption use port 465 (not :port).', ['port' => $port])
                );
            }
            if ($enc === 'tls' && $port !== 587) {
                $validator->errors()->add(
                    'smtp_port',
                    __('Gmail: with TLS encryption use port 587 (not :port).', ['port' => $port])
                );
            }
        });
    }
}
