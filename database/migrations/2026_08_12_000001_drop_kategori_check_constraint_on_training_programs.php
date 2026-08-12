<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL keeps the CHECK constraint when enum is changed to string via ->change()
        DB::statement('ALTER TABLE training_programs DROP CONSTRAINT IF EXISTS training_programs_kategori_check');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE training_programs ADD CONSTRAINT training_programs_kategori_check CHECK (kategori IN ('diklat', 'beasiswa'))");
    }
};
