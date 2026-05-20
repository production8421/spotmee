<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            if (! Schema::hasColumn('gym_listings', 'pt_trainer_levels')) {
                $table->json('pt_trainer_levels')->nullable()->after('personal_training_availability');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            if (Schema::hasColumn('gym_listings', 'pt_trainer_levels')) {
                $table->dropColumn('pt_trainer_levels');
            }
        });
    }
};
