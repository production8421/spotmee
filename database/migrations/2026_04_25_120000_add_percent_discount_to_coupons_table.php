<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            if (! Schema::hasColumn('coupons', 'percent_discount_enabled')) {
                $table->boolean('percent_discount_enabled')->default(false)->after('valid_sessions');
            }
            if (! Schema::hasColumn('coupons', 'percent_discount')) {
                $table->decimal('percent_discount', 5, 2)->nullable()->after('percent_discount_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            if (Schema::hasColumn('coupons', 'percent_discount')) {
                $table->dropColumn('percent_discount');
            }
            if (Schema::hasColumn('coupons', 'percent_discount_enabled')) {
                $table->dropColumn('percent_discount_enabled');
            }
        });
    }
};
