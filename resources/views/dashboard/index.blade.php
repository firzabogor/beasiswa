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

{{-- Modal Pop-up Knowledge Sharing --}}
{{-- Muncul otomatis jika ada pelatihan yang sudah selesai (final_report_path terisi) --}}
@php
    $completedTrainingIds = $registrations
        ->filter(fn($r) => $r->trainingProgram && $r->trainingProgram->kategori === 'pelatihan' && $r->final_report_path)
        ->pluck('id')
        ->toArray();
@endphp

@if(count($completedTrainingIds) > 0)

{{-- ===== ELEGANT KS MODAL STYLES ===== --}}
<style>
/* Overlay */
#ks-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    display: none; align-items: center; justify-content: center;
    background: rgba(5, 15, 40, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 20px;
}

/* Modal Card */
#ks-modal-card {
    background: #ffffff;
    border-radius: 24px;
    max-width: 500px;
    width: 100%;
    overflow: hidden;
    box-shadow:
        0 25px 80px rgba(0, 0, 0, 0.35),
        0 0 0 1px rgba(255,255,255,0.08);
    animation: ksSlideUp 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    transform: translateY(40px);
    opacity: 0;
}

/* Hero Header */
#ks-modal-hero {
    position: relative;
    background: linear-gradient(135deg, #0f2554 0%, #1a3a8c 45%, #2563eb 100%);
    padding: 36px 32px 28px;
    overflow: hidden;
    text-align: center;
}

/* Decorative circles */
#ks-modal-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
#ks-modal-hero::after {
    content: '';
    position: absolute;
    bottom: -30px; left: -30px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}

.ks-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 100px;
    padding: 5px 14px;
    font-size: 11px;
    font-weight: 700;
    color: rgba(255,255,255,0.9);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 18px;
}

.ks-hero-icon-wrap {
    width: 72px; height: 72px;
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
    margin: 0 auto 14px;
    position: relative;
    z-index: 1;
}

.ks-hero-title {
    font-size: 22px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px;
    position: relative; z-index: 1;
    letter-spacing: -0.3px;
}

.ks-hero-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.7);
    margin: 0;
    position: relative; z-index: 1;
}

/* Body */
#ks-modal-body {
    padding: 28px 32px;
}

.ks-desc {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.8;
    margin: 0 0 20px;
}

/* Info box */
.ks-info-box {
    background: linear-gradient(135deg, #fffbeb, #fef9ec);
    border: 1px solid #fcd34d;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.ks-info-icon {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 1px;
}

.ks-info-text {
    font-size: 13px;
    color: #78350f;
    line-height: 1.75;
    margin: 0;
}

.ks-info-text strong { color: #92400e; }

/* Footer */
#ks-modal-footer {
    padding: 0 32px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.ks-date-note {
    font-size: 11px;
    color: #9ca3af;
    font-style: italic;
}

#ks-dismiss-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #1a3a8c, #2563eb);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 28px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
    letter-spacing: 0.2px;
    white-space: nowrap;
}

#ks-dismiss-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(37, 99, 235, 0.5);
    background: linear-gradient(135deg, #1e40af, #3b82f6);
}

#ks-dismiss-btn:active {
    transform: translateY(0);
}

/* Divider */
.ks-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #e5e7eb, transparent);
    margin: 0 32px 24px;
}

