<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->string('service_type', 32)->nullable()->after('person_limit');
        });
    }

    public function down(): void
    {
        Schema::table('gym_listings', function (Blueprint $table): void {
            $table->dropColumn('service_type');
        });
    }
};
