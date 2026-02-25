<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin PPP',
            'email'    => 'admin@ppp.go.id',
            'nip'      => '198001012005011001',
            'position' => 'Administrator',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Users biasa
        $users = [
            [
                'name'     => 'Budi Santoso',
                'email'    => 'budi@ppp.go.id',
                'nip'      => '199203152015031002',
                'position' => 'Staf Teknis',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Siti Rahayu',
                'email'    => 'siti@ppp.go.id',
                'nip'      => '199507202018042003',
                'position' => 'Analis Kebijakan',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Agus Wijaya',
                'email'    => 'agus@ppp.go.id',
                'nip'      => '198811102012031004',
                'position' => 'Kepala Seksi',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Dewi Kusuma',
                'email'    => 'dewi@ppp.go.id',
                'nip'      => '199001252016042005',
                'position' => 'Staf Administrasi',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Rudi Hartono',
                'email'    => 'rudi@ppp.go.id',
                'nip'      => '198706182014031006',
                'position' => 'Pengembang SDM',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
