<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            if (Schema::hasColumn('coupons', 'max_redemptions')) {
                $table->dropColumn('max_redemptions');
            }
            if (Schema::hasColumn('coupons', 'starts_at')) {
                $table->dropColumn('starts_at');
            }
            if (Schema::hasColumn('coupons', 'ends_at')) {
                $table->dropColumn('ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            if (! Schema::hasColumn('coupons', 'max_redemptions')) {
                $table->unsignedInteger('max_redemptions')->nullable();
            }
            if (! Schema::hasColumn('coupons', 'starts_at')) {
                $table->timestamp('starts_at')->nullable();
            }
            if (! Schema::hasColumn('coupons', 'ends_at')) {
                $table->timestamp('ends_at')->nullable();
            }
        });
    }
};
