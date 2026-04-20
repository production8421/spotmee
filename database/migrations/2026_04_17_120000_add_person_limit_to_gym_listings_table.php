<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->unsignedSmallInteger('person_limit')->nullable()->after('host_tier');
        });
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->dropColumn('person_limit');
        });
    }
};
