@extends('layouts.app')

@section('title', 'Ajukan Konsultasi - Simdiklat')

@section('content')
    {{--
        File ini (dashboard.blade.php) adalah sisa dari versi lama.
        Fungsionalitas form konsultasi sudah dipindahkan ke consultation/create.blade.php
        yang terhubung ke route POST /konsultasi via ConsultationController@store.
        View ini diredirect untuk menghindari kebingungan.
    --}}
    <script>window.location.href = "{{ url('/konsultasi/buat') }}";</script>
    <p>Mengalihkan ke halaman ajuan konsultasi... <a href="{{ url('/konsultasi/buat') }}">Klik di sini</a> jika tidak otomatis.</p>
@endsection
