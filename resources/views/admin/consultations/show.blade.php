@extends('layouts.admin')

@section('title', 'Detail Konsultasi - Admin')
@section('page-title', 'Tanggapi Konsultasi')

@section('content')

<div style="margin-bottom: 20px;">
    <a href="{{ url('/admin/consultations') }}" style="text-decoration: none; color: #666; font-size: 14px;">&larr; Kembali ke Daftar</a>
</div>

<div class="grid" style="grid-template-columns: 1fr 1fr; gap: 24px;">
    
    {{-- Detail Konsultasi --}}
    <div class="card">
        <div class="card-header">
            <h3>Rincian Pengajuan</h3>
        </div>
        <div style="padding: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:12px; color:#888; text-transform:uppercase; margin-bottom:4px;">Nama Pegawai</label>
                <div style="font-weight: 600; font-size: 16px;">{{ $consultation->user->name }}</div>
                <div style="font-size: 13px; color: #666;">NIP: {{ $consultation->user->nip }} | Jabatan: {{ $consultation->user->position }}</div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:12px; color:#888; text-transform:uppercase; margin-bottom:4px;">Tujuan & Pimpinan</label>
                <div style="font-weight: 500;">{{ str_replace('_', ' ', ucfirst($consultation->tujuan)) }}</div>
                <div style="font-size: 13px; color: #666;">Ditujukan Ke: {{ ucfirst($consultation->pimpinan) }}</div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:12px; color:#888; text-transform:uppercase; margin-bottom:4px;">Jadwal yang Diajukan</label>
                <div style="font-weight: 500; color: #2D3748;">{{ $consultation->jadwal->format('d F Y, H:i') }} WIB</div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:12px; color:#888; text-transform:uppercase; margin-bottom:4px;">Alasan Konsultasi</label>
                <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $consultation->alasan }}</div>
            </div>
        </div>
    </div>

    {{-- Tanggapan Admin --}}
    <div class="card">
        <div class="card-header">
            <h3>Tanggapan Administrator</h3>
        </div>
        <div style="padding: 20px;">
            @if($consultation->tanggapan)
                <div style="background: #e6fffa; border: 1px solid #38b2ac; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="font-size: 12px; color: #2c7a7b; text-transform: uppercase; margin-bottom: 8px; font-weight: bold;">Tanggapan Anda ({{ $consultation->ditanggapi_pada->format('d/m/Y H:i') }}):</div>
                    <div style="font-size: 14px; line-height: 1.6; color: #234e52; white-space: pre-wrap;">{{ $consultation->tanggapan }}</div>
                </div>
                
                <h4 style="margin-bottom: 12px; font-size: 14px;">Update Tanggapan?</h4>
            @else
                <div style="background: #fffaf0; border: 1px solid #ed8936; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #9c4221; font-size: 13px;">
                    Belum ada tanggapan untuk pengajuan ini. Isi formulir di bawah untuk menanggapi.
                </div>
            @endif

            <form action="{{ url('/admin/consultations/' . $consultation->id . '/reply') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="tanggapan">Isi Tanggapan</label>
                    <textarea name="tanggapan" id="tanggapan" class="form-control" rows="8" required placeholder="Tuliskan jawaban, konfirmasi jadwal, atau instruksi selanjutnya di sini...">{{ old('tanggapan', $consultation->tanggapan) }}</textarea>
                </div>
                
                <div style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        {{ $consultation->tanggapan ? '🔄 Perbarui Tanggapan' : '📩 Kirim Tanggapan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
