<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('coupons')
            ->where('applies_to', 'personal_training')
            ->update(['applies_to' => 'full_booking']);
    }

    public function down(): void
    {
        // Irreversible: prior PT-only intent is not recoverable.
    }
};
