<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('home_hero_button1_label', 120)->nullable();
            $table->string('home_hero_button1_url', 2048)->nullable();
            $table->string('home_hero_button2_label', 120)->nullable();
            $table->string('home_hero_button2_url', 2048)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'home_hero_button1_label',
                'home_hero_button1_url',
                'home_hero_button2_label',
                'home_hero_button2_url',
            ]);
        });
    }
};
