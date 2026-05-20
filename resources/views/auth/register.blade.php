<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Simdiklat BPSDMP</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

    <div class="login-card" style="max-width: 500px;">
        <div class="login-header">
            <h1>Simdiklat</h1>
            <p>Pendaftaran Akun Baru</p>
        </div>

        {{-- Tampilkan errors validasi --}}
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 14px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" class="login-form">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       required placeholder="Nama Lengkap"
                       style="{{ $errors->has('name') ? 'border-color: #dc3545;' : '' }}">
            </div>

            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" id="nip" name="nip"
                       value="{{ old('nip') }}"
                       required placeholder="Nomor Induk Pegawai"
                       style="{{ $errors->has('nip') ? 'border-color: #dc3545;' : '' }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       required placeholder="Email Instansi"
                       style="{{ $errors->has('email') ? 'border-color: #dc3545;' : '' }}">
            </div>

            <div class="form-group">
                <label for="position">Jabatan</label>
                <input type="text" id="position" name="position"
                       value="{{ old('position') }}"
                       required placeholder="Jabatan Saat Ini"
                       style="{{ $errors->has('position') ? 'border-color: #dc3545;' : '' }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; box-sizing: border-box; {{ $errors->has('password') ? 'border-color: #dc3545;' : '' }}"
                       required placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; box-sizing: border-box;"
                       required placeholder="Ulangi Password">
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
