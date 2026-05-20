<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - BPSDMP')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; display: flex; min-height: 100vh; }

        /* Sidebar */
        .admin-sidebar {
            width: 260px; background: linear-gradient(160deg, #1a3a6c 0%, #0d2244 100%);
            color: #fff; display: flex; flex-direction: column; min-height: 100vh;
            position: fixed; top: 0; left: 0; z-index: 100; box-shadow: 3px 0 15px rgba(0,0,0,0.2);
        }
        .sidebar-logo {
            padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
            font-weight: 800; font-size: 15px; letter-spacing: 0.3px; line-height: 1.4;
        }
        .sidebar-logo span { color: #64b5f6; display: block; font-size: 12px; font-weight: 500; }
        .sidebar-section { padding: 14px 20px 6px; font-size: 10px; text-transform: uppercase; color: rgba(255,255,255,0.45); letter-spacing: 1px; font-weight: 600; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px; padding: 11px 20px;
            color: rgba(255,255,255,0.75); text-decoration: none; font-size: 13.5px; transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.12); color: #fff; border-left: 3px solid #64b5f6;
        }
        .sidebar-nav a .icon { font-size: 16px; width: 20px; text-align: center; }
        .sidebar-badge { margin-left: auto; background: #e53935; color: #fff; border-radius: 10px; padding: 1px 7px; font-size: 11px; }

        /* Main content */
        .admin-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Top bar */
        .admin-topbar {
            background: #fff; padding: 0 28px; height: 60px; display: flex; align-items: center;
            justify-content: space-between; box-shadow: 0 1px 4px rgba(0,0,0,0.08); position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 17px; font-weight: 700; color: #1a3a6c; }
        .topbar-user { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #555; }
        .topbar-user .avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #1a3a6c, #64b5f6); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
        .topbar-user form button { background: none; border: 1px solid #ddd; color: #555; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; transition: all 0.2s; }
        .topbar-user form button:hover { background: #f44336; color: #fff; border-color: #f44336; }

        /* Page content */
        .admin-content { padding: 28px; flex: 1; }
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 22px; font-weight: 800; color: #1a3a6c; }
        .page-header p { color: #666; font-size: 13px; margin-top: 4px; }

        /* Cards */
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 24px; margin-bottom: 20px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #eee; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a3a6c; }

        /* Stat Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .stat-icon.blue  { background: #e3f2fd; }
        .stat-icon.green { background: #e8f5e9; }
        .stat-icon.orange{ background: #fff3e0; }
        .stat-icon.red   { background: #ffebee; }
        .stat-value { font-size: 26px; font-weight: 800; color: #1a3a6c; }
        .stat-label { font-size: 12px; color: #888; }

        /* Table */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th { background: #f5f7fa; color: #1a3a6c; font-weight: 700; padding: 12px 14px; text-align: left; border-bottom: 2px solid #e0e6ed; white-space: nowrap; }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f2f5; vertical-align: middle; }
        tbody tr:hover { background: #fafbff; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #1a3a6c; color: #fff; }
        .btn-primary:hover { background: #0d2244; }
        .btn-success { background: #2e7d32; color: #fff; }
        .btn-success:hover { background: #1b5e20; }
        .btn-warning { background: #f57c00; color: #fff; }
        .btn-warning:hover { background: #e65100; }
        .btn-danger { background: #c62828; color: #fff; }
        .btn-danger:hover { background: #b71c1c; }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-secondary:hover { background: #bdbdbd; }
        .btn-sm { padding: 5px 11px; font-size: 12px; }
        .btn-outline { background: transparent; border: 1.5px solid #1a3a6c; color: #1a3a6c; }
        .btn-outline:hover { background: #1a3a6c; color: #fff; }

        /* Badges */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-verified  { background: #d4edda; color: #155724; }
        .badge-rejected  { background: #f8d7da; color: #721c24; }
        .badge-open    { background: #d4edda; color: #155724; }
        .badge-closed  { background: #f8d7da; color: #721c24; }
        .badge-applied    { background: #cce5ff; color: #004085; }
        .badge-selected   { background: #d4edda; color: #155724; }
        .badge-not_selected { background: #f8d7da; color: #721c24; }
        .badge-admin { background: #d3d3f8; color: #3030b0; }
        .badge-user  { background: #f0f0f0; color: #555; }

        /* Forms */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid #ddd; border-radius: 8px; font-size: 14px; transition: border 0.2s; font-family: inherit; }
        .form-control:focus { border-color: #1a3a6c; outline: none; box-shadow: 0 0 0 3px rgba(26,58,108,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #eee; }

        /* Alert */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .alert-info    { background: #cce5ff; color: #004085; border-left: 4px solid #0d6efd; }
        .alert-warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }

        /* Pagination */
        .pagination-wrapper { display: flex; justify-content: flex-end; padding-top: 16px; font-weight: 600; }
        .pagination-wrapper .pagination { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
        .pagination-wrapper .pagination li { margin: 0; }
        .pagination-wrapper .pagination a, .pagination-wrapper .pagination span { 
            padding: 6px 14px; 
            font-size: 14px; 
            color: #2e7d32; 
            text-decoration: none; 
            background: transparent; 
            border: none; 
            border-radius: 6px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            min-width: 32px; 
            transition: all 0.2s; 
        }
        .pagination-wrapper .pagination a:hover {
            background: rgba(46, 125, 50, 0.1);
        }
        .pagination-wrapper .pagination .active span {
            background: #2e7d32;
            color: #fff;
        }
        .pagination-wrapper .pagination .disabled span {
            color: #aaa;
            background: transparent;
        }

        /* Search bar */
        .search-bar { display: flex; gap: 10px; margin-bottom: 16px; }
        .search-bar .form-control { max-width: 320px; }

        /* Tabs */
        .tab-nav { display: flex; gap: 0; border-bottom: 2px solid #e0e6ed; margin-bottom: 20px; }
        .tab-nav a { padding: 10px 20px; font-size: 13.5px; font-weight: 600; color: #888; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; }
        .tab-nav a.active { color: #1a3a6c; border-bottom-color: #1a3a6c; }
        .tab-nav a:hover { color: #1a3a6c; }

        /* Timeline for user dashboard */
        .timeline { position: relative; padding-left: 28px; }
        .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: #e0e6ed; }
        .timeline-step { position: relative; margin-bottom: 24px; }
        .timeline-dot { position: absolute; left: -28px; top: 4px; width: 20px; height: 20px; border-radius: 50%; border: 3px solid; display: flex; align-items: center; justify-content: center; font-size: 10px; background: #fff; }
        .timeline-dot.done    { border-color: #28a745; color: #28a745; }
        .timeline-dot.active  { border-color: #1a3a6c; color: #1a3a6c; background: #1a3a6c; color: #fff; }
        .timeline-dot.pending { border-color: #ccc; color: #ccc; }
        .timeline-dot.failed  { border-color: #dc3545; color: #dc3545; }
        .timeline-title { font-weight: 700; font-size: 14px; color: #333; }
        .timeline-sub   { font-size: 12.5px; color: #888; margin-top: 3px; }

        /* Empty state */
        .empty-state { text-align: center; padding: 80px 20px; color: #aaa; }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
        .empty-state p { font-size: 15px; }

        /* Notification badge pulse */
        @keyframes pulse-badge {
            0%,100% { transform: scale(1); }
            50%     { transform: scale(1.2); }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="admin-sidebar">
    <div class="sidebar-logo">
        🏛️ Admin Panel<br>
        <span>BPSDMP — Simdiklat</span>
    </div>

    <div class="sidebar-section">Menu Utama</div>
    <nav class="sidebar-nav">
        <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') || request()->is('admin') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>
    </nav>

    <div class="sidebar-section">Manajemen</div>
    <nav class="sidebar-nav">
        <a href="{{ url('/admin/programs') }}" class="{{ request()->is('admin/programs*') ? 'active' : '' }}">
            <span class="icon">📚</span> Program Pelatihan
        </a>
        <a href="{{ url('/admin/verifikasi') }}" class="{{ request()->is('admin/verifikasi*') ? 'active' : '' }}">
            <span class="icon">✅</span> Verifikasi Pendaftaran
            @php $pendingCount = \App\Models\TrainingRegistration::where('verification_status','pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <span class="icon">👥</span> Kelola Pengguna
        </a>
        <a href="{{ url('/admin/documents') }}" class="{{ request()->is('admin/documents*') ? 'active' : '' }}">
            <span class="icon">📄</span> Dokumen Seleksi
        </a>
        <a href="{{ url('/admin/monitoring-pendidikan') }}" class="{{ request()->is('admin/monitoring-pendidikan*') ? 'active' : '' }}">
            <span class="icon">🎓</span> Monitoring Pendidikan
        </a>
        <a href="{{ url('/admin/monitoring-pelatihan') }}" class="{{ request()->is('admin/monitoring-pelatihan*') ? 'active' : '' }}">
            <span class="icon">📋</span> Monitoring Pelatihan
        </a>
    </nav>

    <div class="sidebar-section">Lainnya</div>
    <nav class="sidebar-nav">
        <a href="{{ url('/diklat') }}" target="_blank">
            <span class="icon">🌐</span> Lihat Situs
        </a>
        <form method="POST" action="{{ url('/logout') }}" style="display:contents;">
            @csrf
            <button type="submit" style="background:none;border:none;width:100%;cursor:pointer;" class="">
                <a href="#" onclick="this.closest('form').submit(); return false;" style="color:rgba(255,100,100,0.85);">
                    <span class="icon">🚪</span> Logout
                </a>
            </button>
        </form>
    </nav>
</aside>

{{-- Main --}}
<div class="admin-main">
    {{-- Topbar --}}
    <div class="admin-topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-user">

            {{-- Notification Bell (Admin) --}}
            @php
                $today = \Carbon\Carbon::today();
                $adminDeadlines = \App\Models\TrainingProgram::where('status', 'open')
                    ->whereDate('end_date', '>=', $today)
                    ->whereDate('end_date', '<=', $today->copy()->addDays(10))
                    ->orderBy('end_date')
                    ->get()
                    ->map(function($p) use ($today) {
                        $p->days_left = (int) $today->diffInDays($p->end_date, false);
                        return $p;
                    });
            @endphp
            <div style="position:relative;" id="admin-notif-wrap">
                <div id="admin-notif-overlay" style="display:none;position:fixed;inset:0;z-index:9990;"></div>
                <button id="admin-notif-btn"
                    title="Notifikasi Pendaftaran"
                    style="background:none;border:none;cursor:pointer;padding:6px;border-radius:50%;color:#555;display:flex;align-items:center;justify-content:center;position:relative;transition:background 0.2s;"
                    onmouseover="this.style.background='#f0f2f5'" onmouseout="this.style.background='none'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    @if($adminDeadlines->count() > 0)
                        <span style="position:absolute;top:2px;right:2px;background:#e53935;color:white;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid white;animation:pulse-badge 1.5s infinite;">
                            {{ $adminDeadlines->count() }}
                        </span>
                    @endif
                </button>
                <div id="admin-notif-dropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:310px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.15);z-index:9999;overflow:hidden;">
                    <div style="padding:13px 16px;background:linear-gradient(135deg,#1a3a6c,#0d2244);color:white;font-weight:700;font-size:13px;">
                        🔔 Peringatan Tutup Pendaftaran
                    </div>
                    <div style="max-height:320px;overflow-y:auto;">
                        @forelse($adminDeadlines as $p)
                            @php
                                $dl = $p->days_left;
                                $icon = $dl <= 1 ? '🚨' : ($dl <= 3 ? '⚠️' : '📅');
                                $pillBg = $dl <= 1 ? '#ffebee' : ($dl <= 3 ? '#fff3e0' : '#e3f2fd');
                                $pillColor = $dl <= 1 ? '#c62828' : ($dl <= 3 ? '#e65100' : '#1565c0');
                                $iconBg = $dl <= 1 ? '#fff0f0' : ($dl <= 3 ? '#fff8e1' : '#e3f2fd');
                                $daysText = $dl <= 0 ? 'Hari ini!' : ($dl === 1 ? '1 hari lagi' : $dl . ' hari lagi');
                            @endphp
                            <a href="{{ url('/admin/programs/' . $p->id . '/edit') }}"
                               style="display:flex;align-items:flex-start;gap:11px;padding:11px 16px;border-bottom:1px solid #f0f2f5;text-decoration:none;color:#333;transition:background 0.15s;"
                               onmouseover="this.style.background='#f8faff'" onmouseout="this.style.background=''">
                                <div style="width:34px;height:34px;border-radius:9px;background:{{ $iconBg }};display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">{{ $icon }}</div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-weight:600;font-size:13px;color:#1a3a6c;line-height:1.3;">{{ Str::limit($p->title, 38) }}</div>
                                    <div style="font-size:11.5px;color:#888;margin-top:2px;">{{ ucfirst($p->kategori) }} · Tutup {{ $p->end_date->format('d M Y') }}</div>
                                </div>
                                <span style="margin-left:6px;background:{{ $pillBg }};color:{{ $pillColor }};font-size:10.5px;font-weight:800;padding:3px 8px;border-radius:20px;white-space:nowrap;flex-shrink:0;">{{ $daysText }}</span>
                            </a>
                        @empty
                            <div style="padding:28px 16px;text-align:center;color:#aaa;font-size:13px;">
                                ✅ Tidak ada program yang<br>akan segera tutup pendaftaran.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{-- / Notification Bell --}}

            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div style="font-weight:600;font-size:13px;">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:#aaa;">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit">🚪 Logout</button>
            </form>
        </div>
    </div>

    <script>
    (function(){
        var btn = document.getElementById('admin-notif-btn');
        var dd  = document.getElementById('admin-notif-dropdown');
        var ov  = document.getElementById('admin-notif-overlay');
        if(!btn) return;
        function openNotif(){ dd.style.display='block'; ov.style.display='block'; }
        function closeNotif(){ dd.style.display='none';  ov.style.display='none'; }
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            dd.style.display === 'block' ? closeNotif() : openNotif();
        });
        ov.addEventListener('click', closeNotif);
    })();
    </script>

    {{-- Content --}}
    <div class="admin-content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                ❌
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@yield('scripts')
</body>
</html>
