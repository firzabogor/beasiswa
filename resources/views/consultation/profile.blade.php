@extends('layouts.app')

@section('title', 'Profil Saya - Simdiklat')

@section('content')
    <h2>Profil Saya</h2>

    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" value="{{ Auth::user()->name }}" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>NIP</label>
        <input type="text" value="{{ Auth::user()->nip }}" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" value="{{ Auth::user()->position }}" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="text" value="{{ Auth::user()->email }}" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>Role</label>
        <input type="text" value="{{ ucfirst(Auth::user()->role) }}" readonly style="background-color: #e9ecef;">
    </div>
@endsection
