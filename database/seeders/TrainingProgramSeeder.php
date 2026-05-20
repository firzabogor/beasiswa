<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingProgram;

class TrainingProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            // ── Program Diklat ──────────────────────────────────────────
            [
                'title'       => 'Diklat Kepemimpinan Tingkat III',
                'kategori'    => 'pelatihan',
                'description' => 'Program pendidikan dan pelatihan kepemimpinan bagi pejabat eselon III untuk meningkatkan kompetensi manajerial dan kepemimpinan dalam penyelenggaraan pemerintahan.',
                'start_date'  => '2026-03-10 08:00:00',
                'end_date'    => '2026-03-28 17:00:00',
                'location'    => 'Gedung Pusat Pelatihan, Jakarta',
                'quota'       => 30,
                'status'      => 'open',
            ],
            [
                'title'       => 'Pelatihan Teknis Pengadaan Barang/Jasa',
                'kategori'    => 'pelatihan',
                'description' => 'Pelatihan teknis bagi ASN dalam bidang pengadaan barang dan jasa pemerintah sesuai Perpres No. 16 Tahun 2018 beserta perubahannya.',
                'start_date'  => '2026-03-15 08:00:00',
                'end_date'    => '2026-03-19 17:00:00',
                'location'    => 'Aula Serbaguna Lt. 3, Bandung',
                'quota'       => 40,
                'status'      => 'open',
            ],
            [
                'title'       => 'Diklat Fungsional Analis Kebijakan',
                'kategori'    => 'pelatihan',
                'description' => 'Pendidikan dan pelatihan fungsional bagi calon analis kebijakan untuk memenuhi syarat pengangkatan dalam jabatan fungsional analis kebijakan.',
                'start_date'  => '2026-02-03 08:00:00',
                'end_date'    => '2026-02-21 17:00:00',
                'location'    => 'Pusdiklat Aparatur, Bogor',
                'quota'       => 25,
                'status'      => 'closed',
            ],
            [
                'title'       => 'Pelatihan Manajemen Kinerja ASN',
                'kategori'    => 'pelatihan',
                'description' => 'Pelatihan bagi ASN tentang sistem manajemen kinerja berbasis SKP (Sasaran Kinerja Pegawai) sesuai PP No. 30 Tahun 2019 dan peraturan turunannya.',
                'start_date'  => '2026-04-14 08:00:00',
                'end_date'    => '2026-04-16 17:00:00',
                'location'    => 'Hotel Grand Mercure, Surabaya',
                'quota'       => 50,
                'status'      => 'open',
            ],

            // ── Program Bea Siswa ───────────────────────────────────────
            [
                'title'       => 'Beasiswa Tugas Belajar S2 Dalam Negeri',
                'kategori'    => 'pendidikan',
                'description' => 'Program beasiswa penuh bagi ASN berprestasi untuk melanjutkan studi S2 di perguruan tinggi terkemuka dalam negeri guna meningkatkan kapasitas sumber daya manusia aparatur.',
                'start_date'  => '2026-08-01 08:00:00',
                'end_date'    => '2028-07-31 17:00:00',
                'location'    => 'Perguruan Tinggi Dalam Negeri',
                'quota'       => 20,
                'status'      => 'open',
            ],
            [
                'title'       => 'Beasiswa Tugas Belajar S3 Luar Negeri',
                'kategori'    => 'pendidikan',
                'description' => 'Program beasiswa penuh untuk studi doktoral di universitas internasional guna meningkatkan kapasitas ASN di bidang strategis dan mendukung kebijakan riset pemerintah.',
                'start_date'  => '2026-09-01 08:00:00',
                'end_date'    => '2029-08-31 17:00:00',
                'location'    => 'Universitas Luar Negeri (Eropa / Asia)',
                'quota'       => 10,
                'status'      => 'open',
            ],
            [
                'title'       => 'Beasiswa Pendidikan Vokasi Perhubungan',
                'kategori'    => 'pendidikan',
                'description' => 'Program beasiswa vokasi untuk meningkatkan keahlian teknis ASN di bidang transportasi dan perhubungan melalui pendidikan di politeknik perhubungan.',
                'start_date'  => '2026-07-01 08:00:00',
                'end_date'    => '2027-06-30 17:00:00',
                'location'    => 'Politeknik Transportasi Darat, Bekasi',
                'quota'       => 35,
                'status'      => 'open',
            ],
        ];

        foreach ($programs as $program) {
            TrainingProgram::create($program);
        }
    }
}
