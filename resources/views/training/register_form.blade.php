@extends('layouts.app')

@section('title', 'Pendaftaran Program - Simdiklat')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/diklat/' . $program->id) }}" style="color: #666; text-decoration: none;">&larr; Kembali ke Detail Program</a>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="background: #1a3a6c; color: white; padding: 25px 30px;">
            <h2 style="margin: 0;">🚀 Konfirmasi Pendaftaran</h2>
            <p style="margin: 5px 0 0; opacity: 0.8; font-size: 14px;">{{ $program->title }}</p>
        </div>

        <div style="padding: 30px;">
            @if($errors->any())
                <div style="background: #fff5f5; border-left: 4px solid #f56565; color: #c53030; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/diklat/' . $program->id . '/daftar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ======================================================= --}}
                {{-- PILIHAN TOPIK --}}
                {{-- ======================================================= --}}
                @if($program->topics->count() > 0)
                @php $preselectedTopicId = request('topic_id'); @endphp

                <h4 style="margin-top: 0; color: #2d3748; border-bottom: 1px solid #edf2f7; padding-bottom: 10px; margin-bottom: 16px;">📚 Topik Program Yang Dipilih</h4>

                {{-- Info box showing selected topic dates --}}
                <div id="topic-info-box" style="background: linear-gradient(135deg,#f0f7ff,#e8f0fe); border:1px solid #c7d9f8; border-radius:10px; padding:14px 18px; margin-bottom:20px; display:none;">
                    <div style="font-weight:700; color:#1a3a6c; font-size:14px;" id="topic-info-name"></div>
                    <div style="font-size:13px; color:#4a5568; margin-top:6px; display:flex; flex-wrap:wrap; gap:16px;">
                        <span>📅 <strong>Pendaftaran:</strong> <span id="topic-info-reg"></span></span>
                        <span>🗓️ <strong>Pelaksanaan:</strong> <span id="topic-info-exec"></span></span>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 28px;">
                    @foreach($program->topics as $topic)
                    <label id="form-topic-label-{{ $topic->id }}"
                        style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; padding: 12px 16px; background: white; border: 2px solid {{ (string)$preselectedTopicId === (string)$topic->id ? '#1a3a6c' : '#e2e8f0' }}; border-radius: 8px; transition: all 0.2s; {{ (string)$preselectedTopicId === (string)$topic->id ? 'background:#f0f7ff;' : '' }}"
                        onclick="selectFormTopic({{ $topic->id }}, this)">
                        <input type="radio" name="program_topic_id" value="{{ $topic->id }}"
                            {{ (string)$preselectedTopicId === (string)$topic->id ? 'checked' : '' }}
                            data-name="{{ $topic->name }}"
                            data-start="{{ $topic->start_date ? $topic->start_date->format('d M Y') : '' }}"
                            data-end="{{ $topic->end_date ? $topic->end_date->format('d M Y') : '' }}"
                            data-exec-start="{{ $topic->execution_start_date ? $topic->execution_start_date->format('d M Y') : '' }}"
                            data-exec-end="{{ $topic->execution_end_date ? $topic->execution_end_date->format('d M Y') : '' }}"
                            required
                            style="accent-color: #1a3a6c; width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0;"
                            onchange="onFormTopicChange(this)">
                        <div>
                            <div style="font-weight: 600; color: #2d3748; font-size: 14px;">{{ $topic->name }}</div>
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
                @endif

                {{-- ======================================================= --}}
                {{-- UPLOAD SYARAT BERKAS --}}
                {{-- ======================================================= --}}
                <h4 style="margin-top: 0; color: #2d3748; border-bottom: 1px solid #edf2f7; padding-bottom: 10px; margin-bottom: 20px;">📎 Upload Syarat Berkas</h4>

                @if($program->requirements->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 30px;">
                        @foreach($program->requirements as $req)
                            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #edf2f7;">
                                <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px; font-size: 14px;">
                                    {{ $req->name }} @if($req->is_required) <span style="color: #e53e3e;">*</span> @endif
                                </label>
                                <input type="file" name="req_{{ $req->id }}" class="form-control" accept=".pdf,.jpg,.jpeg,.png" @if($req->is_required) required @endif style="border: 1px solid #cbd5e0; padding: 8px; border-radius: 4px; width: 100%; font-size: 13px;">
                                <small style="display: block; color: #718096; margin-top: 5px; font-size: 12px;">Format: PDF, JPG, PNG | Ukuran Maksimal: 5MB</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="background: #ebf8ff; color: #2b6cb0; padding: 15px; border-radius: 8px; border: 1px solid #bee3f8; margin-bottom: 30px; font-size: 14px;">
                        ℹ️ Program ini tidak memerlukan syarat berkas tambahan untuk pendaftaran awal. Silakan teruskan untuk mengirim pendaftaran.
                    </div>
                @endif

                {{-- ======================================================= --}}
                {{-- PERNYATAAN --}}
                {{-- ======================================================= --}}
                <div style="background: #fffaf0; border: 1px solid #feebc8; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <p style="margin: 0; color: #7b341e; font-size: 13px; line-height: 1.6;">
                        <strong>Pernyataan:</strong> Dengan menekan tombol daftar di bawah, saya menyatakan bahwa data dan berkas yang saya unggah adalah benar dan dapat dipertanggungjawabkan.
                    </p>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ url('/diklat/' . $program->id) }}" class="btn btn-secondary" style="padding: 10px 25px;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 35px; font-weight: 700;">
                        Kirim Pendaftaran &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function onFormTopicChange(radio) {
    const name      = radio.dataset.name;
    const start     = radio.dataset.start;
    const end       = radio.dataset.end;
    const execStart = radio.dataset.execStart;
    const execEnd   = radio.dataset.execEnd;

    // Update info box
    const box = document.getElementById('topic-info-box');
    document.getElementById('topic-info-name').textContent = name;
    document.getElementById('topic-info-reg').textContent  = (start && end) ? start + ' – ' + end : 'Belum diatur';
    document.getElementById('topic-info-exec').textContent = (execStart && execEnd) ? execStart + ' – ' + execEnd : 'Belum diatur';
    box.style.display = 'block';

    // Highlight selected label
    document.querySelectorAll('[id^="form-topic-label-"]').forEach(function(el) {
        el.style.borderColor = '#e2e8f0';
        el.style.backgroundColor = 'white';
    });
    const id = radio.value;
    const selectedLabel = document.getElementById('form-topic-label-' + id);
    if (selectedLabel) {
        selectedLabel.style.borderColor = '#1a3a6c';
        selectedLabel.style.backgroundColor = '#f0f7ff';
    }
}

function selectFormTopic(id, label) {
    const radio = label.querySelector('input[type=radio]');
    if (radio) {
        radio.checked = true;
        onFormTopicChange(radio);
    }
}

// Auto-trigger info box for pre-selected topic on page load
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="program_topic_id"]:checked');
    if (checked) onFormTopicChange(checked);
});
</script>
@endsection
