<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->decimal('silver_tier_price_1_hour', 10, 2)->nullable()->after('stripe_live_secret_key');
            $table->decimal('silver_tier_price_40_min', 10, 2)->nullable()->after('silver_tier_price_1_hour');
            $table->decimal('silver_tier_admin_commission_1_hour_pct', 6, 2)->nullable()->after('silver_tier_price_40_min');
            $table->decimal('silver_tier_admin_commission_40_min_pct', 6, 2)->nullable()->after('silver_tier_admin_commission_1_hour_pct');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'silver_tier_price_1_hour',
                'silver_tier_price_40_min',
                'silver_tier_admin_commission_1_hour_pct',
                'silver_tier_admin_commission_40_min_pct',
            ]);
        });
    }
};
