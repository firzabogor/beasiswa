<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $consultations = [
            [
                'user_id' => 2,
                'tujuan'  => 'konsultasi_teknis',
                'pimpinan'=> 'direktur',
                'alasan'  => 'Membutuhkan arahan teknis terkait implementasi sistem informasi kepegawaian yang baru di unit kerja kami.',
                'jadwal'  => '2026-03-05 09:00:00',
                'status'  => 'approved',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(14),
            ],
            [
                'user_id' => 3,
                'tujuan'  => 'konsultasi_sdm',
                'pimpinan'=> 'manajer',
                'alasan'  => 'Konsultasi mengenai rencana pengembangan kompetensi pegawai di bidang analisis kebijakan untuk tahun anggaran 2026.',
                'jadwal'  => '2026-03-12 10:00:00',
                'status'  => 'pending',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => 4,
                'tujuan'  => 'konsultasi_teknis',
                'pimpinan'=> 'manajer',
                'alasan'  => 'Perlu konsultasi terkait prosedur pengadaan barang dan jasa untuk kebutuhan operasional kantor tahun 2026.',
                'jadwal'  => '2026-02-25 13:00:00',
                'status'  => 'rejected',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(19),
            ],
            [
                'user_id' => 5,
                'tujuan'  => 'konsultasi_sdm',
                'pimpinan'=> 'direktur',
                'alasan'  => 'Konsultasi mengenai mekanisme mutasi dan rotasi pegawai serta dampaknya terhadap kinerja organisasi.',
                'jadwal'  => '2026-04-02 09:30:00',
                'status'  => 'pending',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => 6,
                'tujuan'  => 'konsultasi_teknis',
                'pimpinan'=> 'direktur',
                'alasan'  => 'Memerlukan bimbingan teknis terkait penyusunan laporan kinerja tahunan dan evaluasi program pengembangan SDM.',
                'jadwal'  => '2026-03-20 14:00:00',
                'status'  => 'approved',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9),
            ],
        ];

        DB::table('consultations')->insert($consultations);
    }
}
