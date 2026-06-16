<?php

namespace App\Http\Requests\Admin;

use App\Models\GymBooking;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingPayoutSplitRequest extends FormRequest
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
            'host_payout_split_enabled' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'host_payout_split_enabled' => $this->boolean('host_payout_split_enabled'),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $booking = $this->route('booking');
            if ($booking instanceof GymBooking && ! $booking->canChangeHostPayoutSplitToggle()) {
                $validator->errors()->add('host_payout_split_enabled', __('This booking payout can no longer be changed.'));
            }
        });
    }
}
