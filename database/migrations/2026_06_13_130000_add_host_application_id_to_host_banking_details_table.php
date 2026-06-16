<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host_banking_details', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
        });

        Schema::table('host_banking_details', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreignId('host_application_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique('host_application_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('host_banking_details', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['host_application_id']);
            $table->dropConstrainedForeignId('host_application_id');
        });

        Schema::table('host_banking_details', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique('user_id');
        });
    }
};
