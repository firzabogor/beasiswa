<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_registration_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->text('company_address');
            $table->string('applied_position');
            $table->timestamp('applied_at')->useCurrent();
            $table->enum('status', ['applied', 'selected', 'not_selected'])->default('applied');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_applications');
    }
};
