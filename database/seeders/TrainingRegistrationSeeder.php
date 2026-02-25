<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingRegistration;

class TrainingRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = [
            // Budi (user_id=2) mendaftar ke program 1 dan 3
            [
                'user_id'             => 2,
                'training_program_id' => 1,
                'status'              => 'approved',
                'registered_at'       => now()->subDays(10),
            ],
            [
                'user_id'             => 2,
                'training_program_id' => 3,
                'status'              => 'pending',
                'registered_at'       => now()->subDays(3),
            ],
            // Siti (user_id=3) mendaftar ke program 2 dan 5
            [
                'user_id'             => 3,
                'training_program_id' => 2,
                'status'              => 'approved',
                'registered_at'       => now()->subDays(8),
            ],
            [
                'user_id'             => 3,
                'training_program_id' => 5,
                'status'              => 'pending',
                'registered_at'       => now()->subDays(2),
            ],
            // Agus (user_id=4) mendaftar ke program 4 (closed) - rejected
            [
                'user_id'             => 4,
                'training_program_id' => 4,
                'status'              => 'rejected',
                'registered_at'       => now()->subDays(20),
            ],
            [
                'user_id'             => 4,
                'training_program_id' => 1,
                'status'              => 'pending',
                'registered_at'       => now()->subDays(5),
            ],
            // Dewi (user_id=5) mendaftar ke program 3 dan 6
            [
                'user_id'             => 5,
                'training_program_id' => 3,
                'status'              => 'approved',
                'registered_at'       => now()->subDays(7),
            ],
            [
                'user_id'             => 5,
                'training_program_id' => 6,
                'status'              => 'pending',
                'registered_at'       => now()->subDays(1),
            ],
            // Rudi (user_id=6) mendaftar ke program 2
            [
                'user_id'             => 6,
                'training_program_id' => 2,
                'status'              => 'pending',
                'registered_at'       => now()->subDays(4),
            ],
        ];

        foreach ($registrations as $reg) {
            TrainingRegistration::create($reg);
        }
    }
}
