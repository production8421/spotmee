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
            $this->merge([
                'personal_training_availability' => null,
                'pt_trainer_levels' => null,
            ]);

            return;
        }

        $this->merge([
            'pt_trainer_levels' => $this->normalizePtTrainerLevelsInput(),
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
     * @return array<string, array<int, mixed>>
     */
    protected function ptTrainerLevelsRules(): array
    {
        $allowed = array_keys(config('gym_listing.pt_trainer_levels', []));

        return [
            'pt_trainer_levels' => ['required', 'array', 'min:1'],
            'pt_trainer_levels.*' => ['required', 'string', Rule::in($allowed)],
        ];
    }

    /**
     * @return list<string>
     */
    protected function normalizePtTrainerLevelsInput(): array
    {
        $raw = $this->input('pt_trainer_levels', []);
        if (! is_array($raw)) {
            return [];
        }

        $allowed = array_keys(config('gym_listing.pt_trainer_levels', []));

        return array_values(array_unique(array_filter(
            array_map('strval', $raw),
            fn (string $key): bool => in_array($key, $allowed, true)
        )));
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
