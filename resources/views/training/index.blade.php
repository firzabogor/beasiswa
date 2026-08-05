@extends('layouts.app')

@section('title', 'Daftar Program Pelatihan - Simdiklat')

@section('content')

    {{-- Judul --}}
    <h2>Daftar Program</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filter & Search --}}
    <div class="filter-container" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <form action="{{ url('/diklat') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
            <input type="hidden" name="kategori" value="{{ $kategori }}">
            
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px;">Pencarian</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama program...">
            </div>

            <div style="width: 150px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px;">Filter Tahun</label>
                <select name="year" class="form-control">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="height: 40px;">🔍 Cari & Filter</button>
            </div>
        </form>
    </div>

    {{-- Tab Kategori --}}
    <div class="program-tabs" style="margin-bottom: 25px;">
        <a href="{{ url('/diklat') }}?kategori=pelatihan&search={{ $search }}&year={{ $year }}"
           class="tab-btn {{ $kategori === 'pelatihan' ? 'active' : '' }}">
            Program Pelatihan
        </a>
        <a href="{{ url('/diklat') }}?kategori=pendidikan&search={{ $search }}&year={{ $year }}"
           class="tab-btn {{ $kategori === 'pendidikan' ? 'active' : '' }}">
            Program Beasiswa Pendidikan Internal
        </a>
        <a href="{{ url('/diklat') }}?kategori=beasiswa_external&search={{ $search }}&year={{ $year }}"
           class="tab-btn {{ $kategori === 'beasiswa_external' ? 'active' : '' }}">
            Program Beasiswa Pendidikan External
        </a>
    </div>

    {{-- Grid Program --}}
    <div class="program-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        @forelse($programs as $program)
            <a href="{{ url('/diklat/' . $program->id) }}" class="program-card-link" style="text-decoration: none; color: inherit; transition: transform 0.2s;">
                <div class="program-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #eee; height: 100%;">
                    <div class="program-image" style="background-image: url('{{ $program->image_path ? file_url($program->image_path) : asset('img/default-program.jpg') }}'); background-size: cover; background-position: center; height: 180px; width: 100%; position: relative;">
                        @if(!$program->image_path)
                            <div class="placeholder-img" style="height: 100%; background: #edf2f7; display: flex; align-items: center; justify-content: center; color: #a0aec0;">
                                <span>No Poster</span>
                            </div>
                        @endif
                        @php
                            $isExpired = $program->isRegistrationExpired();
                            $badgeBg   = ($program->status == 'open' && !$isExpired) ? '#38a169' : '#e53e3e';
                            $badgeText = ($program->status == 'open' && !$isExpired) ? 'Buka' : 'Tutup';
                        @endphp
                        <div style="position: absolute; top: 10px; right: 10px; background: {{ $badgeBg }}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                            {{ $badgeText }}
                        </div>
                    </div>
                    <div class="program-info" style="padding: 20px; text-align: left;">
                        <span class="badge badge-{{ $program->kategori === 'pelatihan' ? 'open' : 'applied' }}" style="font-size: 10px; text-transform: uppercase; margin-bottom: 8px; display: inline-block;">
                            {{ $program->kategori }}
                        </span>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #1a3a6c; line-height: 1.3; height: 2.6em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $program->title }}</h3>
                        
                        <div style="margin-top: 10px; font-size: 11px; color: #e53e3e; font-weight: 600;">
                            @if($isExpired)
                                🔒 Ditutup sejak {{ $program->end_date->format('d M Y') }}
                            @elseif($program->end_date)
                                ⏳ Tutup: {{ $program->end_date->format('d M Y') }}
                            @endif
                        </div>

                        <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #718096; border-top: 1px solid #f0f2f5; padding-top: 12px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                📅 {{ $program->start_date->format('Y') }}
                            </div>
                            @if($program->requirements_count > 0)
                                <div title="Memerlukan {{ $program->requirements_count }} berkas pendaftaran" style="color: #d9534f; font-weight: 600;">
                                    📎 {{ $program->requirements_count }} Berkas
                                </div>
                            @else
                                <div style="color: #38a169;">
                                    ✅ Bebas Berkas
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #718096; background: #edf2f7; border-radius: 12px;">
                <p style="font-size: 18px; margin-bottom: 10px;">Program tidak ditemukan.</p>
                <p style="font-size: 14px;">Silakan coba kata kunci lain atau filter tahun yang berbeda.</p>
            </div>
        @endforelse
    </div>

    <style>
        .program-card-link:hover {
            transform: translateY(-5px);
        }
        .program-card-link:hover .program-card {
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            border-color: #cbd5e0;
        }
    </style>

@endsection
