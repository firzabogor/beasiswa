<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingProgram;

class TrainingProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title'       => 'Diklat Kepemimpinan Tingkat III',
                'description' => 'Program pendidikan dan pelatihan kepemimpinan bagi pejabat eselon III untuk meningkatkan kompetensi manajerial dan kepemimpinan dalam penyelenggaraan pemerintahan.',
                'start_date'  => '2026-03-10 08:00:00',
                'end_date'    => '2026-03-28 17:00:00',
                'location'    => 'Gedung Pusat Pelatihan, Jakarta',
                'quota'       => 30,
                'status'      => 'open',
            ],
            [
                'title'       => 'Pelatihan Teknis Pengadaan Barang/Jasa',
                'description' => 'Pelatihan teknis bagi ASN dalam bidang pengadaan barang dan jasa pemerintah sesuai Perpres No. 16 Tahun 2018 beserta perubahannya.',
                'start_date'  => '2026-03-15 08:00:00',
                'end_date'    => '2026-03-19 17:00:00',
                'location'    => 'Aula Serbaguna Lt. 3, Bandung',
                'quota'       => 40,
                'status'      => 'open',
            ],
            [
                'title'       => 'Belajar Mandiri: Transformasi Digital Pemerintahan',
                'description' => 'Program belajar mandiri berbasis e-learning tentang transformasi digital dalam tata kelola pemerintahan, mencakup SPBE, big data, dan keamanan siber.',
                'start_date'  => '2026-04-01 08:00:00',
                'end_date'    => '2026-04-30 17:00:00',
                'location'    => 'Online (E-Learning Platform)',
                'quota'       => 100,
                'status'      => 'open',
            ],
            [
                'title'       => 'Diklat Fungsional Analis Kebijakan',
                'description' => 'Pendidikan dan pelatihan fungsional bagi calon analis kebijakan untuk memenuhi syarat pengangkatan dalam jabatan fungsional analis kebijakan.',
                'start_date'  => '2026-02-03 08:00:00',
                'end_date'    => '2026-02-21 17:00:00',
                'location'    => 'Pusdiklat Aparatur, Bogor',
                'quota'       => 25,
                'status'      => 'closed',
            ],
            [
                'title'       => 'Pelatihan Manajemen Kinerja ASN',
                'description' => 'Pelatihan bagi ASN tentang sistem manajemen kinerja berbasis SKP (Sasaran Kinerja Pegawai) sesuai PP No. 30 Tahun 2019 dan peraturan turunannya.',
                'start_date'  => '2026-04-14 08:00:00',
                'end_date'    => '2026-04-16 17:00:00',
                'location'    => 'Hotel Grand Mercure, Surabaya',
                'quota'       => 50,
                'status'      => 'open',
            ],
            [
                'title'       => 'Belajar Mandiri: Penulisan Karya Tulis Ilmiah',
                'description' => 'Program belajar mandiri untuk meningkatkan kemampuan ASN dalam menyusun karya tulis ilmiah, laporan penelitian, dan publikasi ilmiah.',
                'start_date'  => '2026-05-01 08:00:00',
                'end_date'    => '2026-05-31 17:00:00',
                'location'    => 'Online (E-Learning Platform)',
                'quota'       => 80,
                'status'      => 'open',
            ],
        ];

        foreach ($programs as $program) {
            TrainingProgram::create($program);
        }
    }
}
