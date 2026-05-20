@extends('layouts.app')
@section('title', 'Daftar ke Perusahaan - Simdiklat')

@section('content')

<h2 style="color:#1a3a6c;margin-bottom:6px;">🏢 Pendaftaran ke Perusahaan Tujuan</h2>
<p style="color:#666;font-size:13px;margin-bottom:24px;">
    Program: <strong>{{ $registration->trainingProgram->title }}</strong>
</p>

<div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
    <div class="alert alert-info">
        ℹ️ Isi form ini dengan data perusahaan/instansi yang Anda tuju setelah mendapat surat rekomendasi. Pastikan data sudah benar.
    </div>

    <form method="POST" action="{{ url('/registrasi/' . $registration->id . '/daftar-perusahaan') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:6px;">Nama Perusahaan / Instansi *</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required
                placeholder="Contoh: PT. Pertamina (Persero)">
            @error('company_name')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:6px;">Alamat Perusahaan *</label>
            <textarea name="company_address" class="form-control" rows="3" required
                placeholder="Jl. Contoh No. 1, Kota, Provinsi">{{ old('company_address') }}</textarea>
            @error('company_address')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:6px;">Posisi / Jabatan yang Dilamar *</label>
            <input type="text" name="applied_position" class="form-control" value="{{ old('applied_position') }}" required
                placeholder="Contoh: Staff Pengembangan SDM">
            @error('applied_position')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <hr style="border:0; border-top:1px solid #eee; margin:24px 0;">

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:8px;">Hasil Seleksi dari Perusahaan *</label>
            <div style="display:flex;gap:16px;">
                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #ddd;border-radius:8px;cursor:pointer;flex:1;">
                    <input type="radio" name="selection_result" value="passed" style="accent-color:#28a745;width:18px;height:18px;" required {{ old('selection_result') === 'passed' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700;color:#28a745;">✅ Lolos Seleksi</div>
                        <div style="font-size:12px;color:#888;">Saya diterima</div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #ddd;border-radius:8px;cursor:pointer;flex:1;">
                    <input type="radio" name="selection_result" value="not_passed" style="accent-color:#dc3545;width:18px;height:18px;" {{ old('selection_result') === 'not_passed' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700;color:#dc3545;">❌ Tidak Lolos</div>
                        <div style="font-size:12px;color:#888;">Saya tidak diterima</div>
                    </div>
                </label>
            </div>
            @error('selection_result')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:6px;">Upload Dokumen / Surat Hasil Seleksi *</label>
            <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
            <div style="font-size:12px;color:#888;margin-top:5px;">Format: PDF, JPG, PNG | Maksimal 5MB</div>
            @error('document')<div style="color:#dc3545;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:6px;">Catatan Tambahan (opsional)</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Misal: Diterima sebagai MT">{{ old('notes') }}</textarea>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">📤 Simpan & Upload Hasil Seleksi</button>
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
