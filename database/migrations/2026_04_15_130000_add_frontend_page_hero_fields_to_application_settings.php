<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('find_a_gym_hero_title', 200)->nullable();
            $table->string('find_a_gym_hero_background_color', 32)->nullable();
            $table->string('become_a_host_hero_title', 200)->nullable();
            $table->string('become_a_host_hero_background_color', 32)->nullable();
            $table->string('faq_hero_title', 200)->nullable();
            $table->string('faq_hero_background_color', 32)->nullable();
            $table->string('contact_hero_title', 200)->nullable();
            $table->string('contact_hero_background_color', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'find_a_gym_hero_title',
                'find_a_gym_hero_background_color',
                'become_a_host_hero_title',
                'become_a_host_hero_background_color',
                'faq_hero_title',
                'faq_hero_background_color',
                'contact_hero_title',
                'contact_hero_background_color',
            ]);
        });
    }
};
