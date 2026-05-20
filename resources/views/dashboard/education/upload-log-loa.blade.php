@extends('layouts.app')

@section('title', 'Hasil Seleksi Akhir - Simdiklat')

@section('content')
<div style="max-width: 640px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/dashboard') }}" style="color: #666; text-decoration: none;">← Kembali ke Dashboard</a>
    </div>

    <h2 style="margin-bottom: 10px; color: var(--primary-color);">🎓 Hasil Seleksi Akhir Beasiswa</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 24px;">
        Program: <strong>{{ $registration->trainingProgram->title }}</strong><br>
        Unggah dokumen hasil seleksi akhir beasiswa dan tentukan hasilnya. Upload wajib dilakukan baik jika Anda <strong>lolos</strong> maupun <strong>tidak lolos</strong>.
    </p>

    <form action="{{ url('/pendidikan/' . $registration->id . '/upload-log-loa') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hasil Seleksi --}}
        <div style="margin-bottom: 20px;">
            <label style="display:block;font-size:13px;font-weight:700;color:#444;margin-bottom:10px;">Hasil Seleksi Akhir Beasiswa *</label>
            <div style="display:flex;gap:14px;">
                <label style="display:flex;align-items:center;gap:10px;padding:14px 20px;border:2px solid #ddd;border-radius:10px;cursor:pointer;flex:1;transition:border 0.2s;" id="lbl-passed"
                    onclick="this.style.borderColor='#28a745';document.getElementById('lbl-not_passed').style.borderColor='#ddd';">
                    <input type="radio" name="selection_final_result" value="passed" style="accent-color:#28a745;width:18px;height:18px;" required>
                    <div>
                        <div style="font-weight:700;color:#28a745;">✅ Lolos Seleksi</div>
                        <div style="font-size:12px;color:#888;">Saya diterima / lolos seleksi beasiswa</div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:10px;padding:14px 20px;border:2px solid #ddd;border-radius:10px;cursor:pointer;flex:1;transition:border 0.2s;" id="lbl-not_passed"
                    onclick="this.style.borderColor='#dc3545';document.getElementById('lbl-passed').style.borderColor='#ddd';">
                    <input type="radio" name="selection_final_result" value="not_passed" style="accent-color:#dc3545;width:18px;height:18px;">
                    <div>
                        <div style="font-weight:700;color:#dc3545;">❌ Tidak Lolos</div>
                        <div style="font-size:12px;color:#888;">Saya tidak lolos seleksi beasiswa</div>
                    </div>
                </label>
            </div>
            @error('selection_final_result')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        {{-- Section Tidak Lolos: Upload Bukti --}}
        <div id="not-passed-info" style="display:none; background:#fff8f8; border:1px solid #f8d7da; border-radius:10px; padding:16px; margin-bottom:20px;">
            <div style="font-weight:700;font-size:14px;color:#dc3545;margin-bottom:6px;">ℹ️ Informasi</div>
            <p style="font-size:13px;color:#666;margin-bottom:14px;">Jika Anda tidak lolos seleksi, proses akan berakhir di tahapan ini setelah mengunggah dokumen bukti. Anda tidak perlu melanjutkan ke tahapan berikutnya.</p>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-weight: 700;">Dokumen Bukti Hasil Seleksi (PDF) *</label>
                <input type="file" name="selection_final_doc" id="selection_final_doc_input" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small style="color: #888;">Format: PDF, JPG, PNG (Maks. 5MB). Wajib diupload sebagai bukti tidak lolos.</small>
                @error('selection_final_doc')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Section Lolos: Upload LOG & LOA --}}
        <div id="log-loa-section" style="display:none; background:#f0fff4; border:1px solid #c6e3cc; border-radius:10px; padding:20px; margin-bottom:20px;">
            <div style="font-weight:700;font-size:14px;color:#28a745;margin-bottom:12px;">📎 Berkas LOG & LOA (Khusus Lolos Seleksi)</div>
            <p style="font-size:12px;color:#666;margin-bottom:14px;">Jika Anda lolos seleksi, silakan unggah berkas LOG dan LOA Anda sebagai kelengkapan administrasi peserta.</p>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600;">File LOG (Letter of Guarantee) *</label>
                <input type="file" name="log_file" class="form-control" accept=".pdf" id="log_file_input">
                <small style="color: #888;">Format: PDF (Maks. 5MB)</small>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-weight: 600;">File LOA (Letter of Acceptance) *</label>
                <input type="file" name="loa_file" class="form-control" accept=".pdf" id="loa_file_input">
                <small style="color: #888;">Format: PDF (Maks. 5MB)</small>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; padding: 12px;">Batal</a>
            <button type="submit" class="btn btn-primary" style="flex: 2;">📤 Submit Hasil Seleksi</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="selection_final_result"]');
    const logLoaSection = document.getElementById('log-loa-section');
    const notPassedInfo = document.getElementById('not-passed-info');
    
    const logInput = document.getElementById('log_file_input');
    const loaInput = document.getElementById('loa_file_input');
    const docInput = document.getElementById('selection_final_doc_input');

    radios.forEach(r => r.addEventListener('change', function() {
        if (this.value === 'passed') {
            // Show LOG & LOA, Make required
            logLoaSection.style.display = 'block';
            logInput.required = true;
            loaInput.required = true;
            
            // Hide selection_final_doc, Remove required
            notPassedInfo.style.display = 'none';
            docInput.required = false;
            docInput.value = '';
        } else {
            // Hide LOG & LOA, Remove required
            logLoaSection.style.display = 'none';
            logInput.required = false;
            loaInput.required = false;
            logInput.value = '';
            loaInput.value = '';
            
            // Show selection_final_doc, Make required
            notPassedInfo.style.display = 'block';
            docInput.required = true;
        }
    }));
});
</script>
@endsection
