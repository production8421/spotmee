<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->text('gym_listings_public_note')->nullable()->after('footer_logo_path');
            $table->string('gym_listings_contact_email')->nullable()->after('gym_listings_public_note');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->dropColumn(['gym_listings_public_note', 'gym_listings_contact_email']);
        });
    }
};
