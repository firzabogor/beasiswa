<aside>
    <div class="sidebar-menu">
        <a href="{{ url('/diklat') }}" class="menu-item {{ Request::is('diklat*') ? 'active' : '' }}">
            Daftar Diklat
        </a>
        <a href="{{ url('/riwayat-diklat') }}" class="menu-item {{ Request::is('riwayat-diklat') ? 'active' : '' }}">
            Riwayat Pendaftaran
        </a>
        <a href="{{ url('/konsultasi/profil') }}" class="menu-item {{ Request::is('konsultasi/profil') ? 'active' : '' }}">
            Profil Saya
        </a>
    </div>
</aside>
