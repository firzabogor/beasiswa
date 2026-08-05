@extends('layouts.app')

@section('title', $program->title . ' - Simdiklat')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/diklat') }}" style="color: #666; text-decoration: none;">&larr; Kembali ke Daftar</a>
    </div>

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

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
        {{-- Hero Image / Poster --}}
        @if($program->image_path)
            <div style="width: 100%; height: 400px; background-image: url('{{ file_url($program->image_path) }}'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.7)); padding: 40px 30px 20px;">
                    <h1 style="color: white; margin: 0; font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $program->title }}</h1>
                </div>
            </div>
        @else
            <div style="padding: 40px 30px 20px; background: #003366;">
                <h1 style="color: white; margin: 0; font-size: 2.5rem;">{{ $program->title }}</h1>
            </div>
        @endif

        <div style="padding: 30px;">

            {{-- ============================================================ --}}
            {{-- PILIHAN TOPIK (DI ATAS DESKRIPSI) --}}
            {{-- ============================================================ --}}
            @if($program->topics->count() > 0)
            <div id="topic-selector-box" style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%); border: 1px solid #c7d9f8; border-radius: 12px; padding: 20px 24px; margin-bottom: 30px;">
                <h4 style="margin: 0 0 4px 0; color: #1a3a6c; font-size: 16px;">📚 Pilih Topik Program</h4>
                <p style="margin: 0 0 16px 0; font-size: 13px; color: #4a5568;">Pilih salah satu topik di bawah ini untuk melihat jadwal pendaftaran dan pelaksanaannya.</p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($program->topics as $topic)
                    @php
                        $isExpired = $topic->end_date && $topic->end_date->startOfDay() < now()->startOfDay();
                    @endphp
                    <label id="topic-label-{{ $topic->id }}"
                        style="display: flex; align-items: flex-start; gap: 12px; cursor: {{ $isExpired ? 'not-allowed' : 'pointer' }}; padding: 12px 16px; background: {{ $isExpired ? '#f7fafc' : 'white' }}; border: 2px solid #e2e8f0; border-radius: 8px; transition: all 0.2s; user-select: none; opacity: {{ $isExpired ? '0.65' : '1' }};"
                        @if(!$isExpired)
                        onmouseover="this.style.borderColor='#4a90d9'"
                        onmouseout="updateTopicHover({{ $topic->id }})"
                        onclick="selectTopic({{ $topic->id }}, this)"
                        @endif>
                        <input type="radio" name="topic_id_display" value="{{ $topic->id }}"
                            {{ $isExpired ? 'disabled' : '' }}
                            data-start="{{ $topic->start_date ? $topic->start_date->format('d M Y') : '' }}"
                            data-end="{{ $topic->end_date ? $topic->end_date->format('d M Y') : '' }}"
                            data-exec-start="{{ $topic->execution_start_date ? $topic->execution_start_date->format('d M Y') : '' }}"
                            data-exec-end="{{ $topic->execution_end_date ? $topic->execution_end_date->format('d M Y') : '' }}"
                            style="accent-color: #1a3a6c; width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0;"
                            onchange="onTopicChange(this)">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2d3748; font-size: 14px; display: flex; justify-content: space-between; align-items: center;">
                                {{ $topic->name }}
                                @if($isExpired)
                                <span style="font-size: 10px; font-weight: 700; background: #fed7d7; color: #c53030; padding: 2px 6px; border-radius: 4px;">DITUTUP</span>
                                @endif
                            </div>
                            @if($topic->start_date && $topic->end_date)
                            <div style="font-size: 12px; color: #718096; margin-top: 3px;">
                                📅 Pendaftaran: {{ $topic->start_date->format('d M Y') }} – {{ $topic->end_date->format('d M Y') }}
                                @if($topic->execution_start_date && $topic->execution_end_date)
                                &nbsp;|&nbsp; 🗓️ Pelaksanaan: {{ $topic->execution_start_date->format('d M Y') }} – {{ $topic->execution_end_date->format('d M Y') }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- DESKRIPSI & SIDEBAR --}}
            {{-- ============================================================ --}}
            <div style="display: flex; flex-wrap: wrap; gap: 30px; margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 30px;">
                <div style="flex: 1; min-width: 300px;">
                    <h3>Deskripsi Program</h3>
                    <p style="line-height: 1.8; color: #4a5568; white-space: pre-line;">{{ $program->description }}</p>
                </div>

                <div style="width: 300px; flex-shrink: 0;">
                    <div style="background: #f7fafc; padding: 20px; border-radius: 12px; border: 1px solid #edf2f7;">
                        <h4 style="margin-top: 0; color: #2d3748;">Informasi Detail</h4>
                        <div style="font-size: 14px; display: flex; flex-direction: column; gap: 12px; color: #4a5568;">
                            <div><strong>📍 Lokasi:</strong><br>{{ $program->location }}</div>

                            <div>
                                <strong>📅 Tanggal Pendaftaran:</strong><br>
                                <span id="sidebar-reg-date">
                                    @if($program->topics->count() > 0)
                                        <span style="color: #a0aec0; font-style: italic;">Pilih topik terlebih dahulu</span>
                                    @else
                                        {{ $program->start_date->format('d M Y') }} - {{ $program->end_date->format('d M Y') }}
                                    @endif
                                </span>
                            </div>

                            <div>
                                <strong>🗓️ Tanggal Pelaksanaan:</strong><br>
                                <span id="sidebar-exec-date">
                                    @if($program->topics->count() > 0)
                                        <span style="color: #a0aec0; font-style: italic;">Pilih topik terlebih dahulu</span>
                                    @elseif($program->execution_start_date && $program->execution_end_date)
                                        {{ $program->execution_start_date->format('d M Y') }} - {{ $program->execution_end_date->format('d M Y') }}
                                    @else
                                        <span style="color: #a0aec0; font-style: italic;">Belum ditentukan</span>
                                    @endif
                                </span>
                            </div>

                            <div><strong>👥 Kuota:</strong><br>{{ $program->quota }} Peserta</div>
                            <div><strong>🏷️ Kategori:</strong><br>{{ ucfirst($program->kategori) }}</div>

                            @if($program->document_path)
                                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e0;">
                                    <strong>📄 Dokumen Pendukung:</strong><br>
                                    <a href="{{ file_url($program->document_path) }}" target="_blank" class="btn btn-secondary btn-block" style="margin-top: 5px; font-size: 12px; text-align: center;">Download Dokumen Pendukung</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TOMBOL DAFTAR --}}
            {{-- ============================================================ --}}
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div>
                    @if($alreadyRegistered)
                        <div style="background: #e6fffa; color: #2c7a7b; padding: 15px 25px; border-radius: 8px; border: 1px solid #b2f5ea; display: flex; align-items: center; gap: 10px;">
                            <span>✅ Anda sudah terdaftar di program ini.</span>
                            <a href="{{ url('/riwayat-diklat') }}" style="font-weight: bold; color: #285e61;">Lihat Status &rarr;</a>
                        </div>
                    @elseif($program->isRegistrationExpired())
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <button class="btn btn-secondary" disabled style="padding: 15px 40px; border-radius: 8px; opacity:0.7;">🔒 Pendaftaran Ditutup</button>
                            <span style="font-size:13px;color:#e53e3e;">⏰ Masa pendaftaran telah berakhir pada <strong>{{ $program->end_date->format('d M Y') }}</strong>.</span>
                        </div>
                    @elseif($program->status == 'open')
                        @if($program->topics->count() > 0)
                        <a id="btn-daftar"
                            href="#"
                            class="btn btn-secondary"
                            style="padding: 15px 40px; font-size: 1.1rem; border-radius: 8px; text-decoration: none; display: inline-block; opacity: 0.6; pointer-events: none;"
                            title="Pilih topik terlebih dahulu">
                            🚀 Daftar Sekarang
                        </a>
                        <p id="btn-daftar-hint" style="margin: 8px 0 0; font-size: 13px; color: #e53e3e;">
                            ⚠️ Pilih topik di atas terlebih dahulu untuk mendaftar.
                        </p>
                        @else
                        <a href="{{ url('/diklat/' . $program->id . '/daftar') }}" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; border-radius: 8px; text-decoration: none; display: inline-block;">
                            🚀 Daftar Sekarang
                        </a>
                        @endif
                    @else
                        <button class="btn btn-secondary" disabled style="padding: 15px 40px; border-radius: 8px;">Pendaftaran Ditutup</button>
                    @endif
                </div>

                @if($program->isRegistrationOpen() && !$alreadyRegistered)
                    <div style="color: #718096; font-size: 14px;">
                        Pendaftaran ditutup pada: <strong style="color:#c05621;">{{ $program->end_date->format('d M Y') }}</strong>
                    </div>
                @endif
            </div>
            </div>

            {{-- Statistik Pendaftar --}}
            <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 30px;">
                <h3 style="margin-bottom: 20px; color: #1a3a6c; display: flex; align-items: center; gap: 8px;">📊 Statistik Pendaftar</h3>
                <div style="max-width: 800px; margin: 0 auto;">
                    <canvas id="programStatChart" width="400" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// =============================================
