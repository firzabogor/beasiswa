@extends('layouts.admin')

@section('title', 'Manajemen Konsultasi - Admin')
@section('page-title', 'Manajemen Konsultasi')

@section('content')

<div class="page-header">
    <h1>💬 Manajemen Konsultasi</h1>
    <p>Lihat dan tanggapi ajuan konsultasi dari para pegawai.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Ajuan Konsultasi</h3>
    </div>

    @if($consultations->isEmpty())
        <div class="empty-state">
            <div class="icon">💬</div>
            <p>Belum ada ajuan konsultasi yang masuk.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Nama Pegawai</th>
                        <th>Tujuan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultations as $c)
                    <tr>
                        <td style="font-size: 13px;">{{ $c->created_at->format('d/m/Y H:i') }}</td>
                        <td style="font-weight:600;">{{ $c->user->name ?? 'User Tidak Ditemukan' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($c->tujuan)) }}</td>
                        <td>{{ $c->jadwal->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge badge-{{ $c->status === 'approved' ? 'open' : ($c->status === 'pending' ? 'applied' : 'closed') }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ url('/admin/consultations/' . $c->id) }}" class="btn btn-primary btn-sm">Buka & Balas</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
