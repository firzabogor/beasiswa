<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->string('log_path')->nullable()->after('invitation_letter_uploaded_at');
            $table->string('loa_path')->nullable()->after('log_path');
            $table->string('final_doc_path')->nullable()->after('loa_path');
            $table->boolean('is_education_completed')->default(false)->after('final_doc_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            //
        });
    }
};
