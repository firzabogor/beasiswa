@extends('layouts.admin')

@section('title', 'Program Pelatihan - Admin')
@section('page-title', 'Manajemen Program Pelatihan')

@section('content')

<div class="page-header">
    <h1>📚 Manajemen Program</h1>
    <p>Kelola program Pelatihan dan Pendidikan yang tersedia.</p>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 15px;">
        <h3 style="flex: 1; min-width: 200px;">Daftar Program</h3>
        
        <form method="GET" action="{{ url('/admin/programs') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <select name="year" class="form-control" style="width: auto; height: 38px;">
                <option value="">-- Semua Tahun --</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Cari nama program..." value="{{ request('search') }}" style="width: 250px; height: 38px;">
            <button type="submit" class="btn btn-secondary" style="height: 38px;">🔍 Cari</button>
            @if(request('search') || request('year'))
                <a href="{{ url('/admin/programs') }}" class="btn btn-light" style="height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">❌ Reset</a>
            @endif
        </form>

        <a href="{{ url('/admin/programs/create') }}" class="btn btn-primary btn-sm" style="height: 38px; display: flex; align-items: center;">➕ Tambah Program</a>
    </div>

    @if($programs->isEmpty())
        <div class="empty-state">
            <div class="icon">📚</div>
            <p>Belum ada program pelatihan yang dibuat.</p>
            <a href="{{ url('/admin/programs/create') }}" class="btn btn-primary" style="margin-top:16px;">Tambah Program Pertama</a>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Program</th>
                        <th>Kategori</th>
                        <th>Tgl Pendaftaran</th>
                        <th>Lokasi</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Syarat</th>
                        <th>Pendaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $i => $p)
                    <tr>
                        <td>{{ $programs->firstItem() + $i }}</td>
                        <td style="font-weight:600;max-width:220px;">{{ $p->title }}</td>
                        <td>
                            @php
                                $katLabel = 'Pelatihan';
                                if($p->kategori === 'pendidikan') $katLabel = 'Beasiswa Internal';
                                if($p->kategori === 'beasiswa_external') $katLabel = 'Beasiswa External';
                            @endphp
                            <span class="badge badge-{{ $p->kategori === 'pelatihan' ? 'open' : 'applied' }}">{{ $katLabel }}</span>
                        </td>
                        <td>{{ $p->start_date->format('d M Y') }}</td>
                        <td>{{ $p->location }}</td>
                        <td>{{ $p->quota }}</td>
                        <td><span class="badge badge-{{ $p->status }}">{{ $p->status === 'open' ? 'Buka' : 'Tutup' }}</span></td>
                        <td>{{ $p->requirements_count }} Berkas</td>
                        <td>{{ $p->registrations()->count() }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="{{ url('/admin/programs/' . $p->id) }}" class="btn btn-info btn-sm" style="background: #17a2b8; color: white;">👁️ View</a>
                                <a href="{{ url('/admin/programs/' . $p->id . '/edit') }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <form method="POST" action="{{ url('/admin/programs/' . $p->id) }}" onsubmit="return confirm('Hapus program ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $programs->appends(request()->query())->links() }}</div>
    @endif
</div>

@endsection
