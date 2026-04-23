<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            if (! Schema::hasColumn('coupons', 'valid_sessions')) {
                $table->unsignedInteger('valid_sessions')->default(1)->after('description');
            }
        });

        if (Schema::hasColumn('coupons', 'discount_type')) {
            Schema::table('coupons', function (Blueprint $table): void {
                $table->dropColumn('discount_type');
            });
        }
        if (Schema::hasColumn('coupons', 'discount_value')) {
            Schema::table('coupons', function (Blueprint $table): void {
                $table->dropColumn('discount_value');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('coupons', 'valid_sessions')) {
            Schema::table('coupons', function (Blueprint $table): void {
                $table->dropColumn('valid_sessions');
            });
        }

        Schema::table('coupons', function (Blueprint $table): void {
            if (! Schema::hasColumn('coupons', 'discount_type')) {
                $table->string('discount_type', 20)->default('percent')->after('description');
            }
            if (! Schema::hasColumn('coupons', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            }
        });
    }
};
