<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            $table->string('facility_type', 64)->nullable()->after('website');
            $table->string('area_size', 64)->nullable()->after('facility_type');
            $table->json('service_options')->nullable()->after('area_size');
            $table->string('pets_policy', 64)->nullable()->after('service_options');
            $table->string('check_in_method', 64)->nullable()->after('pets_policy');
            $table->json('equipment')->nullable()->after('check_in_method');
            $table->json('amenities')->nullable()->after('equipment');
            $table->string('main_image_path')->nullable()->after('amenities');
            $table->json('gallery_paths')->nullable()->after('main_image_path');
            $table->string('intro_video_path')->nullable()->after('gallery_paths');
        });
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table) {
            $table->dropColumn([
                'facility_type',
                'area_size',
                'service_options',
                'pets_policy',
                'check_in_method',
                'equipment',
                'amenities',
                'main_image_path',
                'gallery_paths',
                'intro_video_path',
            ]);
        });
    }
};
