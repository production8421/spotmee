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
        $percentOn = $this->has('percent_discount_enabled') && filter_var($this->input('percent_discount_enabled'), FILTER_VALIDATE_BOOLEAN);
        $this->merge([
            'is_active' => $this->has('is_active') && filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
            'percent_discount_enabled' => $percentOn,
            'valid_sessions' => max(1, min(100000, (int) $this->input('valid_sessions', 1))),
        ]);
        if ($percentOn) {
            $pd = $this->input('percent_discount');
            $this->merge([
                'percent_discount' => is_numeric($pd) ? round((float) $pd, 2) : null,
            ]);
        } else {
            $this->merge([
                'percent_discount' => null,
                'percent_discount_enabled' => false,
            ]);
        }

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
            'percent_discount_enabled' => ['boolean'],
            'percent_discount' => [
                'nullable',
                'numeric',
                'min:0.01',
                'max:100',
                Rule::requiredIf(fn () => $this->boolean('percent_discount_enabled')),
            ],
            'valid_sessions' => [
                'nullable',
                'integer',
                'min:1',
                'max:100000',
                Rule::requiredIf(fn () => ! $this->boolean('percent_discount_enabled')),
            ],
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
