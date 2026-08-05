@extends('layouts.admin')

@section('title', 'Monitoring Pelatihan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📊 Monitoring Hasil Seleksi & Laporan Pelatihan</h1>
    <a href="{{ url('/admin/monitoring-pelatihan/export') }}" class="btn btn-success btn-sm" target="_blank">
        📥 Export Excel (CSV)
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Daftar Peserta Pelatihan — Tahap Seleksi Akhir & Laporan
        </h6>
        <small class="text-muted">Menampilkan peserta yang sudah lolos verifikasi & mendapat Surat Rekomendasi</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th style="width:20%">Nama Peserta</th>
                        <th style="width:25%">Program Pelatihan</th>
                        <th style="width:20%; text-align:center;">Hasil Seleksi &amp; LOA</th>
                        <th style="width:20%; text-align:center;">Laporan Akhir</th>
                        <th style="width:15%; text-align:center;">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            {{-- Nama --}}
                            <td>
                                <strong>{{ $reg->user->name }}</strong><br>
                                <small class="text-muted">{{ $reg->user->nip }}</small>
                            </td>

                            {{-- Program --}}
                            <td>
                                {{ $reg->trainingProgram->title }}
                                @if($reg->programTopic)
                                    <br><small class="text-primary">📚 {{ $reg->programTopic->name }}</small>
                                @endif
                            </td>

                            {{-- Hasil Seleksi & LOA --}}
                            <td style="text-align:center; vertical-align:middle;">
                                @if($reg->selection_final_result === 'passed')
                                    <span class="badge badge-success mb-1" style="font-size:11px;">✅ LOLOS</span><br>
                                    @if($reg->loa_path)
                                        <a href="{{ file_url($reg->loa_path) }}" target="_blank"
                                           class="btn btn-info btn-sm mt-1" style="font-size:10px;">
                                            LOA ⬇️
                                        </a>
                                    @else
                                        <span style="font-size:11px; color:#aaa; display:block; margin-top:4px;">Belum Upload LOA</span>
                                    @endif

                                    {{-- Tombol Reset Seleksi --}}
                                    <form action="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/reset-seleksi') }}"
                                          method="POST" class="mt-2"
                                          onsubmit="return confirm('Reset Hasil Seleksi & LOA untuk {{ addslashes($reg->user->name) }}?\n\nPeserta akan diwajibkan mengisi kembali dokumen seleksi akhir dari awal.')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" style="font-size:10px; padding: 2px 8px;"
                                                title="Reset Hasil Seleksi & LOA">
                                            🔄 Reset Seleksi
                                        </button>
                                    </form>

                                @elseif($reg->selection_final_result === 'not_passed')
                                    <span class="badge badge-danger mb-1" style="font-size:11px;">❌ TDK LOLOS</span><br>
                                    @if($reg->selection_final_doc_path)
                                        <a href="{{ file_url($reg->selection_final_doc_path) }}" target="_blank"
                                           class="btn btn-secondary btn-sm mt-1" style="font-size:10px;">
                                            Bukti ⬇️
                                        </a>
                                    @endif

                                    {{-- Tombol Reset Seleksi --}}
                                    <form action="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/reset-seleksi') }}"
                                          method="POST" class="mt-2"
                                          onsubmit="return confirm('Reset Hasil Seleksi & LOA untuk {{ addslashes($reg->user->name) }}?\n\nPeserta akan diwajibkan mengisi kembali dokumen seleksi akhir dari awal.')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" style="font-size:10px; padding: 2px 8px;"
                                                title="Reset Hasil Seleksi & LOA">
                                            🔄 Reset Seleksi
                                        </button>
                                    </form>

                                @else
                                    <span class="badge badge-secondary" style="font-size:11px; background:#6c757d;">⏳ BELUM SELEKSI</span>
                                @endif
                            </td>

                            {{-- Laporan Akhir --}}
                            <td style="text-align:center; vertical-align:middle;">
                                @if($reg->final_report_path)
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 6px;">
                                        <a href="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/download') }}"
                                           class="btn btn-primary btn-sm" style="font-size:10px;">
                                            <i class="fas fa-download"></i> Laporan ⬇️
                                        </a>
                                        <form action="{{ url('/admin/monitoring-pelatihan/' . $reg->id . '/reset') }}"
                                              method="POST"
                                              onsubmit="return confirm('Reset laporan akhir untuk {{ addslashes($reg->user->name) }}?\n\nPeserta akan dapat mengunggah ulang laporan mereka.')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px; padding: 2px 8px;"
                                                    title="Reset Laporan Akhir">
                                                🗑️ Reset Laporan
                                            </button>
                                        </form>
                                    </div>
                                @elseif($reg->selection_final_result === 'passed' && $reg->loa_path)
                                    <span class="badge badge-warning" style="font-size:11px; color:#856404; background:#fff3cd;">
                                        ⏳ Belum Upload
                                    </span>
                                @else
                                    <span style="font-size:11px; color:#ccc;">—</span>
                                @endif
                            </td>

                            {{-- Waktu update --}}
                            <td style="text-align:center; vertical-align:middle; font-size:12px; color:#6c757d;">
                                {{ $reg->updated_at->format('d M Y') }}<br>
                                <small>{{ $reg->updated_at->format('H:i') }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <div style="font-size:32px; margin-bottom:8px;">📋</div>
                                Belum ada peserta pelatihan yang telah mendapat Surat Rekomendasi.
                            </td>
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