/* Animations */
@keyframes ksFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes ksSlideUp {
    from { transform: translateY(40px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
</style>

<div id="ks-modal-overlay">
    <div id="ks-modal-card">

        {{-- ── Hero Header ── --}}
        <div id="ks-modal-hero">
            <div class="ks-hero-badge">
                ✨ Pemberitahuan Tindak Lanjut
            </div>
            <div class="ks-hero-icon-wrap">🎓</div>
            <h2 class="ks-hero-title">{{ \App\Models\Setting::getValue('ks_popup_title', 'Selamat, Pelatihan Selesai!') }}</h2>
            <p class="ks-hero-sub">{{ \App\Models\Setting::getValue('ks_popup_subtitle', 'Terima kasih atas dedikasi dan semangat belajar Anda.') }}</p>
        </div>

        {{-- ── Body ── --}}
        <div id="ks-modal-body">
            <p class="ks-desc">
                {{ \App\Models\Setting::getValue('ks_popup_desc', 'Anda telah berhasil menyelesaikan seluruh rangkaian program pelatihan dengan baik. Pencapaian ini adalah kontribusi nyata bagi pengembangan kompetensi di instansi kita.') }}
            </p>

            <div class="ks-info-box">
                <span class="ks-info-icon">📌</span>
                <p class="ks-info-text">
                    {!! \App\Models\Setting::getValue('ks_popup_info', '<strong>Tindak Lanjut Wajib:</strong> Sebagai bagian dari kewajiban peserta, Saudara diharapkan bersedia menjadi narasumber pada kegiatan <strong>Knowledge Sharing</strong> paling lambat dalam waktu <strong>H+14 hari kerja</strong> setelah pelatihan berakhir.') !!}
                </p>
            </div>
        </div>

        <div class="ks-divider"></div>

        {{-- ── Footer ── --}}
        <div id="ks-modal-footer">
            <span class="ks-date-note">Pastikan Anda menghubungi pengelola diklat.</span>
            <button id="ks-dismiss-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Saya Mengerti
            </button>
        </div>

    </div>
</div>

<script>
(function() {
    const completedIds = @json($completedTrainingIds);
    const storageKey  = 'ks_dismissed_ids_v3'; // Changed to v3 to reset state for testing
    const dismissed   = JSON.parse(localStorage.getItem(storageKey) || '[]');

    const hasNew = completedIds.some(id => !dismissed.includes(id));

    if (hasNew) {
        const overlay = document.getElementById('ks-modal-overlay');
        overlay.style.display = 'flex';
        overlay.style.animation = 'ksFadeIn 0.3s ease forwards';

        // Re-trigger card animation
        const card = document.getElementById('ks-modal-card');
        card.style.animation = 'none';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.style.animation = 'ksSlideUp 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
            });
        });
    }

    document.getElementById('ks-dismiss-btn').addEventListener('click', function() {
        const updated = [...new Set([...dismissed, ...completedIds])];
        localStorage.setItem(storageKey, JSON.stringify(updated));

        const overlay = document.getElementById('ks-modal-overlay');
        overlay.style.transition = 'opacity 0.3s ease';
        overlay.style.opacity = '0';
        setTimeout(() => { overlay.style.display = 'none'; overlay.style.opacity = ''; }, 300);
    });

    // Close on overlay click (outside card)
    document.getElementById('ks-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            document.getElementById('ks-dismiss-btn').click();
        }
    });
})();
</script>
@endif




