<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host_applications', function (Blueprint $table): void {
            if (! Schema::hasColumn('host_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_by');
            }
            if (! Schema::hasColumn('host_applications', 'rejection_message')) {
                $table->text('rejection_message')->nullable()->after('rejected_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('host_applications', function (Blueprint $table): void {
            if (Schema::hasColumn('host_applications', 'rejection_message')) {
                $table->dropColumn('rejection_message');
            }
            if (Schema::hasColumn('host_applications', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};
