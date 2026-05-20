@extends('layouts.admin')
@section('title', 'Edit Pengguna - Admin')
@section('page-title', 'Edit Akun Pengguna')

@section('content')
<div class="page-header">
    <h1>✏️ Edit: {{ $user->name }}</h1>
    <p><a href="{{ url('/admin/users') }}" style="color:#1a3a6c;">← Kembali</a></p>
</div>
<div class="card">
    <form method="POST" action="{{ url('/admin/users/' . $user->id) }}">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIP *</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Jabatan *</label>
                <input type="text" name="position" class="form-control" value="{{ old('position', $user->position) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-control" required>
                <option value="user"  {{ old('role', $user->role) === 'user'  ? 'selected' : '' }}>👤 User</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
            </select>
        </div>
        <div class="alert alert-info">ℹ️ Kosongkan kolom password jika tidak ingin mengubah password.</div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
