<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selection_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_application_id')->constrained()->onDelete('cascade');
            $table->string('document_path');
            $table->enum('selection_result', ['passed', 'not_passed']);
            $table->text('notes')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selection_documents');
    }
};