// Chart
// =============================================
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('programStatChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendaftar', 'Endorsement BPSDMP', 'Lolos Seleksi Akhir'],
            datasets: [{
                label: 'Jumlah Peserta',
                data: [{{ $registrants }}, {{ $endorsements }}, {{ $finalPassed }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
});

// =============================================
// Topic Selection → Update Sidebar Dates
// =============================================
let selectedTopicId = null;

function onTopicChange(radio) {
    selectedTopicId = radio.value;

    const regDate   = document.getElementById('sidebar-reg-date');
    const execDate  = document.getElementById('sidebar-exec-date');
    const btnDaftar = document.getElementById('btn-daftar');
    const btnHint   = document.getElementById('btn-daftar-hint');

    const start     = radio.dataset.start;
    const end       = radio.dataset.end;
    const execStart = radio.dataset.execStart;
    const execEnd   = radio.dataset.execEnd;

    // Update registration date
    if (start && end) {
        regDate.innerHTML = '<strong style="color:#276749;">' + start + ' – ' + end + '</strong>';
    } else {
        regDate.innerHTML = '<span style="color:#a0aec0;font-style:italic;">Belum diatur</span>';
    }

    // Update execution date
    if (execStart && execEnd) {
        execDate.innerHTML = '<strong style="color:#276749;">' + execStart + ' – ' + execEnd + '</strong>';
    } else {
        execDate.innerHTML = '<span style="color:#a0aec0;font-style:italic;">Belum diatur</span>';
    }

    // Enable register button
    if (btnDaftar) {
        btnDaftar.href = '{{ url('/diklat/' . $program->id . '/daftar') }}?topic_id=' + selectedTopicId;
        btnDaftar.classList.remove('btn-secondary');
        btnDaftar.classList.add('btn-primary');
        btnDaftar.style.opacity = '1';
        btnDaftar.style.pointerEvents = 'auto';
    }
    if (btnHint) btnHint.style.display = 'none';

    // Highlight selected label
    document.querySelectorAll('[id^="topic-label-"]').forEach(function(el) {
        el.style.borderColor = '#e2e8f0';
        el.style.backgroundColor = 'white';
    });
    const selectedLabel = document.getElementById('topic-label-' + selectedTopicId);
    if (selectedLabel) {
        selectedLabel.style.borderColor = '#1a3a6c';
        selectedLabel.style.backgroundColor = '#f0f7ff';
    }
}

function selectTopic(id, label) {
    const radio = label.querySelector('input[type=radio]');
    if (radio) {
        radio.checked = true;
        onTopicChange(radio);
    }
}

function updateTopicHover(id) {
    if (String(id) !== String(selectedTopicId)) {
        const el = document.getElementById('topic-label-' + id);
        if (el) {
            el.style.borderColor = '#e2e8f0';
            el.style.backgroundColor = 'white';
        }
    }
}
</script>
@endsection
