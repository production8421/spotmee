<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Support\RyjGymSchedule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesGymListingAvailabilitySchedule
{
    protected function normalizeAvailabilitySchedule(): void
    {
        $this->merge([
            'availability_schedule' => RyjGymSchedule::normalizeGymFormToStorage(
                $this->input('gym_availability', [])
            ),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function availabilityScheduleRules(): array
    {
        $rules = [];
        foreach (config('gym_listing.weekday_keys', []) as $day) {
            $rules["availability_schedule.{$day}.isClosed"] = ['required', 'boolean'];
            $rules["availability_schedule.{$day}.startTime"] = ['nullable', 'date_format:H:i'];
            $rules["availability_schedule.{$day}.endTime"] = ['nullable', 'date_format:H:i'];
            $rules["availability_schedule.{$day}.slotDuration"] = ['required', 'array', 'min:1', 'max:2'];
            $rules["availability_schedule.{$day}.slotDuration.*"] = ['string', Rule::in(['40', '60'])];
            $rules["availability_schedule.{$day}.personLimit"] = ['sometimes', 'integer', 'min:1', 'max:999'];
        }

        return $rules;
    }

    protected function appendAvailabilityValidation(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            foreach (config('gym_listing.weekday_keys', []) as $day) {
                $row = $this->input("availability_schedule.{$day}");
                if (! is_array($row)) {
                    continue;
                }

                $closed = filter_var($row['isClosed'] ?? false, FILTER_VALIDATE_BOOLEAN);
                if ($closed) {
                    continue;
                }

                $start = $row['startTime'] ?? null;
                $end = $row['endTime'] ?? null;
                if ($start === null || $start === '' || $end === null || $end === '') {
                    $v->errors()->add(
                        "gym_availability.{$day}.start_time",
                        __('Start and end times are required when the day is open.')
                    );

                    continue;
                }

                $tStart = strtotime($start);
                $tEnd = strtotime($end);
                if ($tStart !== false && $tEnd !== false && $tStart >= $tEnd) {
                    $v->errors()->add(
                        "gym_availability.{$day}.end_time",
                        __('End time must be after start time.')
                    );
                }
            }
        });
    }
}
