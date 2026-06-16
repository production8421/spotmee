<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            $table->timestamp('host_payout_scheduled_at')->nullable()->after('coupon_applied_slots');
            $table->decimal('host_payout_amount', 10, 2)->nullable()->after('host_payout_scheduled_at');
            $table->string('host_payout_status', 32)->nullable()->after('host_payout_amount');
            $table->timestamp('host_payout_processed_at')->nullable()->after('host_payout_status');
            $table->string('stripe_transfer_id', 255)->nullable()->after('host_payout_processed_at');
            $table->string('host_payout_skip_reason', 255)->nullable()->after('stripe_transfer_id');
            $table->text('host_payout_failure_reason')->nullable()->after('host_payout_skip_reason');

            $table->index(['host_payout_status', 'host_payout_scheduled_at'], 'gym_bookings_host_payout_due_idx');
        });
    }

    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table): void {
            $table->dropIndex('gym_bookings_host_payout_due_idx');
            $table->dropColumn([
                'host_payout_scheduled_at',
                'host_payout_amount',
                'host_payout_status',
                'host_payout_processed_at',
                'stripe_transfer_id',
                'host_payout_skip_reason',
                'host_payout_failure_reason',
            ]);
        });
    }
};
