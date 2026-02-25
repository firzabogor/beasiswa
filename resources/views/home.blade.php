<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simdiklat - Sistem Diklat Pegawai BPSDMP</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .hero {
            background: linear-gradient(135deg, #003366 0%, #00264d 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 42px;
            margin: 0 0 20px;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .features {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
        
        @media (max-width: 768px) {
            .features {
                grid-template-columns: 1fr;
            }
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .programs-preview {
            background: #f8f9fa;
            padding: 60px 20px;
        }
        
        .programs-preview h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 40px;
        }
        
        .program-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .program-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .program-card h3 {
            color: var(--primary-color);
            margin-top: 0;
        }
        
        .cta-section {
            background: var(--primary-color);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .cta-section h2 {
            margin-top: 0;
            font-size: 32px;
        }
    </style>
</head>
<body>

    <!-- Simple Header -->
    <header style="background: var(--header-bg); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="margin: 0; font-size: 20px; color: white;">Simdiklat BPSDMP</h1>
        </div>
        <div>
            <a href="{{ url('/login') }}" class="btn btn-primary" style="margin-right: 10px;">Masuk</a>
            <a href="{{ url('/register') }}" class="btn btn-secondary">Daftar</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Sistem Diklat Pegawai</h1>
        <p>Platform terpadu untuk pendaftaran dan manajemen pendidikan & pelatihan pegawai BPSDMP</p>
        <div class="hero-buttons">
            <a href="{{ url('/register') }}" class="btn btn-primary" style="padding: 15px 35px; font-size: 16px;">Daftar Sekarang</a>
            <a href="#programs" class="btn btn-secondary" style="padding: 15px 35px; font-size: 16px;">Lihat Program</a>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#003366" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    <circle cx="12" cy="8" r="2"></circle>
                    <path d="M12 10v4"></path>
                </svg>
            </div>
            <h3>Beragam Program</h3>
            <p>Akses berbagai program diklat sesuai kebutuhan pengembangan kompetensi Anda.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#003366" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    <path d="M9 11h6"></path>
                    <path d="M9 15h6"></path>
                </svg>
            </div>
            <h3>Pendaftaran Mudah</h3>
            <p>Proses pendaftaran yang cepat dan sederhana, langsung dari perangkat Anda.</p>
        </div>
    </section>

    <!-- Programs Preview -->
    <section class="programs-preview" id="programs">
        <h2>Program Diklat Tersedia</h2>
        <div class="program-grid">
            <div class="program-card">
                <h3>Diklatpim Tingkat IV</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Pendidikan dan Pelatihan Kepemimpinan Tingkat IV untuk membentuk kompetensi kepemimpinan operasional.
                </p>
                <div style="font-size: 13px; color: #555;">
                    <div><strong>Lokasi:</strong> Kampus BPSDMP Jakarta</div>
                    <div><strong>Kuota:</strong> 30 Peserta</div>
                </div>
            </div>
            <div class="program-card">
                <h3>Workshop IT Security</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Pelatihan keamanan informasi untuk staf IT dalam menghadapi ancaman cyber.
                </p>
                <div style="font-size: 13px; color: #555;">
                    <div><strong>Lokasi:</strong> Online (Zoom)</div>
                    <div><strong>Kuota:</strong> 50 Peserta</div>
                </div>
            </div>
            <div class="program-card">
                <h3>Manajemen Proyek</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Pelatihan manajemen proyek untuk meningkatkan efektivitas pengelolaan program.
                </p>
                <div style="font-size: 13px; color: #555;">
                    <div><strong>Lokasi:</strong> Kampus BPSDMP Bandung</div>
                    <div><strong>Kuota:</strong> 25 Peserta</div>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ url('/login') }}" class="btn btn-primary" style="padding: 12px 30px;">Login untuk Melihat Semua Program</a>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Siap Mengembangkan Kompetensi?</h2>
        <p style="font-size: 18px; margin-bottom: 30px;">Bergabunglah dengan ribuan pegawai yang telah meningkatkan kualitas diri melalui program diklat kami.</p>
        <a href="{{ url('/register') }}" class="btn btn-primary" style="background: white; color: var(--primary-color); padding: 15px 40px; font-size: 16px;">Daftar Akun Sekarang</a>
    </section>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
