<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'application_settings';

        if (! Schema::hasColumn($tableName, 'gym_booking_email_templates')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->json('gym_booking_email_templates')->nullable()->after('webhook_booking_cancelled_secret');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_settings', 'gym_booking_email_templates')) {
            Schema::table('application_settings', function (Blueprint $table): void {
                $table->dropColumn('gym_booking_email_templates');
            });
        }
    }
};
