<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostPayoutSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Administrator') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'share_host_booking_earnings' => ['required', 'boolean'],
            'host_payout_delay_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'platform_commission_pct' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'share_host_booking_earnings' => $this->boolean('share_host_booking_earnings'),
            'host_payout_delay_hours' => (int) $this->input('host_payout_delay_hours', 12),
            'platform_commission_pct' => round((float) $this->input('platform_commission_pct', 20), 2),
        ]);
    }
}
