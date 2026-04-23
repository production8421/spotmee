<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'application_settings';

        if (! Schema::hasColumn($tableName, 'notification_email_templates')) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $after = Schema::hasColumn($tableName, 'gym_booking_email_templates')
                    ? 'gym_booking_email_templates'
                    : 'webhook_booking_cancelled_secret';
                $table->json('notification_email_templates')->nullable()->after($after);
            });
        }

        if (Schema::hasColumn($tableName, 'gym_booking_email_templates')) {
            $rows = DB::table($tableName)->select(['id', 'gym_booking_email_templates'])->get();
            foreach ($rows as $row) {
                $old = json_decode((string) ($row->gym_booking_email_templates ?? ''), true);
                if (! is_array($old)) {
                    $old = [];
                }
                $map = [
                    'admin' => 'gym_booking_admin',
                    'host' => 'gym_booking_host',
                    'guest' => 'gym_booking_guest',
                ];
                $merged = [];
                foreach ($map as $legacyKey => $newKey) {
                    $slot = $old[$legacyKey] ?? [];
                    $merged[$newKey] = [
                        'subject' => is_array($slot) && isset($slot['subject']) ? (string) $slot['subject'] : '',
                        'body_html' => is_array($slot) && isset($slot['body_html']) ? (string) $slot['body_html'] : '',
                    ];
                }
                DB::table($tableName)->where('id', $row->id)->update([
                    'notification_email_templates' => json_encode($merged),
                ]);
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('gym_booking_email_templates');
            });
        }
    }

    public function down(): void
    {
        $tableName = 'application_settings';

        if (! Schema::hasColumn($tableName, 'gym_booking_email_templates')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->json('gym_booking_email_templates')->nullable()->after('webhook_booking_cancelled_secret');
            });
        }

        if (Schema::hasColumn($tableName, 'notification_email_templates')) {
            $rows = DB::table($tableName)->select(['id', 'notification_email_templates'])->get();
            foreach ($rows as $row) {
                $src = json_decode((string) ($row->notification_email_templates ?? ''), true);
                if (! is_array($src)) {
                    $src = [];
                }
                $legacy = [
                    'admin' => $src['gym_booking_admin'] ?? ['subject' => '', 'body_html' => ''],
                    'host' => $src['gym_booking_host'] ?? ['subject' => '', 'body_html' => ''],
                    'guest' => $src['gym_booking_guest'] ?? ['subject' => '', 'body_html' => ''],
                ];
                DB::table($tableName)->where('id', $row->id)->update([
                    'gym_booking_email_templates' => json_encode($legacy),
                ]);
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('notification_email_templates');
            });
        }
    }
};
