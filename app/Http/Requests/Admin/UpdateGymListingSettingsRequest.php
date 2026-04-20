<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGymListingSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $emptyToNull = fn (mixed $v): ?string => is_string($v) && trim($v) === '' ? null : (is_string($v) ? $v : null);

        $this->merge([
            'stripe_test_publishable_key' => $emptyToNull($this->input('stripe_test_publishable_key')),
            'stripe_live_publishable_key' => $emptyToNull($this->input('stripe_live_publishable_key')),
        ]);

        foreach ([
            'silver_tier_price_1_hour',
            'silver_tier_price_40_min',
            'silver_tier_admin_commission_1_hour_pct',
            'silver_tier_admin_commission_40_min_pct',
            'gold_tier_price_1_hour',
            'gold_tier_price_40_min',
            'gold_tier_admin_commission_1_hour_pct',
            'gold_tier_admin_commission_40_min_pct',
            'platinum_tier_price_1_hour',
            'platinum_tier_price_40_min',
            'platinum_tier_admin_commission_1_hour_pct',
            'platinum_tier_admin_commission_40_min_pct',
            'pt_silver_price_per_slot',
            'pt_silver_admin_commission_pct',
            'pt_gold_price_per_slot',
            'pt_gold_admin_commission_pct',
            'pt_platinum_price_per_slot',
            'pt_platinum_admin_commission_pct',
            'legal_booking_terms_url',
            'legal_booking_privacy_url',
            'legal_host_terms_url',
            'legal_host_privacy_url',
            'booking_cancel_result_url',
            'legal_host_registration_url',
            'webhook_booking_completed_url',
            'webhook_booking_completed_secret',
            'webhook_booking_cancelled_url',
            'webhook_booking_cancelled_secret',
        ] as $key) {
            $v = $this->input($key);
            if ($v === '' || $v === null) {
                $this->merge([$key => null]);
            }
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'stripe_mode' => ['required', 'string', Rule::in(['test', 'live'])],
            'stripe_test_publishable_key' => ['nullable', 'string', 'max:255'],
            'stripe_test_secret_key' => ['nullable', 'string', 'max:255'],
            'stripe_live_publishable_key' => ['nullable', 'string', 'max:255'],
            'stripe_live_secret_key' => ['nullable', 'string', 'max:255'],
            'silver_tier_price_1_hour' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'silver_tier_price_40_min' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'silver_tier_admin_commission_1_hour_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'silver_tier_admin_commission_40_min_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'gold_tier_price_1_hour' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'gold_tier_price_40_min' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'gold_tier_admin_commission_1_hour_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'gold_tier_admin_commission_40_min_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'platinum_tier_price_1_hour' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'platinum_tier_price_40_min' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'platinum_tier_admin_commission_1_hour_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'platinum_tier_admin_commission_40_min_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pt_silver_price_per_slot' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'pt_silver_admin_commission_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pt_gold_price_per_slot' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'pt_gold_admin_commission_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pt_platinum_price_per_slot' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'pt_platinum_admin_commission_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'legal_booking_terms_url' => ['nullable', 'string', 'url', 'max:2048'],
            'legal_booking_privacy_url' => ['nullable', 'string', 'url', 'max:2048'],
            'legal_host_terms_url' => ['nullable', 'string', 'url', 'max:2048'],
            'legal_host_privacy_url' => ['nullable', 'string', 'url', 'max:2048'],
            'booking_cancel_result_url' => ['nullable', 'string', 'url', 'max:2048'],
            'legal_host_registration_url' => ['nullable', 'string', 'url', 'max:2048'],
            'webhook_booking_completed_url' => ['nullable', 'string', 'url', 'max:2048'],
            'webhook_booking_completed_secret' => ['nullable', 'string', 'max:500'],
            'webhook_booking_cancelled_url' => ['nullable', 'string', 'url', 'max:2048'],
            'webhook_booking_cancelled_secret' => ['nullable', 'string', 'max:500'],
        ];
    }
}
