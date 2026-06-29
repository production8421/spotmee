<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->decimal('platform_commission_pct', 6, 2)->default(20)->after('host_payout_delay_hours');
        });

        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->decimal('host_price_1_hour', 10, 2)->nullable()->after('host_tier');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn('platform_commission_pct');
        });

        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->dropColumn('host_price_1_hour');
        });
    }
};
