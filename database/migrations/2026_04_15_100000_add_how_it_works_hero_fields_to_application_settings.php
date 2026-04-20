<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('how_it_works_hero_title', 200)->nullable();
            $table->string('how_it_works_hero_background_color', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'how_it_works_hero_title',
                'how_it_works_hero_background_color',
            ]);
        });
    }
};
