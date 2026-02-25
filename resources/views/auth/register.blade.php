<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SDMO BPSDM Perhubungan</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

    <div class="login-card" style="max-width: 500px;">
        <div class="login-header">
            <h1>SDMO</h1>
            <p>Pendaftaran Akun Baru</p>
        </div>

        <form action="{{ url('/register') }}" method="POST" class="login-form">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required placeholder="Nama Lengkap">
            </div>

            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" id="nip" name="nip" required placeholder="Nomor Induk Pegawai">
            </div>

            <div class="form-group">
                <label for="position">Jabatan</label>
                <input type="text" id="position" name="position" required placeholder="Jabatan Saat Ini">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; box-sizing: border-box;" required placeholder="Password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; box-sizing: border-box;" required placeholder="Ulangi Password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>

        <div class="login-footer">
            Sudah punya akun? <a href="{{ url('/login') }}">Masuk disini</a>
            <br><br>
            &copy; {{ date('Y') }} BPSDMP
        </div>
    </div>

</body>
</html>
