<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('application_settings', 'legal_user_nda_pdf_path')) {
                $table->string('legal_user_nda_pdf_path', 500)->nullable()->after('waiver_liability_user_hero_background_color');
            }
            if (! Schema::hasColumn('application_settings', 'legal_user_non_compete_pdf_path')) {
                $table->string('legal_user_non_compete_pdf_path', 500)->nullable()->after('legal_user_nda_pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            if (Schema::hasColumn('application_settings', 'legal_user_non_compete_pdf_path')) {
                $table->dropColumn('legal_user_non_compete_pdf_path');
            }
            if (Schema::hasColumn('application_settings', 'legal_user_nda_pdf_path')) {
                $table->dropColumn('legal_user_nda_pdf_path');
            }
        });
    }
};
