<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\GymListing;
use App\Support\RyjGymSchedule;
use Illuminate\Validation\Rule;

trait ValidatesPersonalTrainingSection
{
    protected function normalizePersonalTrainingSection(): void
    {
        $available = (bool) (int) $this->input('personal_training_available', 0);
        $this->merge(['personal_training_available' => $available]);

        if (! $available) {
            $this->merge(['personal_training_availability' => null]);

            return;
        }

        $this->merge([
            'personal_training_availability' => RyjGymSchedule::normalizePtFormToStorage(
                $this->input('pt_schedule', []),
                $this->input('pt_time_slots', []),
                $this->input('availability_schedule', [])
            ),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function personalTrainingAvailabilityRules(): array
    {
        $rules = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $rules["personal_training_availability.{$day}.isClosed"] = ['required', 'boolean'];
            $rules["personal_training_availability.{$day}.startTime"] = ['nullable', 'date_format:H:i'];
            $rules["personal_training_availability.{$day}.endTime"] = ['nullable', 'date_format:H:i'];
            $rules["personal_training_availability.{$day}.slotDuration"] = ['required', 'array', 'size:1'];
            $rules["personal_training_availability.{$day}.slotDuration.0"] = ['required', 'string', Rule::in(['60'])];
            $rules["personal_training_availability.{$day}.timeSlots"] = ['nullable', 'array'];
            $rules["personal_training_availability.{$day}.timeSlots.*"] = [
                'string',
                'regex:/^\d{2}:\d{2}\|\d{2}:\d{2}$/',
            ];
        }

        return $rules;
    }

    /**
     * @return array<int, mixed>
     */
    protected function personalTrainingCertRules(): array
    {
        return [
            'nullable',
            'image',
            'max:10240',
            Rule::requiredIf(function (): bool {
                if (! $this->boolean('personal_training_available')) {
                    return false;
                }

                if ($this->boolean('remove_personal_training_cert')) {
                    return true;
                }

                if ($this->route('gym_listing') instanceof GymListing) {
                    return ! $this->route('gym_listing')->personal_training_cert_path;
                }

                return true;
            }),
        ];
    }
}
