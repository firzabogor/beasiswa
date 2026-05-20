@extends('layouts.app')

@section('title', 'Tambah Laporan Semester - Simdiklat')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 10px; color: var(--primary-color);">✍️ Laporan Aktivitas Belajar</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 24px;">
        Unggah nilai atau rapot hasil belajar Anda untuk semester ini.
    </p>

    <form action="{{ url('/pendidikan/' . $registration->id . '/tambah-aktivitas') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($registration->trainingProgram->template_report_2_path)
        <div style="background: #eef7ff; border: 1px solid #b3d4ff; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px;">
            <span style="font-size: 28px;">📄</span>
            <div style="flex: 1;">
                <div style="font-weight: 700; color: #1a3a6c; font-size: 14px; margin-bottom: 4px;">Template Laporan Semester Tersedia</div>
                <div style="font-size: 12px; color: #555;">Unduh template dari admin sebagai panduan mengisi laporan semester Anda.</div>
            </div>
            <a href="{{ asset('storage/' . $registration->trainingProgram->template_report_2_path) }}"
               download
               style="background: #1a3a6c; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; white-space: nowrap;">
                ⬇️ Unduh Template
            </a>
        </div>
        @endif

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="font-weight: 700;">Semester</label>
            <input type="text" name="semester_name" class="form-control" value="{{ $nextSemesterName }}" readonly style="background: #f8f9fa; cursor: not-allowed; font-weight: 600;">
            <small style="color: #666; font-size: 13px;">Laporan ini akan disimpan otomatis sebagai {{ $nextSemesterName }}</small>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="font-weight: 700;">File Laporan Semester (PDF)</label>
            <input type="file" name="document" class="form-control" required accept=".pdf">
            <small style="color: #888;">Maks. 5MB. Catatan: boleh ditambahkan data dukung berupa KHS, KRS, dsb.</small>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="font-weight: 700;">Catatan Tambahan (Opsional)</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika ada..."></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; padding: 12px;">Batal</a>
            <button type="submit" class="btn btn-primary" style="flex: 2;">Simpan Aktivitas</button>
        </div>
    </form>
</div>
@endsection
