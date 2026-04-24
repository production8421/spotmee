<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->json('time_slots')->nullable()->after('end_time');
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->dropColumn('time_slots');
        });
    }
};
