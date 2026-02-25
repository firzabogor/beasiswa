@extends('layouts.app')

@section('title', 'Riwayat Saya - SDMO')

@section('content')
    <h2>Riwayat Konsultasi</h2>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; color: #666;">
        <p>Belum ada riwayat konsultasi.</p>
        <a href="{{ url('/konsultasi/dashboard') }}" class="btn btn-primary">Buat Pengajuan Baru</a>
    </div>
@endsection
