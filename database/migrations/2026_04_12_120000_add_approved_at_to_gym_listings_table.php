<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->timestamp('approved_at')->nullable()->after('is_published');
        });

        DB::table('gym_listings')
            ->whereNotNull('user_id')
            ->where('is_published', true)
            ->update(['approved_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->dropColumn('approved_at');
        });
    }
};
