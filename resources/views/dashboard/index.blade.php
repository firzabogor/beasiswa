@extends('layouts.app')

@section('title', 'Dashboard Saya - Simdiklat')

@section('content')

<style>
.dash-header { margin-bottom: 24px; }
.dash-header h2 { font-size: 22px; font-weight: 800; color: var(--primary-color); }
.dash-header p { color: #666; font-size: 13px; margin-top: 4px; }

.reg-card {
    background: #fff; border: 1px solid #e0e6ed; border-radius: 12px;
    padding: 22px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.reg-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #f0f2f5; }
.reg-title { font-size: 16px; font-weight: 800; color: #1a3a6c; }
.reg-meta  { font-size: 12px; color: #888; margin-top: 4px; }

.step-flow { display: flex; align-items: center; gap: 0; margin: 16px 0; }
.step { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
.step:not(:last-child)::after { content:''; position: absolute; right: 0; top: 18px; width: calc(100% - 36px); height: 2px; background: #e0e6ed; left: 50%; }
.step-dot { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid #e0e6ed; background: #fff; z-index: 1; position: relative; }
.step-dot.done    { border-color: #28a745; background: #28a745; color: #fff; }
.step-dot.active  { border-color: #1a3a6c; background: #1a3a6c; color: #fff; }
.step-dot.failed  { border-color: #dc3545; background: #dc3545; color: #fff; }
.step-dot.waiting { border-color: #ddd; color: #ccc; }
.step-label { font-size: 11px; font-weight: 600; color: #888; margin-top: 8px; text-align: center; max-width: 80px; }
.step-label.done   { color: #28a745; }
.step-label.active { color: #1a3a6c; }
.step-label.failed { color: #dc3545; }

.action-area { background: #f8faff; border-radius: 8px; padding: 14px 16px; border: 1px solid #dce8ff; margin-top: 12px; }
.action-area .action-title { font-size: 13px; font-weight: 700; color: #1a3a6c; margin-bottom: 8px; }
</style>

<div class="dash-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div>
        <h2>📋 Dashboard Pendaftaran Saya</h2>
        <p>Pantau status pendaftaran dan ikuti setiap tahapan proses.</p>
    </div>
    <div class="filter-year">
        <form action="{{ url('/dashboard') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <label style="font-size: 13px; font-weight: 700; color: #1a3a6c;">Filter Tahun:</label>
            <select name="year" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #ddd; font-size: 13px; color: #1a3a6c;">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">❌ {{ session('error') }}</div>
@endif

@forelse($registrations as $reg)
@php
    $isEdu  = $reg->trainingProgram->kategori === 'pendidikan';
    $app    = $reg->companyApplication;
    $selDoc = $app?->selectionDocument;

    // Common Steps
    $s1 = 'done';
    $s2 = $reg->verification_status === 'verified' ? 'done' : ($reg->verification_status === 'rejected' ? 'failed' : 'active');
    $s3 = $reg->hasInvitationLetter() ? 'done' : ($s2 === 'done' ? 'active' : ($s2 === 'failed' ? 'failed' : 'waiting'));
    
    // Workflow Specific Steps
    if (!$isEdu) {
        $hasSelResult = !is_null($reg->selection_final_result);
        $selPassed    = $reg->selection_final_result === 'passed';
        $selNotPassed = $reg->selection_final_result === 'not_passed';

        if ($selNotPassed) {
            $s4 = 'failed';
            $s5 = 'failed';
        } elseif ($selPassed) {
            $s4 = 'done';
            $s5 = ($reg->final_report_path) ? 'done' : 'active';
        } else {
            $s4 = ($s3 === 'done') ? 'active' : 'waiting';
            $s5 = 'waiting';
        }
    } else {
        // Pendidikan: Step 4 = Hasil Seleksi Akhir
        $hasSelResult = !is_null($reg->selection_final_result);
        $selPassed    = $reg->selection_final_result === 'passed';
        $selNotPassed = $reg->selection_final_result === 'not_passed';

        if ($selNotPassed) {
            $s4 = 'failed';  // Tidak lolos — END here
            $s5 = 'failed';  // Step 5 tidak aktif
        } elseif ($selPassed) {
            $s4 = 'done';
            $s5 = ($reg->is_education_completed) ? 'done' : 'active';
        } else {
            $s4 = ($s3 === 'done') ? 'active' : 'waiting';
            $s5 = 'waiting';
        }
    }
 @endphp

<div class="reg-card">
    <div class="reg-card-header">
        <div>
            <div class="reg-title">
                @if($isEdu) 🎓 @else 📚 @endif
                {{ $reg->trainingProgram->title }}
            </div>
            <div class="reg-meta">
                {{ ucfirst($reg->trainingProgram->kategori) }} •
                {{ $reg->trainingProgram->location }} •
                Didaftarkan: {{ $reg->created_at->format('d M Y') }}
                @if($reg->programTopic)
                <br><span style="color: #1a3a6c; font-weight: 600; margin-top: 4px; display: inline-block;">Topik: {{ $reg->programTopic->name }}</span>
                @endif
            </div>
        </div>
        @if($reg->verification_status === 'pending')
            <span class="badge badge-pending">⏳ Sedang Diverifikasi</span>
        @elseif($reg->verification_status === 'verified')
            <span class="badge badge-verified">✅ Terverifikasi</span>
        @else
            <span class="badge badge-rejected">❌ Tidak Lolos Verifikasi</span>
        @endif
    </div>

    {{-- Step Flow --}}
    <div class="step-flow">
        <div class="step">
            <div class="step-dot {{ $s1 }}">✓</div>
            <div class="step-label {{ $s1 }}">Pendaftaran</div>
        </div>
        <div class="step">
            <div class="step-dot {{ $s2 }}">{{ $s2 === 'done' ? '✓' : ($s2 === 'failed' ? '✕' : '2') }}</div>
            <div class="step-label {{ $s2 }}">{{ $isEdu ? 'Verifikasi Berkas' : 'Verifikasi' }}</div>
        </div>
        <div class="step">
            <div class="step-dot {{ $s3 }}">{{ $s3 === 'done' ? '✓' : '3' }}</div>
            <div class="step-label {{ $s3 }}">Surat Rekomendasi</div>
        </div>
        
        @if(!$isEdu)
            <div class="step">
                <div class="step-dot {{ $s4 }}">{{ $s4 === 'done' ? '✓' : ($s4 === 'failed' ? '✕' : '4') }}</div>
                <div class="step-label {{ $s4 }}">Hasil Seleksi Akhir</div>
            </div>
            @if(!isset($selNotPassed) || !$selNotPassed)
            <div class="step">
                <div class="step-dot {{ $s5 }}">{{ $s5 === 'done' ? '✓' : '5' }}</div>
                <div class="step-label {{ $s5 }}">Laporan Akhir</div>
            </div>
            @endif
        @else
            <div class="step">
                <div class="step-dot {{ $s4 }}">{{ $s4 === 'done' ? '✓' : ($s4 === 'failed' ? '✕' : '4') }}</div>
                <div class="step-label {{ $s4 }}">Hasil Seleksi Akhir</div>
            </div>
            @if(!$selNotPassed)
            <div class="step">
                <div class="step-dot {{ $s5 }}">{{ $s5 === 'done' ? '✓' : '5' }}</div>
                <div class="step-label {{ $s5 }}">Laporan Akhir</div>
            </div>
            @endif
        @endif
    </div>

    {{-- Catatan Verifikasi --}}
    @if($reg->verification_notes)
        <div class="alert alert-info" style="font-size:13px;">
            💬 <strong>Catatan Admin:</strong> {{ $reg->verification_notes }}
        </div>
    @endif

    {{-- Action Area --}}
    <div class="action-area-wrapper">
        @if($reg->verification_status === 'rejected')
            <div class="action-area" style="border-color:#f8d7da;background:#fff8f8;">
                <div class="action-title" style="color:#dc3545;">❌ Verifikasi Tidak Lolos</div>
                <p style="font-size:13px;color:#666;">Maaf, pendaftaran Anda tidak lolos verifikasi. Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>

        @elseif($reg->verification_status === 'pending')
            <div class="action-area">
                <div class="action-title">⏳ Menunggu Verifikasi Admin</div>
                <p style="font-size:13px;color:#666;">Pendaftaran Anda sedang dalam proses verifikasi oleh admin. Anda akan mendapat notifikasi setelah selesai.</p>
            </div>

        @elseif($reg->isVerified() && !$reg->hasInvitationLetter())
            <div class="action-area">
                <div class="action-title">✅ Lolos Verifikasi — Menunggu Surat Rekomendasi</div>
                <p style="font-size:13px;color:#666;">Selamat! Anda lolos verifikasi. Admin sedang menyiapkan surat rekomendasi untuk Anda.</p>
            </div>

        @elseif($reg->hasInvitationLetter())
            {{-- Unified Action Area after Step 3 --}}
            @if(!$isEdu)
                {{-- PELATIHAN --}}
                @if($selNotPassed)
                    {{-- Tidak Lolos Seleksi Akhir → END --}}
                    <div class="action-area" style="border-color:#f8d7da;background:#fff8f8;">
                        <div class="action-title" style="color:#dc3545;">❌ Tidak Lolos Seleksi Akhir Pelatihan</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">
                            Maaf, Anda tidak lolos seleksi akhir pelatihan. Proses pendaftaran berakhir di tahapan ini. Terima kasih telah berpartisipasi.
                        </p>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                            @if($reg->selection_final_doc_path)
                                <a href="{{ asset('storage/' . $reg->selection_final_doc_path) }}" target="_blank" style="font-size:13px;color:var(--primary-color);">Lihat Dokumen Bukti Seleksi ⬇️</a>
                            @endif
                        </div>
                    </div>

                @elseif(!$hasSelResult)
                    {{-- Belum mengisi Hasil Seleksi Akhir --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">📄 Surat Rekomendasi Tersedia</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">
                            Terimakasih atas partisipasinya, Surat rekomendasi dari Sekretariat BPSDMP dapat digunakan sebagai proses administrasi.
                        </p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                        </div>
                        <div style="border-top:1px dashed #c6e3cc;padding-top:15px;">
                            <div class="action-title" style="color:#1a3a6c;">🎓 Tahap 4: Hasil Seleksi Akhir Pelatihan</div>
                            <p style="font-size:13px;color:#666;margin-bottom:10px;">Silakan unggah LOA jika Anda lolos, atau dokumen bukti jika tidak lolos seleksi pelatihan.</p>
                            <a href="{{ url('/pelatihan/' . $reg->id . '/upload-loa') }}" class="btn btn-primary btn-sm">📤 Isi Hasil Seleksi Akhir →</a>
                        </div>
                    </div>

                @elseif($selPassed && !$reg->final_report_path)
                    {{-- Lolos, Laporan Akhir --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">🎉 Lolos Seleksi Akhir Pelatihan</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">Selamat! Anda lolos seleksi akhir dan telah mengunggah LOA. Silakan unggah laporan akhir setelah pelatihan selesai.</p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                            @if($reg->loa_path)
                                <a href="{{ asset('storage/' . $reg->loa_path) }}" target="_blank" class="btn btn-info btn-sm" style="background:#17a2b8; color:white; border:none; padding:5px 10px; border-radius:4px; font-size:12px; text-decoration:none;">⬇️ Lihat LOA Anda</a>
                            @endif
                        </div>
                        <div style="border-top:1px dashed #c6e3cc;padding-top:15px;">
                            <div class="action-title" style="color:#856404;">📝 Tahap Akhir: Unggah Laporan Pelatihan</div>
                            <p style="font-size:13px;color:#666;margin-bottom:12px;">Silakan unggah laporan akhir Anda setelah menyelesaikan pelatihan.</p>
                            <a href="{{ url('/pelatihan/' . $reg->id . '/upload-laporan') }}" class="btn btn-warning btn-sm">📤 Unggah Laporan Akhir</a>
                        </div>
                    </div>
                @else
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">🏁 Pelatihan Selesai</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">Terimakasih atas partisipasinya, Surat rekomendasi dari Sekretariat BPSDMP dapat digunakan sebagai proses administrasi.</p>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                            <span style="font-size:13px;color:#28a745;font-weight:700;">✅ Laporan Akhir Sudah Diunggah</span>
                        </div>
                        <a href="{{ asset('storage/' . $reg->final_report_path) }}" target="_blank" style="display:block;margin-top:10px;font-size: 13px; color: var(--primary-color);">Lihat Laporan Akhir ⬇️</a>
                    </div>
                @endif
            @else
                {{-- PENDIDIKAN --}}
                @if($selNotPassed)
                    {{-- Tidak Lolos Seleksi Akhir → END --}}
                    <div class="action-area" style="border-color:#f8d7da;background:#fff8f8;">
                        <div class="action-title" style="color:#dc3545;">❌ Tidak Lolos Seleksi Akhir Beasiswa</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">
                            Maaf, Anda tidak lolos seleksi akhir beasiswa. Proses pendaftaran berakhir di tahapan ini. Terima kasih telah berpartisipasi.
                        </p>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                            @if($reg->selection_final_doc_path)
                                <a href="{{ asset('storage/' . $reg->selection_final_doc_path) }}" target="_blank" style="font-size:13px;color:var(--primary-color);">Lihat Dokumen Seleksi ⬇️</a>
                            @endif
                        </div>
                    </div>

                @elseif(!$hasSelResult)
                    {{-- Belum mengisi Hasil Seleksi Akhir --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">📄 Surat Rekomendasi Tersedia</div>
                        <p style="font-size:13px;color:#666;margin-bottom:10px;">
                            Terimakasih atas partisipasinya, Surat rekomendasi dari Sekretariat BPSDMP dapat digunakan sebagai proses administrasi.
                        </p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                        </div>
                        <div style="border-top:1px dashed #c6e3cc;padding-top:15px;">
                            <div class="action-title" style="color:#1a3a6c;">🎓 Tahap 4: Hasil Seleksi Akhir Beasiswa</div>
                            <p style="font-size:13px;color:#666;margin-bottom:10px;">Silakan unggah dokumen hasil seleksi akhir beasiswa Anda (wajib diisi baik lolos maupun tidak lolos).</p>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/upload-log-loa') }}" class="btn btn-primary btn-sm">📤 Isi Hasil Seleksi Akhir →</a>
                        </div>
                    </div>

                @elseif($selPassed && !$reg->is_education_completed)
                    {{-- Lolos, tapi belum selesai → Laporan Akhir --}}
                    <div class="action-area" style="border-color:#fff3cd;background:#fffaf0;">
                        <div class="action-title" style="color:#856404;">📖 Tahap Laporan Akhir Pendidikan</div>
                        <p style="font-size:13px;color:#666;margin-bottom:12px;">Pastikan Anda mengunggah laporan hasil belajar (rapot) setiap semesternya dan unggah berkas akhir untuk menyelesaikan program.</p>
                        
                        @if($reg->educationActivities->isNotEmpty())
                            <div style="margin-bottom: 12px; background: white; border: 1px solid #ffeeba; border-radius: 6px; padding: 10px;">
                                <div style="font-size: 11px; font-weight: 700; color: #856404; margin-bottom: 6px; text-transform: uppercase;">Riwayat Aktivitas:</div>
                                @foreach($reg->educationActivities as $act)
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; border-bottom: 1px solid #eee; padding: 4px 0;">
                                        <span>{{ $act->semester_name }}</span>
                                        <a href="{{ asset('storage/' . $act->document_path) }}" target="_blank" style="color: var(--primary-color); text-decoration: none;">Download ⬇️</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div style="display: flex; gap: 10px;">
                            <a href="{{ url('/pendidikan/' . $reg->id . '/tambah-aktivitas') }}" class="btn btn-warning btn-sm">➕ Tambah Laporan Semester</a>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/upload-akhir') }}" class="btn btn-success btn-sm">🎓 Selesaikan Pendidikan</a>
                        </div>
                    </div>

                @else
                    {{-- Selesai --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">🏁 Program Beasiswa Selesai</div>
                        <p style="font-size:13px;color:#666;">Selamat! Anda telah menyelesaikan seluruh rangkaian program pendidikan/beasiswa ini.</p>
                        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Rekomendasi</a>
                        </div>
                        @if($reg->final_doc_path)
                             <a href="{{ asset('storage/' . $reg->final_doc_path) }}" target="_blank" style="font-size: 13px; color: var(--primary-color);">Lihat Berkas Akhir / Ijazah ⬇️</a>
                        @endif
                    </div>
                @endif
            @endif
        @endif

    </div>
</div>
@empty
    <div style="text-align:center;padding:60px 20px;color:#aaa;">
        <div style="font-size:48px;margin-bottom:12px;">📋</div>
        <p style="font-size:15px;">Anda belum mendaftar program apapun.</p>
        <a href="{{ url('/diklat') }}" class="btn btn-primary" style="margin-top:16px;">Lihat Program Pelatihan</a>
    </div>
@endforelse

@endsection
