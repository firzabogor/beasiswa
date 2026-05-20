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
        Schema::create('education_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_registration_id')->constrained('training_registrations')->onDelete('cascade');
            $table->string('semester_name'); // e.g., "Semester 1", "Semester 2"
            $table->string('document_path');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_activities');
    }
};
