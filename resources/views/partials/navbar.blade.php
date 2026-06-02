@php
    // Hitung program dengan pendaftaran yang akan segera tutup (≤10 hari)
    $upcomingDeadlines = collect();
    if (auth()->check()) {
        $today = \Carbon\Carbon::today();
        $upcomingDeadlines = \App\Models\TrainingProgram::where('status', 'open')
            ->whereDate('end_date', '>=', $today)
            ->whereDate('end_date', '<=', $today->copy()->addDays(10))
            ->orderBy('end_date')
            ->get()
            ->map(function($p) use ($today) {
                $p->days_left = (int) $today->diffInDays($p->end_date, false);
                return $p;
            });
    }
@endphp

<style>
/* ---- Notification Bell ---- */
.notif-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    margin-right: 14px;
}
.notif-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    position: relative;
}
.notif-btn:hover { background: rgba(255,255,255,0.15); }
.notif-btn svg { width: 22px; height: 22px; }
.notif-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #f03e3e;
    color: white;
    border-radius: 50%;
    width: 17px;
    height: 17px;
    font-size: 10px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #00264d;
    animation: pulse-badge 1.5s infinite;
}
@keyframes pulse-badge {
    0%,100% { transform: scale(1); }
    50%      { transform: scale(1.2); }
}
.notif-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 10px);
    right: -10px;
    width: 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.18);
    z-index: 9999;
    overflow: hidden;
}
.notif-dropdown.open { display: block; }
.notif-header {
    padding: 14px 16px;
    background: linear-gradient(135deg, #003366, #00264d);
    color: white;
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.notif-list { max-height: 340px; overflow-y: auto; }
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f2f5;
    text-decoration: none;
    color: #333;
    transition: background 0.15s;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f8faff; }
.notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}
.notif-icon.urgent  { background: #fff0f0; }
.notif-icon.warning { background: #fff8e1; }
.notif-icon.normal  { background: #e3f2fd; }
.notif-title { font-weight: 600; font-size: 13px; color: #1a3a6c; line-height: 1.3; }
.notif-sub   { font-size: 11.5px; color: #888; margin-top: 3px; }
.notif-days {
    margin-left: auto;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0;
}
.notif-days.d1  { background: #ffebee; color: #c62828; }
.notif-days.d3  { background: #fff3e0; color: #e65100; }
.notif-days.d10 { background: #e3f2fd; color: #1565c0; }
.notif-empty {
    padding: 28px 16px;
    text-align: center;
    color: #aaa;
    font-size: 13px;
}
/* Overlay */
#notif-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9990;
}
#notif-overlay.open { display: block; }
</style>

<header>
    <div class="header-left">
        <h1>Simdiklat - BPSDMP</h1>
        <p>Sistem Diklat Pegawai</p>
    </div>
    <div class="header-right" style="display:flex;align-items:center;">
        @auth
            {{-- Notification Bell --}}
            <div class="notif-wrap" id="notif-wrap">
                <div id="notif-overlay"></div>
                <button class="notif-btn" id="notif-btn" title="Notifikasi Pendaftaran" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    @if($upcomingDeadlines->count() > 0)
                        <span class="notif-badge">{{ $upcomingDeadlines->count() }}</span>
                    @endif
                </button>

                <div class="notif-dropdown" id="notif-dropdown">
                    <div class="notif-header">
                        🔔 Peringatan Tutup Pendaftaran
                    </div>
                    <div class="notif-list">
                        @forelse($upcomingDeadlines as $p)
                            @php
                                $dl = $p->days_left;
                                $iconClass = $dl <= 1 ? 'urgent' : ($dl <= 3 ? 'warning' : 'normal');
                                $icon      = $dl <= 1 ? '🚨' : ($dl <= 3 ? '⚠️' : '📅');
                                $daysClass = $dl <= 1 ? 'd1'  : ($dl <= 3 ? 'd3'  : 'd10');
                                $daysText  = $dl <= 0 ? 'Hari ini!' : ($dl === 1 ? '1 hari lagi' : $dl . ' hari lagi');
                            @endphp
                            <a href="{{ url('/diklat/' . $p->id) }}" class="notif-item">
                                <div class="notif-icon {{ $iconClass }}">{{ $icon }}</div>
                                <div style="flex:1;min-width:0;">
                                    <div class="notif-title">{{ Str::limit($p->title, 40) }}</div>
                                    <div class="notif-sub">{{ ucfirst($p->kategori) }} · Tutup {{ $p->end_date->format('d M Y') }}</div>
                                </div>
                                <span class="notif-days {{ $daysClass }}">{{ $daysText }}</span>
                            </a>
                        @empty
                            <div class="notif-empty">
                                ✅ Tidak ada program yang<br>akan segera tutup pendaftaran.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <span class="name">{{ Auth::user()->name }}</span>
            <span class="role">
                {{ Auth::user()->position }}
                <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout" style="background: none; border: none; cursor: pointer; color: inherit; padding: 0; font: inherit; text-decoration: underline;">
                        Logout
                    </button>
                </form>
            </span>
        @else
            <a href="{{ url('/login') }}" class="btn btn-primary" style="margin-right: 8px;">Masuk</a>
            <a href="{{ url('/register') }}" class="btn btn-secondary">Daftar</a>
        @endauth
    </div>
</header>

<script>
    (function(){
        const btn      = document.getElementById('notif-btn');
        const dropdown = document.getElementById('notif-dropdown');
        const overlay  = document.getElementById('notif-overlay');
        if(!btn) return;
        function open(){
            dropdown.classList.add('open');
            overlay.classList.add('open');
        }
        function close(){
            dropdown.classList.remove('open');
            overlay.classList.remove('open');
        }
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            dropdown.classList.contains('open') ? close() : open();
        });
        overlay.addEventListener('click', close);
    })();
</script>

