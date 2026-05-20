@extends('layouts.admin')

@section('title', 'Detail Program - Admin')
@section('page-title', 'Detail Program')

@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>📋 Detail Program</h1>
        <p>Informasi lengkap dan statistik pendaftar program.</p>
    </div>
    <a href="{{ url('/admin/programs') }}" class="btn btn-secondary">⬅️ Kembali</a>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3>Informasi Program</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <table class="table" style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="width: 35%; font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Nama Program</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: {{ $program->title }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Kategori</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: <span class="badge badge-{{ $program->kategori === 'pelatihan' ? 'open' : 'applied' }}">{{ ucfirst($program->kategori) }}</span></td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Status</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: <span class="badge badge-{{ $program->status }}">{{ $program->status === 'open' ? 'Buka' : 'Tutup' }}</span></td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Kuota</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: {{ $program->quota }} Peserta</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Lokasi</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: {{ $program->location }}</td>
                </tr>
            </table>
        </div>
        <div>
            <table class="table" style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="width: 35%; font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Tanggal Pendaftaran</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: {{ $program->start_date->format('d M Y') }} - {{ $program->end_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 8px 0; border-bottom: 1px solid #eee;">Pelaksanaan</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">: {{ $program->execution_start_date ? $program->execution_start_date->format('d M Y') : '-' }} - {{ $program->execution_end_date ? $program->execution_end_date->format('d M Y') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 8px;">Deskripsi:</h4>
        <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; font-size: 14px; line-height: 1.6;">
            {!! nl2br(e($program->description)) !!}
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📊 Statistik Pendaftar</h3>
    </div>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <canvas id="programChart" width="400" height="150"></canvas>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('programChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendaftar', 'Endorsement BPSDMP', 'Lolos Seleksi Akhir'],
            datasets: [{
                label: 'Jumlah Peserta',
                data: [{{ $registrants }}, {{ $endorsements }}, {{ $finalPassed }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // This makes the chart horizontal
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endsection
