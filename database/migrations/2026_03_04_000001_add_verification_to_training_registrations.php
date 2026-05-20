<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('status');
            $table->text('verification_notes')->nullable()->after('verification_status');
            $table->string('invitation_letter_path')->nullable()->after('verification_notes');
            $table->timestamp('invitation_letter_uploaded_at')->nullable()->after('invitation_letter_path');
        });
    }

    public function down(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verification_notes', 'invitation_letter_path', 'invitation_letter_uploaded_at']);
        });
    }
};
