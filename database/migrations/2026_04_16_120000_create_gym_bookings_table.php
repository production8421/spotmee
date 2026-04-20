<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_listing_id')->constrained('gym_listings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration_hours', 6, 2)->nullable();

            $table->unsignedSmallInteger('number_of_persons')->default(1);

            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 50)->nullable();

            $table->text('notes')->nullable();

            $table->boolean('personal_trainer_requested')->default(false);
            $table->json('trainer_per_slot')->nullable();
            $table->unsignedSmallInteger('trainer_slot_count')->default(0);
            $table->boolean('pt_free_trial')->default(false);
            $table->string('pt_free_trial_slot', 32)->nullable();

            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            $table->string('status', 50)->default('confirmed');
            $table->string('confirmation_code', 50)->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_bookings');
    }
};
