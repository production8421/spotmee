<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn(['gym_listings_public_note', 'gym_listings_contact_email']);
        });

        Schema::table('application_settings', function (Blueprint $table): void {
            $table->string('stripe_mode', 16)->default('test')->after('footer_logo_path');
            $table->string('stripe_test_publishable_key')->nullable()->after('stripe_mode');
            $table->text('stripe_test_secret_key')->nullable()->after('stripe_test_publishable_key');
            $table->string('stripe_live_publishable_key')->nullable()->after('stripe_test_secret_key');
            $table->text('stripe_live_secret_key')->nullable()->after('stripe_live_publishable_key');
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'stripe_mode',
                'stripe_test_publishable_key',
                'stripe_test_secret_key',
                'stripe_live_publishable_key',
                'stripe_live_secret_key',
            ]);
        });

        Schema::table('application_settings', function (Blueprint $table): void {
            $table->text('gym_listings_public_note')->nullable()->after('footer_logo_path');
            $table->string('gym_listings_contact_email')->nullable()->after('gym_listings_public_note');
        });
    }
};
