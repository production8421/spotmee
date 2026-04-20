<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_host', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['coupon_id', 'user_id']);
        });

        Schema::create('coupon_gym_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('gym_listing_id')->constrained('gym_listings')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['coupon_id', 'gym_listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_gym_listing');
        Schema::dropIfExists('coupon_host');
    }
};
