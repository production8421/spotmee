<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('gym_bookings', 'pt_trainer_levels_per_slot')) {
                $table->json('pt_trainer_levels_per_slot')->nullable()->after('pt_trainer_level_keys');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('gym_bookings', 'pt_trainer_levels_per_slot')) {
                $table->dropColumn('pt_trainer_levels_per_slot');
            }
        });
    }
};
