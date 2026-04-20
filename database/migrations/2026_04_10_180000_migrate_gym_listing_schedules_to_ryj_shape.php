<?php

use App\Models\GymListing;
use App\Support\RyjGymSchedule;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (GymListing::query()->orderBy('id')->cursor() as $listing) {
            $dirty = false;
            $sched = $listing->availability_schedule;
            if (is_array($sched) && $sched !== []) {
                $next = RyjGymSchedule::migrateLegacyAvailability($sched);
                if ($next !== $sched) {
                    $listing->availability_schedule = $next;
                    $dirty = true;
                }
            }
            $pt = $listing->personal_training_availability;
            if (is_array($pt) && $pt !== []) {
                $nextPt = RyjGymSchedule::migrateLegacyPt($pt);
                if ($nextPt !== $pt) {
                    $listing->personal_training_availability = $nextPt;
                    $dirty = true;
                }
            }
            if ($dirty) {
                $listing->saveQuietly();
            }
        }
    }

    public function down(): void
    {
        //
    }
};
