<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            $table->boolean('personal_training_available')->default(false)->after('availability_schedule');
            $table->string('personal_training_cert_path')->nullable()->after('personal_training_available');
            $table->string('personal_training_cpr_cert_path')->nullable()->after('personal_training_cert_path');
            $table->json('personal_training_availability')->nullable()->after('personal_training_cpr_cert_path');
        });
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            $table->dropColumn([
                'personal_training_available',
                'personal_training_cert_path',
                'personal_training_cpr_cert_path',
                'personal_training_availability',
            ]);
        });
    }
};
