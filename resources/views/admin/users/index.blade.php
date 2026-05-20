@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Admin')
@section('page-title', 'Kelola Pengguna')

@section('content')

<div class="page-header">
    <h1>👥 Manajemen Pengguna</h1>
    <p>Kelola akun pengguna dan administrator sistem.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Pengguna</h3>
        <a href="{{ url('/admin/users/create') }}" class="btn btn-primary btn-sm">➕ Tambah Akun</a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ url('/admin/users') }}" class="search-bar">
        <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, atau email..." value="{{ $search }}">
        <button type="submit" class="btn btn-primary btn-sm">🔍 Cari</button>
        @if($search)
            <a href="{{ url('/admin/users') }}" class="btn btn-secondary btn-sm">✖ Reset</a>
        @endif
    </form>

    @if($users->isEmpty())
        <div class="empty-state">
            <div class="icon">👥</div>
            <p>Tidak ada pengguna yang ditemukan.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Pendaftaran</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $u->name }}</td>
                        <td style="font-size:12px;color:#888;">{{ $u->nip }}</td>
                        <td style="font-size:13px;max-width:160px;">{{ $u->position }}</td>
                        <td style="font-size:13px;">{{ $u->email }}</td>
                        <td><span class="badge badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                        <td>{{ $u->registrations()->count() }}</td>
                        <td>{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="{{ url('/admin/users/' . $u->id) }}" class="btn btn-outline btn-sm">👁 Detail</a>
                                <a href="{{ url('/admin/users/' . $u->id . '/edit') }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <form method="POST" action="{{ url('/admin/users/' . $u->id) }}" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $users->links() }}</div>
    @endif
</div>

@endsection
