<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            // 'passed' = lolos seleksi akhir, 'not_passed' = tidak lolos
            $table->string('selection_final_result')->nullable()->after('loa_path');
            $table->string('selection_final_doc_path')->nullable()->after('selection_final_result');
        });
    }

    public function down(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->dropColumn(['selection_final_result', 'selection_final_doc_path']);
        });
    }
};
