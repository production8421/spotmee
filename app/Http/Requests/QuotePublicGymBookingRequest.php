<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Same booking fields as {@see StorePublicGymBookingRequest} except terms (not required for price preview).
 */
class QuotePublicGymBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $code = $this->input('coupon_code');
        if (is_string($code)) {
            $this->merge(['coupon_code' => trim($code)]);
        }

        if (filled($this->input('coupon_code'))) {
            $email = trim((string) $this->input('guest_email', ''));
            if ($email === '') {
                $user = Auth::user();
                if ($user instanceof User && $user->hasRole(UserRole::Subscriber->value)) {
                    $this->merge(['guest_email' => $user->email]);
                }
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guest_name' => ['nullable', 'string', 'max:255'],
            'guest_email' => [
                'nullable',
                'email',
                'max:255',
                Rule::requiredIf(fn () => filled($this->input('coupon_code'))),
            ],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'slot_duration_minutes' => ['required', 'integer', 'in:60'],
            'time_slots' => ['required', 'array', 'min:1'],
            'time_slots.*' => ['required', 'string', 'max:32'],
            'number_of_persons' => ['required', 'integer', 'min:1', 'max:100'],
            'trainer_per_slot' => ['nullable', 'array'],
            'trainer_per_slot.*' => ['boolean'],
            'pt_addon' => ['nullable', 'string', 'in:none,paid,free_trial'],
            'pt_free_trial_slot' => ['nullable', 'string', 'max:32'],
            'pt_trainer_levels_selected' => ['nullable', 'array'],
            'pt_trainer_levels_selected.*' => ['required', 'string', 'max:20'],
            'coupon_code' => ['nullable', 'string', 'max:64'],
        ];
    }
}
