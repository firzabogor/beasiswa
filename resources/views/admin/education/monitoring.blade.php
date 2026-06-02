@extends('layouts.admin')

@section('title', 'Monitoring Pendidikan - Admin')
@section('page-title', 'Progress Siswa Pendidikan (Beasiswa)')

@section('content')

<div class="page-header">
    <h1>🎓 Monitoring Program Beasiswa (Internal & External)</h1>
    <p>Pantau perkembangan, laporan semester, dan berkas akhir para penerima beasiswa.</p>
</div>

<div class="card">
    <div class="card-header" style="justify-content: space-between;">
        <h3>Daftar Progress Siswa</h3>
        <a href="{{ url('/admin/monitoring-pendidikan/export') }}" class="btn btn-success" target="_blank">📥 Export Excel (CSV)</a>
    </div>

    @if($registrations->isEmpty())
        <div class="empty-state">
            <div class="icon">🎓</div>
            <p>Belum ada pendaftar di kategori program beasiswa.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>NIP & Nama</th>
                        <th>Program</th>
                        <th>LOG/LOA</th>
                        <th>Laporan Semester</th>
                        <th>Lap. Akhir Studi / Ijazah</th>
                        <th>Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                    <tr>
                        <td>
                            <div style="font-weight: 700;">{{ $reg->user->name }}</div>
                            <div style="font-size: 11px; color: #666;">NIP: {{ $reg->user->nip }}</div>
                        </td>
                        <td>{{ $reg->trainingProgram->title }}</td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                @if($reg->log_path)
                                    <div style="display:flex; align-items:center; gap:5px;">
                                        <a href="{{ asset('storage/' . $reg->log_path) }}" target="_blank" class="badge badge-verified" style="text-decoration:none;">LOG ⬇️</a>
                                        <form action="{{ url('/admin/monitoring-pendidikan/' . $reg->id . '/reset-log') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset berkas LOG ini? Peserta akan diminta mengunggah ulang.')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px; font-size: 10px;" title="Reset Berkas LOG">✕</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge badge-applied">LOG: Belum</span>
                                @endif

                                @if($reg->loa_path)
                                    <div style="display:flex; align-items:center; gap:5px;">
                                        <a href="{{ asset('storage/' . $reg->loa_path) }}" target="_blank" class="badge badge-verified" style="text-decoration:none;">LOA ⬇️</a>
                                        <form action="{{ url('/admin/monitoring-pendidikan/' . $reg->id . '/reset-loa') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset berkas LOA ini? Peserta akan diminta mengunggah ulang.')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px; font-size: 10px;" title="Reset Berkas LOA">✕</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge badge-applied">LOA: Belum</span>
                                @endif
                            </div>
                        </td>
                        <td style="max-width: 200px;">
                            @if($reg->educationActivities->isNotEmpty())
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($reg->educationActivities as $act)
                                        <a href="{{ asset('storage/' . $act->document_path) }}" target="_blank" 
                                           title="{{ $act->semester_name }}"
                                           style="padding: 4px 8px; background: #edf2f7; border-radius: 4px; border: 1px solid #cbd5e0; font-size: 10px; color: #4a5568; text-decoration: none;">
                                           {{ $act->semester_name }} ⬇️
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span style="font-size: 11px; color: #999;">Tidak ada laporan</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->final_doc_path || $reg->final_report_path)
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    @if($reg->final_report_path)
                                        <a href="{{ asset('storage/' . $reg->final_report_path) }}" target="_blank" class="btn btn-info btn-sm" style="background:#17a2b8; color:white; font-size:12px;">Lap. Studi/KHS ⬇️</a>
                                    @endif
                                    @if($reg->final_doc_path)
                                        <a href="{{ asset('storage/' . $reg->final_doc_path) }}" target="_blank" class="btn btn-primary btn-sm" style="font-size:12px;">Ijazah ⬇️</a>
                                    @endif
                                    <form action="{{ url('/admin/monitoring-pendidikan/' . $reg->id . '/reset') }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin me-reset berkas akhir ini? Peserta harus mengunggah ulang berkas mereka dan status akan kembali berlangsung.')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; font-size:12px;">
                                            <i class="fas fa-undo"></i> Reset Berkas
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge badge-applied">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->is_education_completed)
                                <span class="badge badge-verified">SELESAI 🏁</span>
                            @else
                                <span class="badge badge-applied">Berlangsung</span>
                            @endif
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
