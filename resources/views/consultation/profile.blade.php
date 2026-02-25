@extends('layouts.app')

@section('title', 'Profil Saya - SDMO')

@section('content')
    <h2>Profil Saya</h2>
    
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" value="Rayhan A" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" value="Karyawan" readonly style="background-color: #e9ecef;">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="text" value="rayhan@bpsdm.dephub.go.id" readonly style="background-color: #e9ecef;">
    </div>

    <button class="btn btn-secondary">Edit Profil</button>
@endsection
