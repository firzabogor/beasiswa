@extends('layouts.app')

@section('title', 'Konsultasi Saya - Simdiklat')

@section('content')
    <h2>Konsultasi Kepegawaian</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ url('/konsultasi/buat') }}" class="btn btn-primary">+ Ajukan Konsultasi Baru</a>
    </div>

    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        @if($consultations->isEmpty())
            <div style="padding: 40px; text-align: center; color: #666;">
                <p style="font-size: 16px; margin: 0;">Belum ada ajuan konsultasi.</p>
                <a href="{{ url('/konsultasi/buat') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">
                    Ajukan Konsultasi
                </a>
            </div>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                        <th style="padding: 15px; text-align: left;">Tujuan</th>
                        <th style="padding: 15px; text-align: left;">Pimpinan</th>
                        <th style="padding: 15px; text-align: left;">Jadwal</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultations as $c)
                        @php
                            $statusColor = match($c->status) {
                                'approved' => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Disetujui'],
                                'rejected' => ['bg' => '#f8d7da', 'color' => '#721c24', 'label' => 'Ditolak'],
                                default    => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Menunggu'],
                            };
                        @endphp
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">{{ str_replace('_', ' ', ucfirst($c->tujuan)) }}</td>
                            <td style="padding: 15px;">{{ ucfirst($c->pimpinan) }}</td>
                            <td style="padding: 15px; font-size: 13px;">
                                {{ $c->jadwal->format('d M Y H:i') }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['color'] }}; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    {{ $statusColor['label'] }}
                                </span>
                            </td>
                        </tr>
                        @if($c->tanggapan)
                        <tr style="background: #f0fff4; border-bottom: 1px solid #eee;">
                            <td colspan="4" style="padding: 10px 15px; font-size: 13px; color: #2c7a7b;">
                                <div style="display: flex; gap: 8px; flex-direction: column;">
                                    <div style="font-weight: bold; font-size: 11px; text-transform: uppercase;">Tanggapan Admin ({{ $c->ditanggapi_pada->format('d/m/Y H:i') }}):</div>
                                    <div style="line-height: 1.5;">{{ $c->tanggapan }}</div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
