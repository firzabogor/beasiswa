<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDMO BPSDM Perhubungan</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

    <div class="login-card">
        <div class="login-header">
            <h1>SDMO</h1>
            <p>Sistem Konsultasi Kepegawaian</p>
        </div>

        <form action="{{ url('/login') }}" method="POST" class="login-form">
            @csrf
            
            <div class="form-group">
                <label for="email">Email / NIP</label>
                <input type="text" id="email" name="email" required placeholder="Masukkan Email atau NIP">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; box-sizing: border-box;" required placeholder="Masukkan Password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>

        <div class="login-footer">
            <a href="#">Lupa Password?</a>
            <span style="margin: 0 10px; color: #ccc;">|</span>
            <a href="{{ url('/register') }}">Belum punya akun? Daftar</a>
            <br><br>
            &copy; {{ date('Y') }} BPSDMP
        </div>
    </div>

</body>
</html>
