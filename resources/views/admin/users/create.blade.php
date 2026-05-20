@extends('layouts.admin')
@section('title', 'Tambah Pengguna - Admin')
@section('page-title', 'Tambah Akun Pengguna')

@section('content')
<div class="page-header">
    <h1>➕ Tambah Akun Pengguna</h1>
    <p><a href="{{ url('/admin/users') }}" style="color:#1a3a6c;">← Kembali</a></p>
</div>
<div class="card">
    <form method="POST" action="{{ url('/admin/users') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIP *</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Jabatan *</label>
                <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-control" required>
                    <option value="user"  {{ old('role','user') === 'user'  ? 'selected' : '' }}>👤 User</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Akun</button>
            <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
