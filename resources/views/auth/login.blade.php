<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Simdiklat BPSDMP</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body.login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #00264d 0%, #003f88 60%, #0065bd 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            display: flex;
            width: 860px;
            max-width: 95vw;
            min-height: 480px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        /* Panel kiri — branding */
        .login-brand {
            flex: 1;
            background: linear-gradient(160deg, #003366 0%, #001a40 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 35px;
            text-align: center;
            color: white;
        }

        .login-brand .brand-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .login-brand h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            border: none;
            color: white;
            padding: 0;
        }

        .login-brand p {
            font-size: 13.5px;
            opacity: 0.75;
            line-height: 1.6;
        }

        .login-brand .tagline {
            display: inline-block;
            margin-top: 28px;
            padding: 6px 16px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            font-size: 12px;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* Panel kanan — form */
        .login-form-panel {
            flex: 1.1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 45px;
        }

        .login-form-panel h3 {
            font-size: 22px;
            color: #003366;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-form-panel .subtitle {
            font-size: 13.5px;
            color: #888;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #dde1e7;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            color: #333;
            background: #fafbfc;
        }

        .form-group input:focus {
            border-color: #003366;
            box-shadow: 0 0 0 3px rgba(0,51,102,0.1);
            background: #fff;
        }

        .form-group input.is-error {
            border-color: #e53935;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #003366 0%, #0055a4 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: opacity 0.2s, transform 0.1s;
            font-family: inherit;
        }

        .btn-login:hover  { opacity: 0.92; }
        .btn-login:active { transform: scale(0.99); }

        .alert-box {
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 18px;
        }
        .alert-box.error {
            background: #fdecea;
            color: #b71c1c;
            border-left: 4px solid #e53935;
        }
        .alert-box.success {
            background: #e8f5e9;
            color: #1b5e20;
            border-left: 4px solid #43a047;
        }
        .alert-box ul { margin: 0; padding-left: 16px; }

        .login-footer-links {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
        .login-footer-links a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
        }
        .login-footer-links a:hover { text-decoration: underline; }
        .sep { margin: 0 8px; color: #ccc; }

        @media (max-width: 620px) {
            .login-brand { display: none; }
            .login-form-panel { padding: 40px 28px; }
            .login-wrapper { min-height: auto; }
        }
    </style>
</head>
<body class="login-page">

    <div class="login-wrapper">

        {{-- Panel kiri --}}
        <div class="login-brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                     fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <h2>Simdiklat BPSDMP</h2>
            <p>Sistem Informasi Manajemen<br>Pendidikan &amp; Pelatihan Pegawai</p>
            <span class="tagline">Kementerian Perhubungan</span>
        </div>

        {{-- Panel kanan (form) --}}
        <div class="login-form-panel">
            <h3>Selamat Datang</h3>
            <p class="subtitle">Masuk menggunakan akun Simdiklat Anda</p>

            @if($errors->any())
                <div class="alert-box error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-box success">{{ session('success') }}</div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email"
                           class="{{ $errors->has('email') ? 'is-error' : '' }}"
                           autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Masukkan password"
                           class="{{ $errors->has('password') ? 'is-error' : '' }}"
                           autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <div class="login-footer-links">
                <a href="#">Lupa Password?</a>
                <span class="sep">|</span>
                <a href="{{ url('/register') }}">Belum punya akun? Daftar</a>
            </div>
        </div>

    </div>

</body>
</html>
