@extends('layouts.app')

@section('title', $program->title . ' - Simdiklat')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/diklat') }}" style="color: #666; text-decoration: none;">&larr; Kembali ke Daftar</a>
    </div>

    <div style="background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h1 style="margin-top: 0; color: var(--primary-color);">{{ $program->title }}</h1>
        
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <strong>Mulai:</strong> <br>
                {{ \Carbon\Carbon::parse($program->start_date)->format('d F Y H:i') }}
            </div>
            <div>
                <strong>Selesai:</strong> <br>
                {{ \Carbon\Carbon::parse($program->end_date)->format('d F Y H:i') }}
            </div>
            <div>
                <strong>Lokasi:</strong> <br>
                {{ $program->location }}
            </div>
            <div>
                <strong>Kuota:</strong> <br>
                {{ $program->quota }} Peserta
            </div>
        </div>

        <h3>Deskripsi</h3>
        <p style="line-height: 1.6; color: #333;">
            {{ $program->description }}
        </p>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            @if($program->status == 'open')
                <form action="{{ url('/diklat/' . $program->id . '/daftar') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mendaftar diklat ini?');">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">Daftar Sekarang</button>
                    <span style="color: #666; margin-left: 10px; font-size: 14px;">Pastikan data pendaftaran sudah benar.</span>
                </form>
            @else
                <button class="btn btn-secondary" disabled>Pendaftaran Ditutup</button>
            @endif
        </div>
    </div>
@endsection
