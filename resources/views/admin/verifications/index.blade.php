@extends('layouts.admin')

@section('title', 'Verifikasi Pendaftaran - Admin')
@section('page-title', 'Verifikasi Pendaftaran')

@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>✅ Verifikasi Pendaftaran</h1>
        <p>Tinjau dan verifikasi dokumen pendaftaran dari peserta.</p>
    </div>
    <div>
        <a href="{{ url('/admin/export-registrations?status=' . $status) }}" class="btn btn-success" style="background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
            </svg>
            Export ke Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="tab-nav">
        <a href="{{ url('/admin/verifikasi?status=pending') }}"  class="{{ $status === 'pending' ? 'active' : '' }}">⏳ Menunggu</a>
        <a href="{{ url('/admin/verifikasi?status=verified') }}" class="{{ $status === 'verified' ? 'active' : '' }}">✅ Terverifikasi</a>
        <a href="{{ url('/admin/verifikasi?status=rejected') }}" class="{{ $status === 'rejected' ? 'active' : '' }}">❌ Ditolak</a>
    </div>

    @if($registrations->isEmpty())
        <div class="empty-state">
            <div class="icon">📋</div>
            <p>Tidak ada pendaftaran dengan status ini.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>NIP</th>
                        <th>Program</th>
                        <th>Kategori</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Verifikasi</th>
                        <th>Berkas</th>
                        <th>Surat Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $i => $reg)
                    <tr>
                        <td>{{ $registrations->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $reg->user->name ?? '-' }}</td>
                        <td style="font-size:12px;color:#888;">{{ $reg->user->nip ?? '-' }}</td>
                        <td style="max-width:200px;">{{ $reg->trainingProgram->title ?? '-' }}</td>
                        <td>
                            @if($reg->trainingProgram)
                                <span class="badge badge-{{ $reg->trainingProgram->kategori === 'pelatihan' ? 'open' : 'applied' }}">
                                    {{ ucfirst($reg->trainingProgram->kategori) }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $reg->created_at->format('d M Y') }}</td>
                            </span>
                        </td>
                        <td>
                            @if($reg->requirement_documents_count > 0)
                                <span style="font-size:12px;color:#1a3a6c;font-weight:600;">📁 {{ $reg->requirement_documents_count }} File</span>
                            @else
                                <span style="font-size:12px;color:#888;">0 File</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->invitation_letter_path)
                                <span style="color:#28a745;font-size:12px;font-weight:600;">✅ Ada</span>
                            @else
                                <span style="color:#aaa;font-size:12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('/admin/verifikasi/' . $reg->id) }}" class="btn btn-primary btn-sm">🔍 Proses</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $registrations->links() }}</div>
    @endif
</div>

@endsection
