<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('stripe_payment_intent_id')->constrained('coupons')->nullOnDelete();
            $table->decimal('coupon_discount', 10, 2)->nullable()->after('coupon_id');
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->dropColumn('coupon_discount');
            $table->dropConstrainedForeignId('coupon_id');
        });
    }
};
