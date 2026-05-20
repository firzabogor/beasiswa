@extends('layouts.admin')

@section('title', 'Dashboard Admin - Simdiklat')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h1>Selamat Datang, {{ auth()->user()->name }} 👋</h1>
    <p>Ringkasan aktivitas sistem Simdiklat BPSDMP hari ini.</p>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📚</div>
        <div>
            <div class="stat-value">{{ $data['total_programs'] }}</div>
            <div class="stat-label">Total Program</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">👥</div>
        <div>
            <div class="stat-value">{{ $data['total_users'] }}</div>
            <div class="stat-label">Pengguna Terdaftar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div>
            <div class="stat-value">{{ $data['pending_verif'] }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">📋</div>
        <div>
            <div class="stat-value">{{ $data['total_registrations'] }}</div>
            <div class="stat-label">Total Pendaftaran</div>
        </div>
    </div>
</div>

{{-- Grafik / Statistik Program --}}
<div class="card" style="padding: 24px; margin-bottom: 24px;">
    <div class="card-header" style="justify-content: space-between;">
        <h3>📈 Statistik Pendaftaran Program</h3>
        <p style="font-size: 13px; color: #666; margin: 0;">Data menunjukkan total pendaftar, lolos (verifikasi), dan tidak lolos per program.</p>
    </div>

    {{-- Filter Grafik --}}
    <form method="GET" action="{{ url('/admin/dashboard') }}" style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-end;">
        <div style="flex: 1;">
            <label class="form-label">Tahun</label>
            <select name="chart_year" class="form-control">
                <option value="">Semua Tahun</option>
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $chartYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 2;">
            <label class="form-label">Nama Program</label>
            <input type="text" name="chart_program" value="{{ $chartProgram }}" class="form-control" placeholder="Cari judul program (Pelatihan / Pendidikan)...">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Cari Data</button>
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary" style="padding: 10px 18px;">Reset</a>
        </div>
    </form>

    <div style="height: 400px; width: 100%;">
        <canvas id="programChart"></canvas>
    </div>
</div>

{{-- Aksi Cepat --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card" style="padding:20px;">
        <div class="card-header">
            <h3>⚡ Aksi Cepat</h3>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ url('/admin/programs/create') }}" class="btn btn-primary">➕ Tambah Program Baru</a>
            <a href="{{ url('/admin/verifikasi?status=pending') }}" class="btn btn-warning">✅ Verifikasi Pendaftaran ({{ $data['pending_verif'] }})</a>
            <a href="{{ url('/admin/users/create') }}" class="btn btn-success">👤 Tambah Pengguna</a>
            <a href="{{ url('/admin/documents') }}" class="btn btn-secondary">📄 Lihat Dokumen Seleksi</a>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <div class="card-header">
            <h3>📋 Pendaftaran Terbaru</h3>
        </div>
        @forelse($data['recent_registrations'] as $reg)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f2f5;">
                <div>
                    <div style="font-size:13px;font-weight:600;">{{ $reg->user->name ?? '-' }}</div>
                    <div style="font-size:12px;color:#888;">{{ $reg->trainingProgram->title ?? '-' }}</div>
                </div>
                <span class="badge badge-{{ $reg->verification_status }}">
                    {{ $reg->verification_status === 'pending' ? 'Pending' : ($reg->verification_status === 'verified' ? 'Verified' : 'Ditolak') }}
                </span>
            </div>
        @empty
            <p style="color:#aaa;font-size:13px;text-align:center;padding:20px 0;">Belum ada pendaftaran.</p>
        @endforelse
        <a href="{{ url('/admin/verifikasi') }}" class="btn btn-outline btn-sm" style="margin-top:12px;">Lihat Semua →</a>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('programChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const dataTotal = {!! json_encode($chartTotal) !!};
        const dataLolos = {!! json_encode($chartLolos) !!};
        const dataTidakLolos = {!! json_encode($chartTidakLolos) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Pendaftar',
                        data: dataTotal,
                        backgroundColor: 'rgba(26, 58, 108, 0.7)',
                        borderColor: '#1a3a6c',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Lolos Verifikasi',
                        data: dataLolos,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: '#28a745',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Tidak Lolos Verifikasi',
                        data: dataTidakLolos,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: '#dc3545',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        ticks: {
                            // Truncate long labels
                            callback: function(val, index) {
                                let label = this.getLabelForValue(val);
                                return label.length > 25 ? label.substr(0, 25) + '...' : label;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                // Full title on tooltip
                                return context[0].label;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>
@endsection
