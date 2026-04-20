<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id', 255)->nullable()->after('confirmation_code')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->dropColumn('stripe_payment_intent_id');
        });
    }
};
