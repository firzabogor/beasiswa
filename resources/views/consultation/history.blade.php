@extends('layouts.app')

@section('title', 'Riwayat Konsultasi - Simdiklat')

@section('content')
    <h2>Riwayat Konsultasi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ url('/konsultasi/buat') }}" class="btn btn-primary">+ Ajukan Konsultasi Baru</a>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; color: #666;">
        <p>Halaman riwayat konsultasi tersedia melalui menu <strong>Konsultasi</strong> di sidebar.</p>
        <a href="{{ url('/konsultasi') }}" class="btn btn-primary">Lihat Daftar Konsultasi</a>
    </div>
@endsection
