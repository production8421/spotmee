<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->decimal('gold_tier_price_1_hour', 10, 2)->nullable()->after('silver_tier_admin_commission_40_min_pct');
            $table->decimal('gold_tier_price_40_min', 10, 2)->nullable()->after('gold_tier_price_1_hour');
            $table->decimal('gold_tier_admin_commission_1_hour_pct', 6, 2)->nullable()->after('gold_tier_price_40_min');
            $table->decimal('gold_tier_admin_commission_40_min_pct', 6, 2)->nullable()->after('gold_tier_admin_commission_1_hour_pct');

            $table->decimal('platinum_tier_price_1_hour', 10, 2)->nullable()->after('gold_tier_admin_commission_40_min_pct');
            $table->decimal('platinum_tier_price_40_min', 10, 2)->nullable()->after('platinum_tier_price_1_hour');
            $table->decimal('platinum_tier_admin_commission_1_hour_pct', 6, 2)->nullable()->after('platinum_tier_price_40_min');
            $table->decimal('platinum_tier_admin_commission_40_min_pct', 6, 2)->nullable()->after('platinum_tier_admin_commission_1_hour_pct');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'gold_tier_price_1_hour',
                'gold_tier_price_40_min',
                'gold_tier_admin_commission_1_hour_pct',
                'gold_tier_admin_commission_40_min_pct',
                'platinum_tier_price_1_hour',
                'platinum_tier_price_40_min',
                'platinum_tier_admin_commission_1_hour_pct',
                'platinum_tier_admin_commission_40_min_pct',
            ]);
        });
    }
};
