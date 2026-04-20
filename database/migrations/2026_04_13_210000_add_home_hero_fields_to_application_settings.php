<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('home_hero_heading', 200)->nullable();
            $table->string('home_hero_background_type', 16)->default('color');
            $table->string('home_hero_background_color', 32)->nullable();
            $table->string('home_hero_background_path', 512)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'home_hero_heading',
                'home_hero_background_type',
                'home_hero_background_color',
                'home_hero_background_path',
            ]);
        });
    }
};
