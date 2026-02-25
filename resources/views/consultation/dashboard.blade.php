@extends('layouts.app')

@section('title', 'SDMO - Dashboard Pimpinan')

@section('content')
    <h2>Buat Pengajuan</h2>
    <form action="#" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="tujuan">Tujuan</label>
            <select id="tujuan" name="tujuan">
                <option value="">-- Pilih --</option>
                <option value="konsultasi_teknis">Konsultasi Teknis</option>
                <option value="konsultasi_sdm">Konsultasi SDM</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pimpinan">Pimpinan</label>
            <select id="pimpinan" name="pimpinan">
                <option value="">-- Pilih Pimpinan --</option>
                <option value="direktur">Direktur Utama</option>
                <option value="manajer">Manajer Operasional</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alasan">Alasan</label>
            <textarea id="alasan" name="alasan"></textarea>
        </div>

        <div class="form-group">
            <label for="jadwal">Usulkan Jadwal</label>
            <input type="datetime-local" id="jadwal" name="jadwal">
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
@endsection
