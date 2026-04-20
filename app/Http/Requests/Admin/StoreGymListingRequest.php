<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesGymListingAvailabilitySchedule;
use App\Http\Requests\Admin\Concerns\ValidatesPersonalTrainingSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreGymListingRequest extends FormRequest
{
    use ValidatesGymListingAvailabilitySchedule;
    use ValidatesPersonalTrainingSection;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeAvailabilitySchedule();

        $equipment = collect($this->input('equipment', []))
            ->filter(fn (mixed $row) => is_array($row) && filled($row['name'] ?? null))
            ->values()
            ->all();

        $this->merge([
            'email' => $this->email === '' || $this->email === null ? null : $this->email,
            'equipment' => $equipment,
            'service_options' => $this->input('service_options', []),
            'amenities' => $this->input('amenities', []),
            'person_limit' => $this->person_limit === '' || $this->person_limit === null
                ? null
                : $this->person_limit,
            'check_in_method' => $this->check_in_method === '' || $this->check_in_method === null
                ? null
                : $this->check_in_method,
        ]);

        $this->normalizePersonalTrainingSection();
    }

    public function withValidator(Validator $validator): void
    {
        $this->appendAvailabilityValidation($validator);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $states = array_keys(config('gym_listing.states', []));
        $facilityTypes = array_keys(config('gym_listing.facility_types', []));
        $areaSizes = array_keys(config('gym_listing.area_sizes', []));
        $pets = array_keys(config('gym_listing.pets_policies', []));
        $checkIn = array_keys(config('gym_listing.check_in_methods', []));
        $services = array_keys(config('gym_listing.service_options', []));
        $amenityKeys = array_keys(config('gym_listing.amenities', []));

        $rules = array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', Rule::in($states)],
            'postal_code' => ['required', 'string', 'max:32'],
            'facility_type' => ['required', 'string', Rule::in($facilityTypes)],
            'area_size' => ['required', 'string', Rule::in($areaSizes)],
            'service_options' => ['required', 'array', 'min:1'],
            'service_options.*' => ['string', Rule::in($services)],
            'pets_policy' => ['required', 'string', Rule::in($pets)],
            'check_in_method' => ['nullable', 'string', Rule::in($checkIn)],
            'person_limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'description' => ['required', 'string', 'min:10'],
            'equipment' => ['required', 'array', 'min:1'],
            'equipment.*.name' => ['required', 'string', 'max:255'],
            'equipment.*.quantity' => ['required', 'integer', 'min:1', 'max:999999'],
            'amenities' => ['required', 'array', 'min:1'],
            'amenities.*' => ['string', Rule::in($amenityKeys)],
            'main_image' => ['required', 'image', 'max:10240'],
            'gallery' => ['nullable', 'array', 'max:15'],
            'gallery.*' => ['image', 'max:10240'],
            'intro_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:102400'],
            'is_published' => ['sometimes', 'boolean'],
            'personal_training_available' => ['required', 'boolean'],
            'personal_training_cert' => $this->personalTrainingCertRules(),
            'personal_training_cpr_cert' => ['nullable', 'image', 'max:10240'],
        ], $this->availabilityScheduleRules());

        if ($this->boolean('personal_training_available')) {
            $rules = array_merge($rules, $this->personalTrainingAvailabilityRules());
        }

        return $rules;
    }
}
