@extends('layouts.app')
@section('title', 'Upload Surat Hasil Seleksi - Simdiklat')

@section('content')

<h2 style="color:#1a3a6c;margin-bottom:6px;">📎 Upload Surat Hasil Seleksi</h2>
<p style="color:#666;font-size:13px;margin-bottom:24px;">
    Perusahaan: <strong>{{ $application->company_name }}</strong> — {{ $application->applied_position }}
</p>

<div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
    <div class="alert alert-info">
        ℹ️ Upload surat hasil seleksi dari perusahaan yang Anda tuju. Upload wajib dilakukan baik jika hasil seleksi lolos maupun tidak lolos.
    </div>

    <form method="POST" action="{{ url('/aplikasi/' . $application->id . '/upload-seleksi') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:8px;">Hasil Seleksi dari Perusahaan *</label>
            <div style="display:flex;gap:16px;">
                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #ddd;border-radius:8px;cursor:pointer;flex:1;" id="lbl-passed">
                    <input type="radio" name="selection_result" value="passed" style="accent-color:#28a745;width:18px;height:18px;" required>
                    <div>
                        <div style="font-weight:700;color:#28a745;">✅ Lolos Seleksi</div>
                        <div style="font-size:12px;color:#888;">Saya diterima oleh perusahaan</div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #ddd;border-radius:8px;cursor:pointer;flex:1;" id="lbl-not_passed">
                    <input type="radio" name="selection_result" value="not_passed" style="accent-color:#dc3545;width:18px;height:18px;">
                    <div>
                        <div style="font-weight:700;color:#dc3545;">❌ Tidak Lolos Seleksi</div>
                        <div style="font-size:12px;color:#888;">Saya tidak diterima oleh perusahaan</div>
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
            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan jika ada...">{{ old('notes') }}</textarea>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">📤 Upload Dokumen</button>
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
