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

        User::updateOrCreate(
            ['email' => 'admin.internal@bpsdmp.go.id'],
            [
                'name'     => 'Admin Beasiswa Internal',
                'nip'      => '000000000001',
                'position' => 'Admin Internal',
                'email'    => 'admin.internal@bpsdmp.go.id',
                'password' => Hash::make('AdminInternal123'),
                'role'     => 'admin',
            ]
        );
    }
}
