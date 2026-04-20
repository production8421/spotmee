<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const URL_COLUMNS = [
        'legal_booking_terms_url',
        'legal_booking_privacy_url',
        'legal_host_terms_url',
        'legal_host_privacy_url',
        'booking_cancel_result_url',
        'legal_host_registration_url',
        'webhook_booking_completed_url',
        'webhook_booking_cancelled_url',
    ];

    public function up(): void
    {
        $tableName = 'application_settings';

        Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
            if (! Schema::hasColumn($tableName, 'legal_booking_terms_url')) {
                $table->text('legal_booking_terms_url')->nullable()->after('pt_platinum_admin_commission_pct');
            }
            if (! Schema::hasColumn($tableName, 'legal_booking_privacy_url')) {
                $table->text('legal_booking_privacy_url')->nullable()->after('legal_booking_terms_url');
            }
            if (! Schema::hasColumn($tableName, 'legal_host_terms_url')) {
                $table->text('legal_host_terms_url')->nullable()->after('legal_booking_privacy_url');
            }
            if (! Schema::hasColumn($tableName, 'legal_host_privacy_url')) {
                $table->text('legal_host_privacy_url')->nullable()->after('legal_host_terms_url');
            }
            if (! Schema::hasColumn($tableName, 'booking_cancel_result_url')) {
                $table->text('booking_cancel_result_url')->nullable()->after('legal_host_privacy_url');
            }
            if (! Schema::hasColumn($tableName, 'legal_host_registration_url')) {
                $table->text('legal_host_registration_url')->nullable()->after('booking_cancel_result_url');
            }
            if (! Schema::hasColumn($tableName, 'webhook_booking_completed_url')) {
                $table->text('webhook_booking_completed_url')->nullable()->after('legal_host_registration_url');
            }
            if (! Schema::hasColumn($tableName, 'webhook_booking_completed_secret')) {
                $table->text('webhook_booking_completed_secret')->nullable()->after('webhook_booking_completed_url');
            }
            if (! Schema::hasColumn($tableName, 'webhook_booking_cancelled_url')) {
                $table->text('webhook_booking_cancelled_url')->nullable()->after('webhook_booking_completed_secret');
            }
            if (! Schema::hasColumn($tableName, 'webhook_booking_cancelled_secret')) {
                $table->text('webhook_booking_cancelled_secret')->nullable()->after('webhook_booking_cancelled_url');
            }
        });

        // Shrink row footprint on MySQL: VARCHAR(2048) URLs from a partial run → TEXT (off-row in InnoDB).
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            foreach (self::URL_COLUMNS as $column) {
                if (! Schema::hasColumn($tableName, $column)) {
                    continue;
                }
                DB::statement('ALTER TABLE `application_settings` MODIFY `'.$column.'` TEXT NULL');
            }
        }
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'legal_booking_terms_url',
                'legal_booking_privacy_url',
                'legal_host_terms_url',
                'legal_host_privacy_url',
                'booking_cancel_result_url',
                'legal_host_registration_url',
                'webhook_booking_completed_url',
                'webhook_booking_completed_secret',
                'webhook_booking_cancelled_url',
                'webhook_booking_cancelled_secret',
            ]);
        });
    }
};
