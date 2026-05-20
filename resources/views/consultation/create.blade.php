@extends('layouts.app')

@section('title', 'Ajukan Konsultasi - Simdiklat')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/konsultasi') }}" style="color: #666; text-decoration: none;">&larr; Kembali ke Daftar Konsultasi</a>
    </div>

    <h2>Ajukan Konsultasi Baru</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/konsultasi') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="tujuan">Tujuan Konsultasi</label>
            <select id="tujuan" name="tujuan" required
                    style="{{ $errors->has('tujuan') ? 'border-color: #dc3545;' : '' }}">
                <option value="">-- Pilih Tujuan --</option>
                <option value="konsultasi_teknis"  {{ old('tujuan') == 'konsultasi_teknis'  ? 'selected' : '' }}>Konsultasi Teknis</option>
                <option value="konsultasi_sdm"     {{ old('tujuan') == 'konsultasi_sdm'     ? 'selected' : '' }}>Konsultasi SDM</option>
                <option value="konsultasi_hukum"   {{ old('tujuan') == 'konsultasi_hukum'   ? 'selected' : '' }}>Konsultasi Hukum</option>
                <option value="konsultasi_lainnya" {{ old('tujuan') == 'konsultasi_lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pimpinan">Pimpinan yang Dituju</label>
            <select id="pimpinan" name="pimpinan" required
                    style="{{ $errors->has('pimpinan') ? 'border-color: #dc3545;' : '' }}">
                <option value="">-- Pilih Pimpinan --</option>
                <option value="direktur" {{ old('pimpinan') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                <option value="manajer"  {{ old('pimpinan') == 'manajer'  ? 'selected' : '' }}>Manajer</option>
                <option value="kepala_bagian" {{ old('pimpinan') == 'kepala_bagian' ? 'selected' : '' }}>Kepala Bagian</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jadwal">Jadwal Konsultasi</label>
            <input type="datetime-local" id="jadwal" name="jadwal"
                   value="{{ old('jadwal') }}"
                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                   required
                   style="{{ $errors->has('jadwal') ? 'border-color: #dc3545;' : '' }}">
        </div>

        <div class="form-group">
            <label for="alasan">Alasan / Topik Konsultasi</label>
            <textarea id="alasan" name="alasan" required placeholder="Jelaskan topik yang ingin dikonsultasikan..."
                      style="{{ $errors->has('alasan') ? 'border-color: #dc3545;' : '' }}">{{ old('alasan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Ajuan</button>
        <a href="{{ url('/konsultasi') }}" class="btn btn-secondary" style="margin-left: 10px;">Batal</a>
    </form>
@endsection
