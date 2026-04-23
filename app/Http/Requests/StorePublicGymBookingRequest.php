<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePublicGymBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $email = trim((string) $this->input('guest_email', ''));
        if ($email === '') {
            $user = Auth::user();
            if ($user instanceof User && $user->hasRole(UserRole::Subscriber->value)) {
                $this->merge(['guest_email' => $user->email]);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'slot_duration_minutes' => ['required', 'integer', 'in:40,60'],
            'time_slots' => ['required', 'array', 'min:1'],
            'time_slots.*' => ['required', 'string', 'max:32'],
            'number_of_persons' => ['required', 'integer', 'min:1', 'max:100'],
            'trainer_per_slot' => ['nullable', 'array'],
            'trainer_per_slot.*' => ['boolean'],
            'pt_addon' => ['nullable', 'string', 'in:none,paid,free_trial'],
            'pt_free_trial_slot' => ['nullable', 'string', 'max:32'],
            'coupon_code' => ['nullable', 'string', 'max:64'],
            'accept_terms' => ['required', 'accepted'],
        ];
    }
}
