@extends('layouts.app')

@section('title', 'Upload Laporan Akhir - Simdiklat')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/dashboard') }}" style="color: #666; text-decoration: none;">&larr; Kembali ke Dashboard</a>
    </div>

    <div
        style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; padding: 30px;">
        <h2 style="color: var(--primary-color); margin-bottom: 5px;">📤 Upload Laporan Akhir</h2>
        <p style="color: #666; font-size: 14px; margin-bottom: 25px;">
            Program: <strong>{{ $registration->trainingProgram->title }}</strong>
        </p>

        <form action="{{ url('/pelatihan/' . $registration->id . '/upload-laporan') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            @if($registration->trainingProgram->template_report_path)
                <div
                    style="background: #eef7ff; border: 1px solid #b3d4ff; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px;">
                    <span style="font-size: 28px;">📄</span>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #1a3a6c; font-size: 14px; margin-bottom: 4px;">Template Laporan
                            Akhir Tersedia</div>
                        <div style="font-size: 12px; color: #555;">Unduh template dari admin sebagai panduan mengisi laporan
                            akhir Anda.</div>
                    </div>
                    <a href="{{ asset('storage/' . $registration->trainingProgram->template_report_path) }}" download
                        style="background: #1a3a6c; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; white-space: nowrap;">
                        ⬇️ Unduh Template
                    </a>
                </div>
            @endif


            <div style="margin-bottom: 20px;">
                <label for="final_report" style="display: block; font-weight: 700; margin-bottom: 8px; color: #333;">File
                    Laporan Akhir (PDF/Word)</label>
                <input type="file" name="final_report" id="final_report" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                <small style="display: block; color: #888; margin-top: 5px;">Format: PDF, DOC, DOCX. Maksimal 10MB.</small>
                @error('final_report')
                    <div style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div
                style="background: #fff8f1; border-left: 4px solid #f6ad55; padding: 15px; border-radius: 4px; margin-bottom: 16px;">
                <p style="margin: 0; font-size: 13px; color: #744210; line-height: 1.5;">
                    <strong>Catatan:</strong> Dengan mengunggah laporan ini, Anda menyatakan bahwa seluruh rangkaian
                    kegiatan pelatihan telah selesai dilaksanakan.
                </p>
            </div>

            <div
                style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                <p style="margin: 0; font-size: 13px; color: #856404; line-height: 1.6;">
                    ⚠️ <strong>Penting:</strong> Mohon agar laporan akhir kegiatan dapat segera diunggah. Apabila laporan
                    akhir tidak diunggah, maka seluruh pegawai pada unit kerja Bapak/Ibu <strong>tidak dapat
                        didaftarkan</strong> untuk mengikuti pelatihan lain yang ditawarkan.
                </p>
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; padding: 12px; font-weight: bold; border-radius: 8px;">
                🚀 Unggah Laporan & Selesaikan Tahapan
            </button>
        </form>
    </div>
@endsection