@extends('layouts.admin')

@section('title', 'Monitoring Laporan Akhir Pelatihan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Monitoring Laporan Akhir Pelatihan</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="display: flex; justify-content: space-between; align-items: center;">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Laporan Akhir Peserta</h6>
        <a href="{{ url('/admin/monitoring-pelatihan/export') }}" class="btn btn-success btn-sm" target="_blank">📥 Export Excel (CSV)</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Program Pelatihan</th>
                        <th>Hasil Seleksi & LOA</th>
                        <th>Laporan Akhir</th>
                        <th>Tanggal Kirim Lap.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td>
                                <strong>{{ $reg->user->name }}</strong><br>
                                <small class="text-muted">{{ $reg->user->nip }}</small>
                            </td>
                            <td>{{ $reg->trainingProgram->title }}</td>
                            <td>
                                @if($reg->selection_final_result === 'passed')
                                    <span class="badge badge-verified mb-1">Lolos</span><br>
                                    @if($reg->loa_path)
                                        <a href="{{ asset('storage/' . $reg->loa_path) }}" target="_blank" class="btn btn-info btn-sm mt-1" style="font-size:11px;">LOA ⬇️</a>
                                    @else
                                        <span style="font-size:11px; color:#aaa;">Belum Upload LOA</span>
                                    @endif
                                @elseif($reg->selection_final_result === 'not_passed')
                                    <span class="badge badge-rejected mb-1">Tidak Lolos</span><br>
                                    @if($reg->selection_final_doc_path)
                                        <a href="{{ asset('storage/' . $reg->selection_final_doc_path) }}" target="_blank" class="btn btn-secondary btn-sm mt-1" style="font-size:11px;">Bukti ⬇️</a>
                                    @endif
                                @else
                                    <span class="badge badge-pending">Belum Seleksi</span>
                                @endif
                            </td>
                            <td>
                                @if($reg->final_report_path)
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/download') }}" class="btn btn-primary btn-sm" style="font-size:11px;">
                                            <i class="fas fa-download"></i> Laporan ⬇️
                                        </a>
                                        <form action="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset laporan ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="font-size:11px;" title="Reset Laporan Akhir">✕</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge badge-pending">Belum Laporan</span>
                                @endif
                            </td>
                            <td>{{ $reg->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada peserta yang mengunggah laporan akhir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection
