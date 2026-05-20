@extends('layouts.admin')
@section('title', 'Dokumen Seleksi - Admin')
@section('page-title', 'Dokumen Hasil Seleksi')

@section('content')
<div class="page-header">
    <h1>📄 Dokumen Hasil Seleksi Perusahaan</h1>
    <p>Semua dokumen yang diupload peserta dari perusahaan yang dituju.</p>
</div>

<div class="card">
    <div class="tab-nav">
        <a href="{{ url('/admin/documents') }}"               class="{{ !$result ? 'active' : '' }}">📋 Semua</a>
        <a href="{{ url('/admin/documents?result=passed') }}" class="{{ $result === 'passed' ? 'active' : '' }}">✅ Lolos</a>
        <a href="{{ url('/admin/documents?result=not_passed') }}" class="{{ $result === 'not_passed' ? 'active' : '' }}">❌ Tidak Lolos</a>
    </div>

    @if($docs->isEmpty())
        <div class="empty-state"><div class="icon">📄</div><p>Belum ada dokumen seleksi.</p></div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>Program</th>
                        <th>Kategori</th>
                        <th>Perusahaan / Beasiswa</th>
                        <th>Posisi / Tujuan</th>
                        <th>Hasil Seleksi</th>
                        <th>Diupload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docs as $i => $doc)
                    @php 
                        $app = $doc->companyApplication; 
                        $reg = $app->trainingRegistration; 
                        $isEdu = $reg->trainingProgram->kategori === 'pendidikan';
                    @endphp
                    <tr>
                        <td>{{ $docs->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $reg->user->name ?? '-' }}</td>
                        <td style="max-width:180px;">{{ $reg->trainingProgram->title ?? '-' }}</td>
                        <td><span class="badge badge-{{ $isEdu ? 'applied' : 'open' }}">{{ ucfirst($reg->trainingProgram->kategori) }}</span></td>
                        <td>{{ $app->company_name }}</td>
                        <td>{{ $app->applied_position }}</td>
                        <td>
                            @if($doc->selection_result === 'passed')
                                <span class="badge badge-verified">✅ Lolos {{ $isEdu ? 'Beasiswa' : '' }}</span>
                            @else
                                <span class="badge badge-rejected">❌ Tidak Lolos {{ $isEdu ? 'Beasiswa' : '' }}</span>
                            @endif
                        </td>
                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ url('/admin/documents/' . $doc->id . '/download') }}" class="btn btn-primary btn-sm">⬇️ Unduh</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $docs->links() }}</div>
    @endif
</div>
@endsection
