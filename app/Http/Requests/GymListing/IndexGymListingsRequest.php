<?php

namespace App\Http\Requests\GymListing;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexGymListingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        foreach (['q', 'city', 'workflow', 'published', 'facility_type', 'state', 'user_id'] as $key) {
            if ($this->has($key) && $this->input($key) === '') {
                $merge[$key] = null;
            }
        }

        if (! $this->user()?->hasRole(UserRole::Administrator->value)) {
            $merge['user_id'] = null;
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        $facilityKeys = array_keys(config('gym_listing.facility_types', []));
        $stateKeys = array_keys(config('gym_listing.states', []));

        return [
            'q' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:100'],
            'workflow' => ['nullable', 'string', Rule::in(['pending_approval', 'declined', 'approved', 'no_host'])],
            'published' => ['nullable', 'string', Rule::in(['1', '0'])],
            'facility_type' => ['nullable', 'string', Rule::in($facilityKeys)],
            'state' => ['nullable', 'string', 'size:2', Rule::in($stateKeys)],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
