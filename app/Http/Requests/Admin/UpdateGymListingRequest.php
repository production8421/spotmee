<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesGymListingAvailabilitySchedule;
use App\Http\Requests\Admin\Concerns\ValidatesPersonalTrainingSection;
use App\Models\GymListing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateGymListingRequest extends FormRequest
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
            'remove_gallery' => $this->input('remove_gallery', []),
            'remove_personal_training_cert' => $this->boolean('remove_personal_training_cert'),
            'remove_personal_training_cpr_cert' => $this->boolean('remove_personal_training_cpr_cert'),
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

        $validator->after(function (Validator $validator): void {
            /** @var GymListing|null $listing */
            $listing = $this->route('gym_listing');
            if (! $listing instanceof GymListing) {
                return;
            }

            $current = $listing->gallery_paths ?? [];
            $remove = $this->input('remove_gallery', []);
            if (! is_array($remove)) {
                return;
            }

            $invalid = array_diff($remove, $current);
            if ($invalid !== []) {
                $validator->errors()->add('remove_gallery', __('Invalid gallery selection.'));
            }

            $remaining = count(array_diff($current, $remove));
            $new = count($this->file('gallery') ?? []);
            if ($remaining + $new > 15) {
                $validator->errors()->add('gallery', __('You may have at most :max gallery images.', ['max' => 15]));
            }
        });
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

        /** @var GymListing $listing */
        $listing = $this->route('gym_listing');

        $mainImageRules = $listing->main_image_path
            ? ['nullable', 'image', 'max:10240']
            : ['required', 'image', 'max:10240'];

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
            'main_image' => $mainImageRules,
            'gallery' => ['nullable', 'array', 'max:15'],
            'gallery.*' => ['image', 'max:10240'],
            'intro_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:102400'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['string', 'max:500'],
            'remove_personal_training_cert' => ['sometimes', 'boolean'],
            'remove_personal_training_cpr_cert' => ['sometimes', 'boolean'],
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
