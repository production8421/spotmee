<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('gym_bookings', 'coupon_applied_slots')) {
                $table->unsignedInteger('coupon_applied_slots')->nullable()->after('coupon_discount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            if (Schema::hasColumn('gym_bookings', 'coupon_applied_slots')) {
                $table->dropColumn('coupon_applied_slots');
            }
        });
    }
};
