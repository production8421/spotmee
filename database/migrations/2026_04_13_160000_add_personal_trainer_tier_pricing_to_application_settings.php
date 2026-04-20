<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->decimal('pt_silver_price_per_slot', 10, 2)->nullable()->after('platinum_tier_admin_commission_40_min_pct');
            $table->decimal('pt_silver_admin_commission_pct', 6, 2)->nullable()->after('pt_silver_price_per_slot');
            $table->decimal('pt_gold_price_per_slot', 10, 2)->nullable()->after('pt_silver_admin_commission_pct');
            $table->decimal('pt_gold_admin_commission_pct', 6, 2)->nullable()->after('pt_gold_price_per_slot');
            $table->decimal('pt_platinum_price_per_slot', 10, 2)->nullable()->after('pt_gold_admin_commission_pct');
            $table->decimal('pt_platinum_admin_commission_pct', 6, 2)->nullable()->after('pt_platinum_price_per_slot');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'pt_silver_price_per_slot',
                'pt_silver_admin_commission_pct',
                'pt_gold_price_per_slot',
                'pt_gold_admin_commission_pct',
                'pt_platinum_price_per_slot',
                'pt_platinum_admin_commission_pct',
            ]);
        });
    }
};
