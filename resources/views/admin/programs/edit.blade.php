@extends('layouts.admin')

@section('title', 'Edit Program - Admin')
@section('page-title', 'Edit Program Pelatihan')

@section('content')

<div class="page-header">
    <h1>✏️ Edit Program: {{ $program->title }}</h1>
    <p><a href="{{ url('/admin/programs') }}" style="color:#1a3a6c;">← Kembali ke Daftar Program</a></p>
</div>

<div class="card">
    <form method="POST" action="{{ url('/admin/programs/' . $program->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="title">Nama Program *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $program->title) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="kategori">Kategori *</label>
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value="pelatihan"   {{ old('kategori', $program->kategori) === 'pelatihan'   ? 'selected' : '' }}>Pelatihan</option>
                    <option value="pendidikan" {{ old('kategori', $program->kategori) === 'pendidikan' ? 'selected' : '' }}>Beasiswa Pendidikan Internal</option>
                    <option value="beasiswa_external" {{ old('kategori', $program->kategori) === 'beasiswa_external' ? 'selected' : '' }}>Beasiswa Pendidikan External</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="image_file">Poster / Gambar Program</label>
                <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
                @if($program->image_path)
                    <div style="margin-top: 5px;">
                        <img src="{{ asset('storage/' . $program->image_path) }}" alt="Poster" style="max-height: 100px; border-radius: 4px;">
                    </div>
                @endif
                <small>Kosongkan jika tidak ingin mengubah. Format: JPG, PNG, Maks. 2MB</small>
            </div>
            <div class="form-group">
                <label class="form-label" for="document_file">Dokumen Pendukung</label>
                <input type="file" id="document_file" name="document_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                @if($program->document_path)
                    <div style="margin-top: 5px;">
                        <a href="{{ asset('storage/' . $program->document_path) }}" target="_blank">📄 Lihat Dokumen Saat Ini</a>
                    </div>
                @endif
                <small>Kosongkan jika tidak ingin mengubah. Format: PDF, Word, Excel, Maks. 10MB</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" id="template_1_group" style="display: none;">
                <label class="form-label" for="template_report_file">Template Laporan Akhir</label>
                <input type="file" id="template_report_file" name="template_report_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                @if($program->template_report_path)
                    <div style="margin-top: 5px;">
                        <a href="{{ asset('storage/' . $program->template_report_path) }}" target="_blank">📄 Lihat Template Saat Ini</a>
                    </div>
                @endif
                <small>Khusus peserta di akhir program. Format: PDF/Office (Maks. 10MB)</small>
            </div>
            <div class="form-group" id="template_2_group" style="display: none;">
                <label class="form-label" for="template_report_2_file">Template Laporan Semester</label>
                <input type="file" id="template_report_2_file" name="template_report_2_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                @if($program->template_report_2_path)
                    <div style="margin-top: 5px;">
                        <a href="{{ asset('storage/' . $program->template_report_2_path) }}" target="_blank">📄 Lihat Template Saat Ini</a>
                    </div>
                @endif
                <small>Khusus Pendidikan. Diunduh peserta tiap semester. Format: PDF/Office</small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi Program *</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $program->description) }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="start_date">Tanggal Mulai Pendaftaran *</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $program->start_date ? $program->start_date->format('Y-m-d') : '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="end_date">Tanggal Selesai Pendaftaran *</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $program->end_date ? $program->end_date->format('Y-m-d') : '') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="execution_start_date">Tanggal Mulai Pelaksanaan *</label>
                <input type="date" id="execution_start_date" name="execution_start_date" class="form-control" value="{{ old('execution_start_date', $program->execution_start_date ? $program->execution_start_date->format('Y-m-d') : '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="execution_end_date">Tanggal Selesai Pelaksanaan *</label>
                <input type="date" id="execution_end_date" name="execution_end_date" class="form-control" value="{{ old('execution_end_date', $program->execution_end_date ? $program->execution_end_date->format('Y-m-d') : '') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="location">Lokasi *</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $program->location) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="quota">Kuota Peserta *</label>
                <input type="number" id="quota" name="quota" class="form-control" value="{{ old('quota', $program->quota) }}" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Status Pendaftaran *</label>
            <select id="status" name="status" class="form-control" required>
                <option value="open"   {{ old('status', $program->status) === 'open'   ? 'selected' : '' }}>🟢 Buka (Menerima Pendaftar)</option>
                <option value="closed" {{ old('status', $program->status) === 'closed' ? 'selected' : '' }}>🔴 Tutup</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ url('/admin/programs') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div><div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>📚 Topik Program</h3>
    </div>
    <div style="padding: 0 20px 20px;">
        <p style="font-size: 13px; color: #666; margin-bottom: 20px;">Tentukan topik yang dapat dipilih oleh pelamar saat mendaftar program ini.</p>

        <div class="table-wrapper" style="margin-bottom: 24px;">
            <table>
                <thead>
                    <tr>
                        <th>Nama Topik</th>
                        <th>Tgl Pendaftaran</th>
                        <th>Tgl Pelaksanaan</th>
                        <th style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($program->topics as $topic)
                        <tr>
                            <td>
                                <input type="text" name="name" value="{{ $topic->name }}" class="form-control" style="font-size:13px;" required form="edit-topic-{{ $topic->id }}">
                            </td>
                            <td>
                                <input type="date" name="start_date" value="{{ $topic->start_date ? $topic->start_date->format('Y-m-d') : '' }}" class="form-control" style="font-size:12px; margin-bottom:5px;" form="edit-topic-{{ $topic->id }}">
                                <input type="date" name="end_date" value="{{ $topic->end_date ? $topic->end_date->format('Y-m-d') : '' }}" class="form-control" style="font-size:12px;" form="edit-topic-{{ $topic->id }}">
                            </td>
                            <td>
                                <input type="date" name="execution_start_date" value="{{ $topic->execution_start_date ? $topic->execution_start_date->format('Y-m-d') : '' }}" class="form-control" style="font-size:12px; margin-bottom:5px;" form="edit-topic-{{ $topic->id }}">
                                <input type="date" name="execution_end_date" value="{{ $topic->execution_end_date ? $topic->execution_end_date->format('Y-m-d') : '' }}" class="form-control" style="font-size:12px;" form="edit-topic-{{ $topic->id }}">
                            </td>
                            <td>
                                <div style="display:flex; gap:5px; flex-direction:column;">
                                    <form id="edit-topic-{{ $topic->id }}" action="{{ url('/admin/topics/' . $topic->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">💾 Update</button>
                                    </form>
                                    <form action="{{ url('/admin/topics/' . $topic->id) }}" method="POST" onsubmit="return confirm('Hapus topik ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #aaa; padding: 30px 0;">Belum ada topik khusus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="background: #f8faff; padding: 20px; border-radius: 10px; border: 1px solid #e0e6ed;">
            <h4 style="margin-top: 0; margin-bottom: 15px; font-size: 14px; color: #1a3a6c;">➕ Tambah Topik Baru</h4>
            <form action="{{ url('/admin/programs/' . $program->id . '/topics') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Nama Topik *</label>
                        <input type="text" name="topic_name" class="form-control" placeholder="Input nama topik..." required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:12px;">Tgl Mulai Pendaftaran</label>
                        <input type="date" name="topic_start_date" class="form-control">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:12px;">Tgl Selesai Pendaftaran</label>
                        <input type="date" name="topic_end_date" class="form-control">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:12px;">Tgl Mulai Pelaksanaan</label>
                        <input type="date" name="topic_exec_start" class="form-control">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:12px;">Tgl Selesai Pelaksanaan</label>
                        <input type="date" name="topic_exec_end" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">➕ Tambah Topik</button>
            </form>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>📋 Syarat Berkas Pendaftaran</h3>
    </div>
    <div style="padding: 0 20px 20px;">
        <p style="font-size: 13px; color: #666; margin-bottom: 20px;">Tentukan berkas apa saja yang wajib diunggah peserta saat mendaftar program ini.</p>

        <div class="table-wrapper" style="margin-bottom: 24px;">
            <table>
                <thead>
                    <tr>
                        <th>Nama Berkas</th>
                        <th>Wajib Diunggah?</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($program->requirements as $req)
                        <tr>
                            <td>
                                <textarea name="name" class="form-control" rows="2" required form="edit-req-{{ $req->id }}">{{ $req->name }}</textarea>
                            </td>
                            <td>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                                    <input type="checkbox" name="is_required" value="1" {{ $req->is_required ? 'checked' : '' }} form="edit-req-{{ $req->id }}"> Wajib
                                </label>
                            </td>
                            <td>
                                <div style="display:flex; gap:5px; flex-direction:column;">
                                    <form id="edit-req-{{ $req->id }}" action="{{ url('/admin/requirements/' . $req->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">💾 Update</button>
                                    </form>
                                    <form action="{{ url('/admin/requirements/' . $req->id) }}" method="POST" onsubmit="return confirm('Hapus syarat berkas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #aaa; padding: 30px 0;">Belum ada syarat berkas khusus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="background: #f8faff; padding: 20px; border-radius: 10px; border: 1px solid #e0e6ed;">
            <h4 style="margin-top: 0; margin-bottom: 15px; font-size: 14px; color: #1a3a6c;">➕ Tambah Syarat Berkas Baru</h4>
            <form action="{{ url('/admin/programs/' . $program->id . '/requirements') }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                @csrf
                <div style="flex: 2; min-width: 250px;">
                    <label class="form-label">Nama Berkas (Misal: Scan KTP, Ijazah Terakhir)</label>
                    <textarea name="requirement_name" class="form-control" placeholder="Input nama file berkas..." rows="2" required></textarea>
                </div>
                <div style="padding-bottom: 10px; flex: 1; min-width: 150px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 600; color: #444;">
                        <input type="checkbox" name="is_required" value="1" checked style="width:16px; height:16px;"> Harus Wajib?
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 38px;">➕ Tambah Syarat</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const catSelect = document.getElementById('kategori');
        const updateTemplates = () => {
            let cat = catSelect.value;
            let t1 = document.getElementById('template_1_group');
            let t2 = document.getElementById('template_2_group');
            if (cat === 'pelatihan') {
                t1.style.display = 'block';
                t2.style.display = 'none';
            } else if (cat === 'pendidikan' || cat === 'beasiswa_external') {
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
