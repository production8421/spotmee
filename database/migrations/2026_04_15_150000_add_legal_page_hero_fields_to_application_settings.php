<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('waiver_liability_host_hero_title', 200)->nullable();
            $table->string('waiver_liability_host_hero_background_color', 32)->nullable();
            $table->string('waiver_liability_user_hero_title', 200)->nullable();
            $table->string('waiver_liability_user_hero_background_color', 32)->nullable();
            $table->string('cancellation_policy_hero_title', 200)->nullable();
            $table->string('cancellation_policy_hero_background_color', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'waiver_liability_host_hero_title',
                'waiver_liability_host_hero_background_color',
                'waiver_liability_user_hero_title',
                'waiver_liability_user_hero_background_color',
                'cancellation_policy_hero_title',
                'cancellation_policy_hero_background_color',
            ]);
        });
    }
};
