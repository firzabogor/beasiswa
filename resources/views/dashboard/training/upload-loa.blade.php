@extends('layouts.app')

@section('title', 'Hasil Seleksi Akhir Pelatihan - Simdiklat')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h2 style="margin-top: 0; color: #1a3a6c; font-size: 22px;">🎓 Hasil Seleksi Akhir Pelatihan</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 24px;">Silakan isi form di bawah ini sesuai dengan hasil pengumuman akhir dari panitia beasiswa/pelatihan.</p>

    @if($errors->any())
        <div style="background: #fff5f5; border-left: 4px solid #f56565; padding: 15px; margin-bottom: 20px;">
            <ul style="margin: 0; color: #c53030; font-size: 14px; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/pelatihan/' . $registration->id . '/upload-loa') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 700; color: #1a3a6c; margin-bottom: 12px;">Pilih Hasil Seleksi Akhir *</label>
            <div style="display: flex; gap: 15px;">
                <label style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s;" onmouseover="this.style.borderColor='#28a745'" onmouseout="this.style.borderColor='#ddd'">
                    <input type="radio" name="selection_final_result" value="passed" required style="accent-color: #28a745;">
                    <div style="font-weight: 700; color: #28a745; margin-top: 5px;">🎉 LOLOS</div>
                </label>
                <label style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s;" onmouseover="this.style.borderColor='#dc3545'" onmouseout="this.style.borderColor='#ddd'">
                    <input type="radio" name="selection_final_result" value="not_passed" required style="accent-color: #dc3545;">
                    <div style="font-weight: 700; color: #dc3545; margin-top: 5px;">❌ TIDAK LOLOS</div>
                </label>
            </div>
        </div>

        {{-- Wajib jika tidak lolos --}}
        <div id="not-passed-info" style="display: none; background: #fff8f8; border: 1px solid #ffcccc; padding: 15px; border-radius: 8px; margin-bottom: 24px;">
            <label style="font-weight: 700; color: #dc3545; display: block; margin-bottom: 8px;">Upload Bukti Seleksi (Wajib jika tidak lolos)</label>
            <p style="font-size: 12px; color: #666; margin-top: 0;">Silakan unggah dokumen/screenshot pengumuman yang menyatakan Anda tidak lolos sebagai bukti administrasi.</p>
            <input type="file" name="selection_final_doc" id="selection_final_doc_input" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            <small style="color: #888;">Format: PDF/JPG/PNG (Maks. 5MB)</small>
        </div>

        {{-- Wajib jika lolos --}}
        <div id="loa-section" style="display: none; background: #f0f7ff; border: 1px solid #cce5ff; padding: 15px; border-radius: 8px; margin-bottom: 24px;">
            <p style="font-size: 13px; color: #1a3a6c; margin-top: 0;">Selamat Anda lolos seleksi akhir! Silakan unggah dokumen wajib berikut:</p>
            
            <div style="margin-bottom: 0;">
                <label style="font-weight: 700; color: #1a3a6c;">Surat Diterima (LOA) *</label>
                <input type="file" name="loa_file" id="loa_file_input" class="form-control" accept=".pdf" style="margin-top: 5px;">
                <small style="color: #888;">Format: PDF (Maks. 5MB)</small>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; padding: 12px;">Batal</a>
            <button type="submit" class="btn btn-primary" style="flex: 2;">💾 Submit Hasil Seleksi</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="selection_final_result"]');
    const loaSection = document.getElementById('loa-section');
    const notPassedInfo = document.getElementById('not-passed-info');
    
    const loaInput = document.getElementById('loa_file_input');
    const docInput = document.getElementById('selection_final_doc_input');

    radios.forEach(r => r.addEventListener('change', function() {
        if (this.value === 'passed') {
            // Show LOA, Make required
            loaSection.style.display = 'block';
            loaInput.required = true;
            
            // Hide selection_final_doc, Remove required
            notPassedInfo.style.display = 'none';
            docInput.required = false;
            docInput.value = '';
        } else {
            // Hide LOA, Remove required
            loaSection.style.display = 'none';
            loaInput.required = false;
            loaInput.value = '';
            
            // Show selection_final_doc, Make required
            notPassedInfo.style.display = 'block';
            docInput.required = true;
        }
    }));
});
</script>
@endsection
