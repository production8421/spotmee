<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('application_settings', 'legal_host_nda_pdf_path')) {
                $table->string('legal_host_nda_pdf_path', 500)->nullable()->after('waiver_liability_host_hero_background_color');
            }
            if (! Schema::hasColumn('application_settings', 'legal_host_contractor_pdf_path')) {
                $table->string('legal_host_contractor_pdf_path', 500)->nullable()->after('legal_host_nda_pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            if (Schema::hasColumn('application_settings', 'legal_host_contractor_pdf_path')) {
                $table->dropColumn('legal_host_contractor_pdf_path');
            }
            if (Schema::hasColumn('application_settings', 'legal_host_nda_pdf_path')) {
                $table->dropColumn('legal_host_nda_pdf_path');
            }
        });
    }
};
