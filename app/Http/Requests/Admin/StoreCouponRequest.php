<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge([
                'code' => Coupon::normalizeCode((string) $this->input('code', '')),
            ]);
        }
        if ($this->input('max_redemptions') === '' || $this->input('max_redemptions') === null) {
            $this->merge(['max_redemptions' => null]);
        }
        foreach (['starts_at', 'ends_at'] as $key) {
            if ($this->input($key) === '') {
                $this->merge([$key => null]);
            }
        }
        $this->merge([
            'is_active' => $this->has('is_active') && filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $hostIds = $this->input('host_ids');
        if ($hostIds === null || $hostIds === '') {
            $hostIds = [];
        } elseif (! is_array($hostIds)) {
            $hostIds = [(int) $hostIds];
        }
        $gymIds = $this->input('gym_listing_ids');
        if ($gymIds === null || $gymIds === '') {
            $gymIds = [];
        } elseif (! is_array($gymIds)) {
            $gymIds = [(int) $gymIds];
        }
        $this->merge([
            'host_ids' => array_values(array_unique(array_filter(array_map('intval', $hostIds)))),
            'gym_listing_ids' => array_values(array_unique(array_filter(array_map('intval', $gymIds)))),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:64', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('coupons', 'code')],
            'description' => ['nullable', 'string', 'max:500'],
            'discount_type' => ['required', 'string', Rule::in([Coupon::TYPE_PERCENT, Coupon::TYPE_FIXED])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'applies_to' => [
                'required',
                'string',
                Rule::in([Coupon::APPLIES_FULL_BOOKING, Coupon::APPLIES_PERSONAL_TRAINING]),
            ],
            'max_redemptions' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
            'host_ids' => ['nullable', 'array'],
            'host_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            'gym_listing_ids' => ['nullable', 'array'],
            'gym_listing_ids.*' => ['integer', 'distinct', 'exists:gym_listings,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if ($v->errors()->isNotEmpty()) {
                return;
            }
            $type = $this->input('discount_type');
            $val = (float) $this->input('discount_value');
            if ($type === Coupon::TYPE_PERCENT && $val > 100) {
                $v->errors()->add('discount_value', __('Percent discount cannot exceed 100.'));
            }

            foreach ($this->input('host_ids', []) as $hostId) {
                $user = User::query()->find((int) $hostId);
                if (! $user || ! $user->hasRole(UserRole::Host->value)) {
                    $v->errors()->add('host_ids', __('Each selected assignee must be a user with the Host role.'));

                    return;
                }
            }
        });
    }
}
