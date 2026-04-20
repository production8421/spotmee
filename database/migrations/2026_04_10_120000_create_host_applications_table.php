<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('host_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->text('social_security_number')->nullable();
            $table->string('phone', 32);
            $table->string('email');
            $table->string('street_address');
            $table->string('city', 120);
            $table->string('state', 120);
            $table->string('postal_code', 32);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('host_applications');
    }
};
