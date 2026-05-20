<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bpsdmp.go.id'],
            [
                'name'     => 'Administrator BPSDMP',
                'nip'      => '000000000000',
                'position' => 'Administrator Sistem',
                'email'    => 'admin@bpsdmp.go.id',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
            ]
        );
    }
}
