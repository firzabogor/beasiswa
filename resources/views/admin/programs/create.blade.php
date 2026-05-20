@extends('layouts.admin')

@section('title', 'Tambah Program - Admin')
@section('page-title', 'Tambah Program Pelatihan')

@section('content')

<div class="page-header">
    <h1>➕ Tambah Program Pelatihan</h1>
    <p><a href="{{ url('/admin/programs') }}" style="color:#1a3a6c;">← Kembali ke Daftar Program</a></p>
</div>

<div class="card">
    <form method="POST" action="{{ url('/admin/programs') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="title">Nama Program *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: Pelatihan Kepemimpinan Tingkat II">
            </div>
            <div class="form-group">
                <label class="form-label" for="kategori">Kategori *</label>
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="pelatihan"  {{ old('kategori') === 'pelatihan'  ? 'selected' : '' }}>Pelatihan</option>
                    <option value="pendidikan" {{ old('kategori') === 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
            <label class="form-label">Topik Program (Opsional)</label>
            <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Tambahkan topik jika pelamar harus memilih salah satu topik saat mendaftar program ini.</p>
            <div id="topic-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 10px;">
                <!-- Topics added here -->
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addTopicRow()">➕ Tambah Topik</button>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="image_file">Poster / Gambar Program</label>
                <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
                <small>Format: JPG, PNG, GIF (Maks. 2MB)</small>
            </div>
            <div class="form-group">
                <label class="form-label" for="document_file">Dokumen Pendukung (Silabus/Syarat)</label>
                <input type="file" id="document_file" name="document_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <small>Format: PDF, Word, Excel (Maks. 10MB)</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" id="template_1_group" style="display: none;">
                <label class="form-label" for="template_report_file">Template Laporan Akhir</label>
                <input type="file" id="template_report_file" name="template_report_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <small>Diunduh peserta di akhir program. Format: PDF/Office</small>
            </div>
            <div class="form-group" id="template_2_group" style="display: none;">
                <label class="form-label" for="template_report_2_file">Template Laporan Semester</label>
                <input type="file" id="template_report_2_file" name="template_report_2_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <small>Khusus Pendidikan. Diunduh peserta tiap semester. Format: PDF/Office</small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi Program *</label>
            <textarea id="description" name="description" class="form-control" rows="4" required placeholder="Jelaskan tujuan, materi, dan syarat program...">{{ old('description') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="start_date">Tanggal Mulai Pendaftaran *</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="end_date">Tanggal Selesai Pendaftaran *</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="execution_start_date">Tanggal Mulai Pelaksanaan *</label>
                <input type="date" id="execution_start_date" name="execution_start_date" class="form-control" value="{{ old('execution_start_date') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="execution_end_date">Tanggal Selesai Pelaksanaan *</label>
                <input type="date" id="execution_end_date" name="execution_end_date" class="form-control" value="{{ old('execution_end_date') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="location">Lokasi *</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location') }}" required placeholder="Contoh: Jakarta, Balai Pelatihan X">
            </div>
            <div class="form-group">
                <label class="form-label" for="quota">Kuota Peserta *</label>
                <input type="number" id="quota" name="quota" class="form-control" value="{{ old('quota', 30) }}" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Status Pendaftaran *</label>
            <select id="status" name="status" class="form-control" required>
                <option value="open"   {{ old('status', 'open') === 'open'   ? 'selected' : '' }}>🟢 Buka (Menerima Pendaftar)</option>
                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>🔴 Tutup</option>
            </select>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #f0f2f5; padding-top: 25px;">
            <h3 style="font-size: 16px; color: #1a3a6c; margin-bottom: 15px;">📋 Syarat Berkas Pendaftaran (Opsional)</h3>
            <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Tentukan berkas apa saja yang wajib diunggah peserta saat mendaftar program ini.</p>
            
            <div class="table-wrapper" style="margin-bottom: 15px;">
                <table id="req-table">
                    <thead>
                        <tr>
                            <th>Nama Berkas (Contoh: Scan KTP, Ijazah)</th>
                            <th style="width: 150px;">Wajib?</th>
                            <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="req-body">
                        <!-- Baris Syarat Berkas Akan Ditambah Disini -->
                    </tbody>
                </table>
            </div>
            
            <button type="button" class="btn btn-secondary btn-sm" onclick="addReqRow()">➕ Tambah Baris Syarat</button>
        </div>

        <div class="form-actions" style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">💾 Simpan Program</button>
            <a href="{{ url('/admin/programs') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    let reqIndex = 0;
    function addReqRow() {
        const body = document.getElementById('req-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <textarea name="requirements[${reqIndex}][name]" class="form-control" placeholder="Input nama file berkas..." rows="2" required></textarea>
            </td>
            <td>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                    <input type="checkbox" name="requirements[${reqIndex}][is_required]" value="1" checked> Ya, Wajib
                </label>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()" style="padding: 4px 8px;">Hapus</button>
            </td>
        `;
        body.appendChild(row);
        reqIndex++;
    }

    let topicIndex = 0;
    function addTopicRow() {
        const container = document.getElementById('topic-container');
        const row = document.createElement('div');
        row.style.cssText = 'background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:15px;display:flex;flex-direction:column;gap:10px;';
        row.innerHTML = `
            <div style="display:flex;gap:10px;align-items:center;">
                <input type="text" name="topics[${topicIndex}][name]" class="form-control" placeholder="Nama topik..." required style="flex:1;">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('div[style]').remove()" style="padding:4px 10px;white-space:nowrap;">✕ Hapus</button>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <label style="font-size:12px;color:#555;font-weight:600;">Tgl Mulai Pendaftaran</label>
                    <input type="date" name="topics[${topicIndex}][start_date]" class="form-control" style="font-size:13px;">
                </div>
                <div>
                    <label style="font-size:12px;color:#555;font-weight:600;">Tgl Selesai Pendaftaran</label>
                    <input type="date" name="topics[${topicIndex}][end_date]" class="form-control" style="font-size:13px;">
                </div>
                <div>
                    <label style="font-size:12px;color:#555;font-weight:600;">Tgl Mulai Pelaksanaan</label>
                    <input type="date" name="topics[${topicIndex}][execution_start_date]" class="form-control" style="font-size:13px;">
                </div>
                <div>
                    <label style="font-size:12px;color:#555;font-weight:600;">Tgl Selesai Pelaksanaan</label>
                    <input type="date" name="topics[${topicIndex}][execution_end_date]" class="form-control" style="font-size:13px;">
                </div>
            </div>
        `;
        container.appendChild(row);
        topicIndex++;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const catSelect = document.getElementById('kategori');
        const updateTemplates = () => {
            let cat = catSelect.value;
            let t1 = document.getElementById('template_1_group');
            let t2 = document.getElementById('template_2_group');
            if(cat === 'pelatihan') {
                t1.style.display = 'block';
                t2.style.display = 'none';
            } else if(cat === 'pendidikan') {
                t1.style.display = 'block';
                t2.style.display = 'block';
            } else {
                t1.style.display = 'none';
                t2.style.display = 'none';
            }
        };
        catSelect.addEventListener('change', updateTemplates);
        updateTemplates();
    });
</script>

@endsection
