<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            $table->boolean('host_payout_split_enabled')->default(true)->after('host_payout_failure_reason');
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            $table->dropColumn('host_payout_split_enabled');
        });
    }
};
