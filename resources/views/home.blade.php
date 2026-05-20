<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simdiklat - Sistem Diklat Pegawai BPSDMP</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="home-page">

    {{-- Header publik --}}
    <header class="home-header">
        <div>
            <h1>Simdiklat BPSDMP</h1>
        </div>
        <div>
            @auth
                <a href="{{ url('/diklat') }}" class="btn btn-primary" style="margin-right: 10px;">Dashboard</a>
            @else
                <a href="{{ url('/login') }}"    class="btn btn-primary"    style="margin-right: 10px;">Masuk</a>
                <a href="{{ url('/register') }}" class="btn btn-secondary">Daftar</a>
            @endauth
        </div>
    </header>

    {{-- Hero --}}
    <section class="hero">
        <h1>Sistem Pelatihan Pegawai</h1>
        <p>Platform terpadu untuk pendaftaran dan manajemen kompetensi pegawai BPSDMP</p>
        
        {{-- Search Bar --}}
        <div class="search-container">
            <form action="{{ url('/') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Cari penawaran pelatihan..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
            </form>
        </div>

        <div class="hero-buttons" style="margin-top: 20px;">
            <a href="{{ url('/register') }}" class="btn btn-primary">Daftar Sekarang</a>
            <a href="#programs" class="btn btn-secondary">Lihat Program</a>
        </div>
    </section>

    {{-- Preview Program --}}
    <section class="programs-preview" id="programs">
        <h2>{{ $search ? 'Hasil Pencarian: "' . $search . '"' : 'Program Tersedia' }}</h2>

        {{-- Tab Kategori --}}
        <div class="program-tabs">
            <button class="tab-btn {{ $kategori === 'pelatihan' ? 'active' : '' }}" onclick="filterProgram('pelatihan', this)">Program Pelatihan</button>
            <button class="tab-btn {{ $kategori === 'pendidikan' ? 'active' : '' }}" onclick="filterProgram('pendidikan', this)">Program Pendidikan</button>
        </div>

        <div class="program-grid">
            @forelse($programs as $program)
                <a href="{{ url('/diklat/' . $program->id) }}" class="program-card-link" style="text-decoration: none; color: inherit;">
                    <div class="program-card" data-kategori="{{ $program->kategori }}" style="{{ $program->kategori === $kategori ? '' : 'display:none;' }}">
                        <div class="program-image" style="background-image: url('{{ $program->image_path ? asset('storage/' . $program->image_path) : asset('img/default-program.jpg') }}'); background-size: cover; background-position: center; height: 180px; width: 100%; border-radius: 8px 8px 0 0;">
                            @if(!$program->image_path)
                                <div class="placeholder-img" style="height: 100%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #777;">
                                    <span>No Poster</span>
                                </div>
                            @endif
                        </div>
                        <div class="program-info" style="padding: 15px; text-align: center;">
                            <h3 style="margin: 0; font-size: 1.1rem; color: #003366;">{{ $program->title }}</h3>
                        </div>
                    </div>
                </a>
            @empty
                <div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <p>Maaf, program tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="programs-cta">
            @auth
                <a id="cta-program-btn" href="{{ url('/diklat') }}?kategori={{ $kategori }}" class="btn btn-primary" style="padding: 12px 30px;">
                    Lihat Semua Program
                </a>
            @else
                <a id="cta-program-btn" href="{{ url('/login') }}" class="btn btn-primary" style="padding: 12px 30px;">
                    Login untuk Melihat Semua Program
                </a>
            @endauth
        </div>
    </section>

    <script>
        // Tentukan base URL dashboard & login dari Laravel
        const dashboardUrl = '{{ url("/diklat") }}';
        const loginUrl    = '{{ url("/login") }}';
        const isLoggedIn  = {{ Auth::check() ? 'true' : 'false' }};

        function filterProgram(kategori, elBtn) {
            // Update tab aktif
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            elBtn.classList.add('active');

            // Tampilkan/sembunyikan card
            document.querySelectorAll('.program-card').forEach(card => {
                card.style.display = card.dataset.kategori === kategori ? '' : 'none';
            });

            // Update tombol CTA — arahkan ke halaman program sesuai kategori
            const ctaBtn = document.getElementById('cta-program-btn');
            if (ctaBtn) {
                if (isLoggedIn) {
                    ctaBtn.href = dashboardUrl + '?kategori=' + kategori;
                } else {
                    // Simpan intended URL agar setelah login langsung ke kategori yg dipilih
                    ctaBtn.href = loginUrl;
                    ctaBtn.dataset.kategori = kategori;
                }
            }
        }
    </script>

    {{-- CTA --}}
    <section class="cta-section">
        <h2>Siap Mengembangkan Kompetensi?</h2>
        <p>Bergabunglah dengan ribuan pegawai yang telah meningkatkan kualitas diri melalui program pelatihan kami.</p>
        <a href="{{ url('/register') }}" class="btn btn-white">Daftar Akun Sekarang</a>
    </section>

    {{-- Footer --}}
    @include('partials.footer')

</body>
</html>
