@extends('layouts.app')

@section('title', 'Daftar Diklat - Simdiklat')

@section('content')
    <h2>Daftar Program Diklat</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($programs as $program)
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: var(--primary-color);">{{ $program->title }}</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    {{ Str::limit($program->description, 100) }}
                </p>
                <div style="margin-bottom: 15px; font-size: 13px; color: #555;">
                    <div><strong>Lokasi:</strong> {{ $program->location }}</div>
                    <div><strong>Kuota:</strong> {{ $program->quota }} Peserta</div>
                    <div><strong>Status:</strong> 
                        <span style="color: {{ $program->status == 'open' ? 'green' : 'red' }}; font-weight: bold;">
                            {{ ucfirst($program->status) }}
                        </span>
                    </div>
                </div>
                <a href="{{ url('/diklat/' . $program->id) }}" class="btn btn-primary btn-block">Lihat Detail</a>
            </div>
        @endforeach
    </div>
@endsection
