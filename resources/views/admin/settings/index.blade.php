@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">⚙️ Pengaturan Aplikasi</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Teks Pop-up Knowledge Sharing (KS)</h6>
                <small class="text-muted">Pop-up ini akan otomatis muncul pada dashboard pelamar yang telah menyelesaikan program pelatihannya (telah mengunggah Laporan Akhir).</small>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/settings') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="ks_popup_title">Judul Pop-up</label>
                        <input type="text" class="form-control" id="ks_popup_title" name="ks_popup_title" value="{{ \App\Models\Setting::getValue('ks_popup_title', 'Selamat, Pelatihan Selesai!') }}" required>
                        <small class="form-text text-muted">Contoh: Selamat, Pelatihan Selesai!</small>
                    </div>

                    <div class="form-group">
                        <label for="ks_popup_subtitle">Sub-judul Pop-up</label>
                        <input type="text" class="form-control" id="ks_popup_subtitle" name="ks_popup_subtitle" value="{{ \App\Models\Setting::getValue('ks_popup_subtitle', 'Terima kasih atas dedikasi dan semangat belajar Anda.') }}" required>
                        <small class="form-text text-muted">Kalimat pendek di bawah judul utama.</small>
                    </div>

                    <div class="form-group">
                        <label for="ks_popup_desc">Deskripsi Utama</label>
                        <textarea class="form-control" id="ks_popup_desc" name="ks_popup_desc" rows="3" required>{{ \App\Models\Setting::getValue('ks_popup_desc', 'Anda telah berhasil menyelesaikan seluruh rangkaian program pelatihan dengan baik. Pencapaian ini adalah kontribusi nyata bagi pengembangan kompetensi di instansi kita.') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="ks_popup_info">Teks Kotak Info (Tindak Lanjut)</label>
                        <textarea class="form-control" id="ks_popup_info" name="ks_popup_info" rows="4" required>{{ \App\Models\Setting::getValue('ks_popup_info', 'Sebagai bagian dari kewajiban peserta, Saudara diharapkan bersedia menjadi narasumber pada kegiatan Knowledge Sharing paling lambat dalam waktu H+14 hari kerja setelah pelatihan berakhir.') }}</textarea>
                        <small class="form-text text-muted">Catatan: Anda dapat menggunakan kode HTML sederhana seperti <code>&lt;strong&gt;teks tebal&lt;/strong&gt;</code> untuk penekanan kalimat.</small>
                    </div>

                    <div class="form-group mt-4 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">💡 Bantuan</h6>
            </div>
            <div class="card-body" style="font-size: 13px;">
                <p>Fitur Pengaturan ini memungkinkan Anda untuk mengubah kalimat yang tampil pada sistem secara dinamis.</p>
                <p>Saat ini pengaturan yang tersedia adalah untuk <strong>Pop-up Knowledge Sharing (KS)</strong>. Jika pelamar melihat KS yang memiliki informasi yang kedaluwarsa, ubahlah kalimatnya melalui formulir di samping.</p>
                <hr>
                <div class="alert alert-warning p-2">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Perubahan yang disimpan akan langsung berpengaruh pada Pop-up KS bagi peserta yang bersangkutan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
