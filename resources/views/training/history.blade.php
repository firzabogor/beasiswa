@extends('layouts.app')

@section('title', 'Riwayat Diklat - Simdiklat')

@section('content')
    <h2>Riwayat Pendaftaran Diklat</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px; text-align: left;">Nama Diklat</th>
                    <th style="padding: 15px; text-align: left;">Jadwal</th>
                    <th style="padding: 15px; text-align: left;">Lokasi</th>
                    <th style="padding: 15px; text-align: left;">Status</th>
                    <th style="padding: 15px; text-align: left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dummy Data for History -->
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">Diklatpim Tingkat IV</td>
                    <td style="padding: 15px;">01 Mar 2024</td>
                    <td style="padding: 15px;">Jakarta</td>
                    <td style="padding: 15px;">
                        <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Pending</span>
                    </td>
                    <td style="padding: 15px;">
                        <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Detail</button>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px;">Workshop IT Security</td>
                    <td style="padding: 15px;">10 Apr 2024</td>
                    <td style="padding: 15px;">Online</td>
                    <td style="padding: 15px;">
                        <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Approved</span>
                    </td>
                    <td style="padding: 15px;">
                        <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Cetak Tiket</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