@forelse($registrations as $reg)
@php
    $isEdu  = in_array($reg->trainingProgram->kategori, ['pendidikan', 'beasiswa_external']);
    $isTugasBelajar = $reg->trainingProgram->kategori === 'pendidikan';
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
        } elseif ($selPassed && $reg->loa_path) {
            $s4 = 'done';
            $s5 = ($reg->final_report_path) ? 'done' : 'active';
        } elseif ($selPassed && !$reg->loa_path) {
            $s4 = 'active'; // LOA di-reset admin
            $s5 = 'waiting';
        } else {
            $s4 = ($s3 === 'done') ? 'active' : 'waiting';
            $s5 = 'waiting';
        }
    } else {
        // Pendidikan (Internal) atau Beasiswa External
        if ($isTugasBelajar) {
            // Beasiswa Pendidikan Internal: 4 steps
            // S4 = Laporan Akhir (skipping seleksi akhir)
            $s4 = ($s3 === 'done') ? (($reg->is_education_completed) ? 'done' : 'active') : 'waiting';
            $s5 = 'waiting'; // not used
        } else {
            // Beasiswa External: 5 steps
            $hasSelResult = !is_null($reg->selection_final_result);
            $selPassed    = $reg->selection_final_result === 'passed';
            $selNotPassed = $reg->selection_final_result === 'not_passed';

            if ($selNotPassed) {
                $s4 = 'failed';
                $s5 = 'failed';
            } elseif ($selPassed && $reg->log_path && $reg->loa_path) {
                $s4 = 'done';
                $s5 = ($reg->is_education_completed) ? 'done' : 'active';
            } elseif ($selPassed && (!$reg->log_path || !$reg->loa_path)) {
                $s4 = 'active'; // LOG / LOA di-reset admin
                $s5 = 'waiting';
            } else {
                $s4 = ($s3 === 'done') ? 'active' : 'waiting';
                $s5 = 'waiting';
            }
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
                <br>
                <span style="color: #1a3a6c; font-weight: 600; margin-top: 4px; display: inline-block;">📚 Topik: {{ $reg->programTopic->name }}</span>
                @if($reg->programTopic->execution_start_date && $reg->programTopic->execution_end_date)
                    <br><span style="color: #276749; margin-top: 2px; display: inline-block;">🗓️ Pelaksanaan: {{ $reg->programTopic->execution_start_date->format('d M Y') }} – {{ $reg->programTopic->execution_end_date->format('d M Y') }}</span>
                @endif
                @else
                @if($reg->trainingProgram->execution_start_date && $reg->trainingProgram->execution_end_date)
                    <br><span style="color: #276749; margin-top: 4px; display: inline-block;">🗓️ Pelaksanaan: {{ $reg->trainingProgram->execution_start_date->format('d M Y') }} – {{ $reg->trainingProgram->execution_end_date->format('d M Y') }}</span>
                @endif
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
            <div class="step-label {{ $s3 }}">{{ $isTugasBelajar ? 'Surat Tugas Belajar' : 'Surat Rekomendasi' }}</div>
        </div>
        
        @if($isTugasBelajar)
            <div class="step">
                <div class="step-dot {{ $s4 }}">{{ $s4 === 'done' ? '✓' : '4' }}</div>
                <div class="step-label {{ $s4 }}">Laporan Akhir</div>
            </div>
        @else
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
        @endif
    </div>

    {{-- Catatan Verifikasi --}}
    @if($reg->verification_notes)
        <div class="alert alert-info" style="font-size:13px;">
            💬 <strong>Catatan Admin:</strong> {{ $reg->verification_notes }}
        </div>
    @endif

    {{-- Dokumen yang Diunggah Pelamar --}}
    @if($reg->requirementDocuments && $reg->requirementDocuments->count() > 0)
        <div style="margin-bottom: 16px; padding: 12px; background: #f8faff; border: 1px solid #dce8ff; border-radius: 8px;">
            <div style="font-size: 13px; font-weight: 700; color: #1a3a6c; margin-bottom: 8px;">📂 Dokumen Persyaratan yang Anda Unggah:</div>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #555;">
                @foreach($reg->requirementDocuments as $doc)
                    <li style="margin-bottom: 4px;">
                        <strong>{{ $doc->requirement->name ?? 'Dokumen' }}</strong> - 
                        <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Lihat File ⬇️</a>
                    </li>
                @endforeach
            </ul>
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
                <div class="action-title">✅ Lolos Verifikasi — Menunggu {{ $isTugasBelajar ? 'Surat Tugas Belajar' : 'Surat Rekomendasi' }}</div>
                <p style="font-size:13px;color:#666;">Selamat! Anda lolos verifikasi. Admin sedang menyiapkan {{ $isTugasBelajar ? 'surat tugas belajar' : 'surat rekomendasi' }} untuk Anda.</p>
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

                @elseif(!$hasSelResult || ($selPassed && !$reg->loa_path))
                    {{-- Belum mengisi Hasil Seleksi Akhir atau LOA dikembalikan/di-reset --}}
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
                            <p style="font-size:13px;color:#666;margin-bottom:10px;">
                                @if($hasSelResult)
                                    <span style="color:#dc3545;font-weight:600;">⚠️ Dokumen LOA Anda dikembalikan/di-reset oleh admin. Silakan unggah ulang.</span>
                                @else
                                    Silakan unggah LOA jika Anda lolos, atau dokumen bukti jika tidak lolos seleksi pelatihan.
                                @endif
                            </p>
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
            @elseif($isTugasBelajar)
                {{-- BEASISWA PENDIDIKAN INTERNAL (4 Steps: Pendaftaran, Verifikasi, Surat Tugas Belajar, Laporan Akhir) --}}
                @if(!$reg->is_education_completed)
                    {{-- Belum Selesai → Laporan Akhir (Langsung ke Step 4) --}}
                    <div class="action-area" style="border-color:#fff3cd;background:#fffaf0;">
                        <div class="action-title" style="color:#856404;">📖 Tahap 4: Laporan Akhir Pendidikan</div>
                        <p style="font-size:13px;color:#666;margin-bottom:12px;">Selamat! Surat Tugas Belajar Anda telah terbit. Pastikan Anda mengunggah laporan hasil belajar (rapot) secara berkala dan unggah berkas akhir untuk menyelesaikan program.</p>
                        
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

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-info btn-sm" style="background:#17a2b8; border-color:#17a2b8; color:white;">⬇️ Unduh Surat Tugas Belajar</a>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/tambah-aktivitas') }}" class="btn btn-warning btn-sm">➕ Tambah Laporan Semester</a>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/upload-akhir') }}" class="btn btn-success btn-sm">🎓 Selesaikan Pendidikan</a>
                        </div>
                    </div>
                @else
                    {{-- Selesai --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">🏁 Program Beasiswa Selesai</div>
                        <p style="font-size:13px;color:#666;">Selamat! Anda telah menyelesaikan seluruh rangkaian program pendidikan ini.</p>
                        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px; flex-wrap: wrap;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-success btn-sm">⬇️ Unduh Surat Tugas Belajar</a>
                        </div>
                        @if($reg->final_doc_path)
                             <a href="{{ asset('storage/' . $reg->final_doc_path) }}" target="_blank" style="font-size: 13px; color: var(--primary-color);">Lihat Berkas Akhir / Ijazah ⬇️</a>
                        @endif
                    </div>
                @endif
            @else
                {{-- BEASISWA EXTERNAL (5 Steps) --}}
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

                @elseif(!$hasSelResult || ($selPassed && (!$reg->log_path || !$reg->loa_path)))
                    {{-- Belum mengisi Hasil Seleksi Akhir atau LOG/LOA dikembalikan/di-reset --}}
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
                            <p style="font-size:13px;color:#666;margin-bottom:10px;">
                                @if($hasSelResult)
                                    <span style="color:#dc3545;font-weight:600;">⚠️ Dokumen LOG / LOA Anda dikembalikan/di-reset oleh admin. Silakan unggah ulang.</span>
                                @else
                                    Silakan unggah dokumen hasil seleksi akhir beasiswa Anda (wajib diisi baik lolos maupun tidak lolos).
                                @endif
                            </p>
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

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ url('/registrasi/' . $reg->id . '/surat-pengantar') }}" class="btn btn-info btn-sm" style="background:#17a2b8; border-color:#17a2b8; color:white;">⬇️ Unduh Surat Rekomendasi</a>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/tambah-aktivitas') }}" class="btn btn-warning btn-sm">➕ Tambah Laporan Semester</a>
                            <a href="{{ url('/pendidikan/' . $reg->id . '/upload-akhir') }}" class="btn btn-success btn-sm">🎓 Selesaikan Pendidikan</a>
                        </div>
                    </div>

                @else
                    {{-- Selesai --}}
                    <div class="action-area" style="border-color:#d4edda;background:#f0fff4;">
                        <div class="action-title" style="color:#28a745;">🏁 Program Beasiswa Selesai</div>
                        <p style="font-size:13px;color:#666;">Selamat! Anda telah menyelesaikan seluruh rangkaian program beasiswa external ini.</p>
                        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px; flex-wrap: wrap;">
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
