<aside>
    <div class="sidebar-menu">
        <a href="{{ url('/dashboard') }}"
           class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            📊 Dashboard Saya
        </a>
        <a href="{{ url('/diklat') }}"
           class="menu-item {{ Request::is('diklat') && !Request::is('diklat/*') ? 'active' : '' }}">
            📋 Daftar Program
        </a>
        <a href="{{ url('/riwayat-diklat') }}"
           class="menu-item {{ Request::is('riwayat-diklat') ? 'active' : '' }}">
            📁 Riwayat Pendaftaran
        </a>
        <a href="{{ url('/konsultasi') }}"
           class="menu-item {{ Request::is('konsultasi*') ? 'active' : '' }}">
            💬 Konsultasi
        </a>
        <a href="{{ url('/konsultasi/profil') }}"
           class="menu-item {{ Request::is('konsultasi/profil') ? 'active' : '' }}">
            👤 Profil Saya
        </a>

        @if(auth()->check() && in_array(auth()->user()->email, ['admin@bpsdmp.go.id', 'admin.internal@bpsdmp.go.id']))
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.15);">
                <div style="font-size:10px;text-transform:uppercase;color:rgba(255,255,255,0.4);padding:0 14px;margin-bottom:6px;letter-spacing:1px;">Admin</div>
                <a href="{{ url('/admin/dashboard') }}"
                   class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    🛡️ Dashboard Admin
                </a>
                
                @if(auth()->user()->email !== 'admin.internal@bpsdmp.go.id')
                <a href="{{ url('/admin/consultations') }}"
                   class="menu-item {{ Request::is('admin/consultations*') ? 'active' : '' }}">
                    💬 Manajemen Konsultasi
                </a>
                @endif
                
                <a href="{{ url('/admin/monitoring-pendidikan') }}"
                   class="menu-item {{ Request::is('admin/monitoring-pendidikan*') ? 'active' : '' }}">
                    🎓 Monitoring Beasiswa
                </a>
                <a href="{{ url('/admin/documents') }}"
                   class="menu-item {{ Request::is('admin/documents*') ? 'active' : '' }}">
                    📊 Hasil Seleksi
                </a>
            </div>
        @endif
    </div>
</aside>
