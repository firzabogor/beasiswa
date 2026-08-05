@extends('layouts.admin')

@section('title', 'Proses Verifikasi - Admin')
@section('page-title', 'Proses Verifikasi Pendaftaran')

@section('content')

<div class="page-header">
    <h1>🔍 Verifikasi Pendaftaran</h1>
    <p><a href="{{ url('/admin/verifikasi') }}" style="color:#1a3a6c;">← Kembali ke Daftar Verifikasi</a></p>
</div>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:20px;">

    {{-- Info Pendaftar --}}
    <div>
        <div class="card">
            <div class="card-header"><h3>👤 Data Peserta</h3></div>
            <table style="font-size:13.5px;width:100%;">
                <tr><td style="color:#888;padding:6px 0;width:120px;">Nama</td><td><strong>{{ $registration->user->name }}</strong></td></tr>
                <tr><td style="color:#888;padding:6px 0;">NIP</td><td>{{ $registration->user->nip }}</td></tr>
                <tr><td style="color:#888;padding:6px 0;">Jabatan</td><td>{{ $registration->user->position }}</td></tr>
                <tr><td style="color:#888;padding:6px 0;">Email</td><td>{{ $registration->user->email }}</td></tr>
                <tr><td style="color:#888;padding:6px 0;">Tanggal Daftar</td><td>{{ $registration->created_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-header"><h3>📚 Program Dipilih</h3></div>
            <table style="font-size:13.5px;width:100%;">
                <tr><td style="color:#888;padding:6px 0;width:120px;">Program</td><td><strong>{{ $registration->trainingProgram->title }}</strong></td></tr>
                <tr><td style="color:#888;padding:6px 0;">Kategori</td><td>{{ ucfirst($registration->trainingProgram->kategori) }}</td></tr>
                @if($registration->programTopic)
                <tr><td style="color:#888;padding:6px 0;">Topik</td><td><strong>{{ $registration->programTopic->name }}</strong></td></tr>
                @endif
                <tr><td style="color:#888;padding:6px 0;">Lokasi</td><td>{{ $registration->trainingProgram->location }}</td></tr>
                <tr><td style="color:#888;padding:6px 0;">Tanggal</td><td>{{ $registration->trainingProgram->start_date->format('d M Y') }}</td></tr>
            </table>
        </div>

        {{-- Status saat ini --}}
        <div class="card">
            <div class="card-header"><h3>📊 Status Saat Ini</h3></div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;">
                <span class="badge badge-{{ $registration->verification_status }}" style="font-size:13px;padding:6px 14px;">
                    {{ $registration->verification_status === 'pending' ? '⏳ Menunggu Verifikasi' : ($registration->verification_status === 'verified' ? '✅ Terverifikasi' : '❌ Ditolak') }}
                </span>
            </div>
            @if($registration->verification_notes)
                <div class="alert alert-info" style="margin-top:8px;">
                    <strong>Catatan:</strong> {{ $registration->verification_notes }}
                </div>
            @endif
            @if($registration->invitation_letter_path)
                <div class="alert alert-success" style="margin-top:8px;">
                    ✅ {{ $registration->trainingProgram->kategori === 'pendidikan' ? 'Surat Tugas Belajar' : 'Surat Rekomendasi' }} sudah diupload pada {{ $registration->invitation_letter_uploaded_at?->format('d M Y H:i') }}
                </div>
            @endif
        </div>

        {{-- Berkas yang Diunggah --}}
        <div class="card">
            <div class="card-header"><h3>📂 Syarat Pendaftaran</h3></div>
            <div style="padding: 5px 0;">
                @forelse($registration->requirementDocuments as $doc)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f2f5;">
                        <div style="font-size: 13px;">
                            <div style="font-weight: 700; color: #1a3a6c;">{{ $doc->requirement->name }}</div>
                            <div style="font-size: 11px; color: #888;">Diunggah: {{ $doc->created_at->format('d M Y') }}</div>
                        </div>
                        <a href="{{ file_url($doc->document_path) }}" target="_blank" class="btn btn-sm btn-secondary" style="padding: 4px 10px; font-size: 11px; display: flex; align-items: center; gap: 5px; text-decoration:none;">
                            👁️ Lihat
                        </a>
                    </div>
                @empty
                    <div style="text-align: center; color: #aaa; padding: 20px; font-size: 13px;">
                        Tidak ada berkas syarat yang diunggah.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Form Verifikasi --}}
    <div class="card">
        <div class="card-header"><h3>⚖️ Form Verifikasi</h3></div>

        <form method="POST" action="{{ url('/admin/verifikasi/' . $registration->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Keputusan Verifikasi *</label>
                <div style="display:flex;gap:16px;margin-top:8px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all 0.2s;" id="lbl-verified">
                        <input type="radio" name="verification_status" value="verified" id="r-verified" required
                            {{ old('verification_status') === 'verified' ? 'checked' : '' }}
                            style="accent-color:#28a745;width:18px;height:18px;">
                        <div>
                            <div style="font-weight:700;color:#28a745;">✅ Lolos Verifikasi</div>
                            <div style="font-size:12px;color:#888;">Sertakan {{ $registration->trainingProgram->kategori === 'pendidikan' ? 'surat tugas belajar' : 'surat rekomendasi' }}</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all 0.2s;" id="lbl-rejected">
                        <input type="radio" name="verification_status" value="rejected" id="r-rejected"
                            {{ old('verification_status') === 'rejected' ? 'checked' : '' }}
                            style="accent-color:#dc3545;width:18px;height:18px;">
                        <div>
                            <div style="font-weight:700;color:#dc3545;">❌ Tidak Lolos</div>
                            <div style="font-size:12px;color:#888;">Proses pendaftaran selesai</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group" id="letter-section" style="{{ old('verification_status') === 'rejected' ? 'display:none' : '' }}">
                <label class="form-label" for="invitation_letter">
                    📎 Upload {{ $registration->trainingProgram->kategori === 'pendidikan' ? 'Surat Tugas Belajar' : 'Surat Rekomendasi' }} (PDF) <span style="color:#dc3545;">*</span>
                </label>
                <input type="file" id="invitation_letter" name="invitation_letter" class="form-control" accept=".pdf"
                    @if(!$registration->invitation_letter_path) required @endif>
                <div style="font-size:12px;color:#888;margin-top:6px;">Format: PDF | Maks. 10MB</div>
                @if($registration->invitation_letter_path)
                    <div class="alert alert-info" style="margin-top:8px;">
                        ℹ️ {{ $registration->trainingProgram->kategori === 'pendidikan' ? 'Surat Tugas Belajar' : 'Surat Rekomendasi' }} sudah ada. Upload baru untuk mengganti.
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label" for="verification_notes">Catatan untuk Peserta (opsional)</label>
                <textarea id="verification_notes" name="verification_notes" class="form-control" rows="3"
                    placeholder="Catatan akan dilihat oleh peserta di dashboard mereka...">{{ old('verification_notes', $registration->verification_notes) }}</textarea>
            </div>

            <div class="alert alert-warning">
                ⚠️ <strong>Perhatian:</strong> Keputusan yang telah dikirim akan langsung terlihat oleh peserta. Pastikan data sudah benar.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">📨 Kirim {{ $registration->trainingProgram->kategori === 'pendidikan' ? 'Surat Tugas Belajar' : 'Rekomendasi' }}</button>
                <a href="{{ url('/admin/verifikasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const rVerified  = document.getElementById('r-verified');
    const rRejected  = document.getElementById('r-rejected');
    const lblVerified = document.getElementById('lbl-verified');
    const lblRejected = document.getElementById('lbl-rejected');
    const letterSection = document.getElementById('letter-section');
    const letterInput   = document.getElementById('invitation_letter');

    function updateUI() {
        if (rVerified.checked) {
            letterSection.style.display = '';
            lblVerified.style.borderColor = '#28a745';
            lblRejected.style.borderColor = '#ddd';
            @if(!$registration->invitation_letter_path)
            letterInput.required = true;
            @endif
        } else {
            letterSection.style.display = 'none';
            lblRejected.style.borderColor = '#dc3545';
            lblVerified.style.borderColor = '#ddd';
            letterInput.required = false;
        }
    }

    rVerified.addEventListener('change', updateUI);
    rRejected.addEventListener('change', updateUI);
    updateUI();
</script>
@endsection
