@extends('layouts.app')

@section('title', 'Riwayat Pelatihan - Simdiklat')

@section('content')
    <h2>Riwayat Pendaftaran Pelatihan</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        @if($registrations->isEmpty())
            <div style="padding: 40px; text-align: center; color: #666;">
                <p style="font-size: 16px; margin: 0;">Anda belum mendaftar program pelatihan manapun.</p>
                <a href="{{ url('/diklat') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">Lihat Program Pelatihan</a>
            </div>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                        <th style="padding: 15px; text-align: left;">Nama Program</th>
                        <th style="padding: 15px; text-align: left;">Jadwal</th>
                        <th style="padding: 15px; text-align: left;">Lokasi</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                        <th style="padding: 15px; text-align: left;">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                        @php
                            $statusColor = match($reg->status) {
                                'approved' => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Disetujui'],
                                'rejected' => ['bg' => '#f8d7da', 'color' => '#721c24', 'label' => 'Ditolak'],
                                default    => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Menunggu'],
                            };
                        @endphp
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 500;">
                                {{ $reg->trainingProgram->title ?? '-' }}
                            </td>
                            <td style="padding: 15px; font-size: 13px;">
                                @if($reg->trainingProgram)
                                    {{ $reg->trainingProgram->start_date->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 15px; font-size: 13px;">
                                {{ $reg->trainingProgram->location ?? '-' }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['color'] }}; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    {{ $statusColor['label'] }}
                                </span>
                            </td>
                            <td style="padding: 15px; font-size: 13px; color: #666;">
                                {{ $reg->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
