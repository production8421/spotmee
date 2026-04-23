<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_listing_id')->constrained('gym_listings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // One review per subscriber per gym.
            $table->unique(['gym_listing_id', 'user_id']);
            $table->index(['gym_listing_id', 'approved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_reviews');
    }
};
