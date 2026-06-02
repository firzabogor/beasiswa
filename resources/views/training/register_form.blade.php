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
                {{-- TOPIK YANG DIPILIH (READ-ONLY) --}}
                {{-- ======================================================= --}}
                @if($program->topics->count() > 0)
                @php
                    $preselectedTopicId = request('topic_id');
                    $selectedTopic = $program->topics->firstWhere('id', $preselectedTopicId);
                @endphp

                @if($selectedTopic)
                <h4 style="margin-top: 0; color: #2d3748; border-bottom: 1px solid #edf2f7; padding-bottom: 10px; margin-bottom: 16px;">📚 Topik Program Yang Dipilih</h4>

                {{-- Hidden input to submit the topic --}}
                <input type="hidden" name="program_topic_id" value="{{ $selectedTopic->id }}">

                {{-- Read-only topic card --}}
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: linear-gradient(135deg, #f0f7ff, #e8f0fe); border: 2px solid #1a3a6c; border-radius: 10px; margin-bottom: 28px;">
                    <div style="margin-top: 2px; flex-shrink: 0;">
                        <div style="width: 22px; height: 22px; border-radius: 50%; background: #1a3a6c; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #1a3a6c; font-size: 14px; line-height: 1.4;">{{ $selectedTopic->name }}</div>
                        @if($selectedTopic->start_date && $selectedTopic->end_date)
                        <div style="font-size: 12px; color: #4a5568; margin-top: 6px; display: flex; flex-wrap: wrap; gap: 12px;">
                            <span>📅 <strong>Pendaftaran:</strong> {{ $selectedTopic->start_date->format('d M Y') }} – {{ $selectedTopic->end_date->format('d M Y') }}</span>
                            @if($selectedTopic->execution_start_date && $selectedTopic->execution_end_date)
                            <span>🗓️ <strong>Pelaksanaan:</strong> {{ $selectedTopic->execution_start_date->format('d M Y') }} – {{ $selectedTopic->execution_end_date->format('d M Y') }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    <a href="{{ url('/diklat/' . $program->id) }}" style="font-size: 11px; color: #718096; text-decoration: none; flex-shrink: 0; padding-top: 2px; white-space: nowrap;" title="Ubah topik">✏️ Ubah</a>
                </div>
                @endif
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


@endsection
