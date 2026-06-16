<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('host_banking_details', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('account_holder_name', 200);
            $table->string('bank_name', 200)->nullable();
            $table->string('account_type', 32)->nullable();
            $table->text('routing_number');
            $table->text('account_number');
            $table->string('bank_country', 2)->default('US');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('host_banking_details');
    }
};
