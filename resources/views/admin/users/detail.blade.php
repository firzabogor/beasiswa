@extends('layouts.admin')
@section('title', 'Detail Pengguna - Admin')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="page-header">
    <h1>👤 Detail: {{ $user->name }}</h1>
    <p><a href="{{ url('/admin/users') }}" style="color:#1a3a6c;">← Kembali ke Daftar Pengguna</a></p>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
    <div class="card">
        <div class="card-header"><h3>Informasi Akun</h3></div>
        <table style="font-size:13.5px;width:100%;">
            <tr><td style="color:#888;padding:7px 0;width:100px;">Nama</td><td><strong>{{ $user->name }}</strong></td></tr>
            <tr><td style="color:#888;padding:7px 0;">NIP</td><td>{{ $user->nip }}</td></tr>
            <tr><td style="color:#888;padding:7px 0;">Jabatan</td><td>{{ $user->position }}</td></tr>
            <tr><td style="color:#888;padding:7px 0;">Email</td><td>{{ $user->email }}</td></tr>
            <tr><td style="color:#888;padding:7px 0;">Role</td><td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td></tr>
            <tr><td style="color:#888;padding:7px 0;">Bergabung</td><td>{{ $user->created_at->format('d M Y') }}</td></tr>
        </table>
        <div style="margin-top:16px;display:flex;gap:8px;">
            <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-warning btn-sm">✏️ Edit</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Riwayat Pendaftaran</h3></div>
        @forelse($user->registrations as $reg)
            <div style="border:1px solid #e0e6ed;border-radius:8px;padding:14px;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <div style="font-weight:700;font-size:14px;">{{ $reg->trainingProgram->title ?? '-' }}</div>
                        <div style="font-size:12px;color:#888;margin-top:4px;">Didaftarkan: {{ $reg->created_at->format('d M Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $reg->verification_status }}">
                        {{ $reg->verification_status === 'pending' ? 'Pending' : ($reg->verification_status === 'verified' ? 'Verified' : 'Ditolak') }}
                    </span>
                </div>
                @if($reg->companyApplication)
                    <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f2f5;font-size:12.5px;color:#555;">
                        🏢 Perusahaan: <strong>{{ $reg->companyApplication->company_name }}</strong> |
                        Posisi: {{ $reg->companyApplication->applied_position }} |
                        <span class="badge badge-{{ $reg->companyApplication->status }}" style="font-size:10px;">{{ ucfirst(str_replace('_',' ',$reg->companyApplication->status)) }}</span>
                    </div>
                @endif
            </div>
        @empty
            <p style="color:#aaa;text-align:center;padding:20px;">Belum ada pendaftaran.</p>
        @endforelse
    </div>
</div>
@endsection
