<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use App\Models\User;
use App\Models\CompanyApplication;
use App\Models\SelectionDocument;
use App\Models\Consultation;
use App\Models\EducationActivity;
use App\Models\ProgramRequirement;
use App\Models\ProgramTopic;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    // ================================================================
    // DASHBOARD
    // ================================================================
    public function dashboard(Request $request)
    {
        $data = [
            'total_programs'      => TrainingProgram::count(),
            'total_users'         => User::where('role', 'user')->count(),
            'pending_verif'       => TrainingRegistration::where('verification_status', 'pending')->count(),
            'total_registrations' => TrainingRegistration::count(),
            'recent_registrations'=> TrainingRegistration::with(['user', 'trainingProgram'])->latest()->take(5)->get(),
        ];

        // Filter untuk grafik
        $chartYear = $request->input('chart_year');
        $chartProgram = $request->input('chart_program');

        // Query program untuk grafik
        $chartQuery = TrainingProgram::withCount([
            'registrations as total_pendaftar',
            'registrations as lolos' => function ($query) {
                $query->where('verification_status', 'verified');
            },
            'registrations as tidak_lolos' => function ($query) {
                $query->where('verification_status', 'rejected');
            }
        ]);

        if ($chartYear) {
            $chartQuery->whereYear('start_date', $chartYear);
        }

        if ($chartProgram) {
            $chartQuery->where('title', 'like', '%' . $chartProgram . '%');
        }

        $programsForChart = $chartQuery->get();

        // Data array chart.js
        $chartLabels = [];
        $chartTotal = [];
        $chartLolos = [];
        $chartTidakLolos = [];

        foreach ($programsForChart as $prog) {
            $tahunLabel = $prog->start_date ? date('Y', strtotime($prog->start_date)) : '-';
            $kategoriLabel = ucfirst($prog->kategori);
            $chartLabels[] = "[{$tahunLabel}] [{$kategoriLabel}] {$prog->title}";
            $chartTotal[] = $prog->total_pendaftar;
            $chartLolos[] = $prog->lolos;
            $chartTidakLolos[] = $prog->tidak_lolos;
        }

        // Dropdown data
        $availableYears = TrainingProgram::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.dashboard', compact('data', 'chartLabels', 'chartTotal', 'chartLolos', 'chartTidakLolos', 'chartYear', 'chartProgram', 'availableYears'));
    }

    // ================================================================
    // MANAJEMEN PROGRAM PELATIHAN
    // ================================================================
    public function programs(Request $request)
    {
        $search = $request->input('search');
        $year   = $request->input('year');

        $query = TrainingProgram::withCount('requirements');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($year) {
            $query->whereYear('start_date', $year);
        }

        $programs = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Ambil daftar tahun unik dari start_date untuk filter
        $years = TrainingProgram::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.programs.index', compact('programs', 'search', 'year', 'years'));
    }

    public function createProgram()
    {
        return view('admin.programs.create');
    }


    public function storeProgram(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'kategori'    => 'required|in:pelatihan,pendidikan',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'location'    => 'required|string|max:255',
            'quota'       => 'required|integer|min:1',
            'status'      => 'required|in:open,closed',
            'execution_start_date' => 'required|date',
            'execution_end_date'   => 'required|date|after_or_equal:execution_start_date',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'template_report_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'template_report_2_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'title.required'       => 'Nama program wajib diisi.',
            'kategori.required'    => 'Kategori wajib dipilih.',
            'description.required' => 'Deskripsi wajib diisi.',
            'start_date.required'  => 'Tanggal mulai wajib diisi.',
            'end_date.required'    => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
            'location.required'    => 'Lokasi wajib diisi.',
            'quota.required'       => 'Kuota wajib diisi.',
            'quota.min'            => 'Kuota minimal 1 orang.',
            'image_file.image'     => 'File poster harus berupa gambar.',
            'document_file.mimes'  => 'Dokumen pendukung harus berupa PDF atau Office file.',
            'execution_start_date.required' => 'Tanggal mulai pelaksanaan wajib diisi.',
            'execution_end_date.required'   => 'Tanggal selesai pelaksanaan wajib diisi.',
            'execution_end_date.after_or_equal' => 'Tanggal selesai pelaksanaan harus setelah tanggal mulai.',
        ]);

        $data = $request->only(['title', 'kategori', 'description', 'start_date', 'end_date', 'location', 'quota', 'status', 'execution_start_date', 'execution_end_date']);

        if ($request->hasFile('image_file')) {
            $data['image_path'] = $request->file('image_file')->store('programs/images', 'public');
        }

        if ($request->hasFile('document_file')) {
            $data['document_path'] = $request->file('document_file')->store('programs/documents', 'public');
        }

        if ($request->hasFile('template_report_file')) {
            $data['template_report_path'] = $request->file('template_report_file')->store('programs/templates', 'public');
        }

        if ($request->hasFile('template_report_2_file')) {
            $data['template_report_2_path'] = $request->file('template_report_2_file')->store('programs/templates', 'public');
        }

        $program = TrainingProgram::create($data);

        // Simpan Syarat Berkas jika ada
        if ($request->has('requirements') && is_array($request->requirements)) {
            foreach ($request->requirements as $req) {
                if (!empty($req['name'])) {
                    ProgramRequirement::create([
                        'training_program_id' => $program->id,
                        'name'                => $req['name'],
                        'is_required'         => isset($req['is_required']) ? true : false,
                    ]);
                }
            }
        }

        // Simpan Topik jika ada
        if ($request->has('topics') && is_array($request->topics)) {
            foreach ($request->topics as $topic) {
                if (!empty($topic['name'])) {
                    ProgramTopic::create([
                        'training_program_id'  => $program->id,
                        'name'                 => $topic['name'],
                        'start_date'           => !empty($topic['start_date']) ? $topic['start_date'] : null,
                        'end_date'             => !empty($topic['end_date']) ? $topic['end_date'] : null,
                        'execution_start_date' => !empty($topic['execution_start_date']) ? $topic['execution_start_date'] : null,
                        'execution_end_date'   => !empty($topic['execution_end_date']) ? $topic['execution_end_date'] : null,
                    ]);
                }
            }
        }

        return redirect('/admin/programs')->with('success', 'Program dan syarat berkas berhasil ditambahkan.');
    }

    public function showProgram($id)
    {
        $program = TrainingProgram::with('requirements')->findOrFail($id);
        
        $registrants = $program->registrations()->count();
        $endorsements = $program->registrations()->whereNotNull('invitation_letter_path')->count();
        $finalPassed = $program->registrations()->where('selection_final_result', 'passed')->count();

        return view('admin.programs.show', compact('program', 'registrants', 'endorsements', 'finalPassed'));
    }

    public function editProgram($id)
    {
        $program = TrainingProgram::findOrFail($id);
        return view('admin.programs.edit', compact('program'));
    }

    public function updateProgram(Request $request, $id)
    {
        $program = TrainingProgram::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'kategori'    => 'required|in:pelatihan,pendidikan',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'location'    => 'required|string|max:255',
            'quota'       => 'required|integer|min:1',
            'status'      => 'required|in:open,closed',
            'execution_start_date' => 'required|date',
            'execution_end_date'   => 'required|date|after_or_equal:execution_start_date',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'template_report_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'template_report_2_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'title.required'       => 'Nama program wajib diisi.',
            'kategori.required'    => 'Kategori wajib dipilih.',
            'description.required' => 'Deskripsi wajib diisi.',
            'start_date.required'  => 'Tanggal mulai wajib diisi.',
            'end_date.required'    => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'location.required'    => 'Lokasi wajib diisi.',
            'quota.required'       => 'Kuota wajib diisi.',
            'quota.min'            => 'Kuota minimal 1 orang.',
            'image_file.image'     => 'File poster harus berupa gambar.',
            'document_file.mimes'  => 'Dokumen pendukung harus berupa PDF atau Office file.',
            'execution_start_date.required' => 'Tanggal mulai pelaksanaan wajib diisi.',
            'execution_end_date.required'   => 'Tanggal selesai pelaksanaan wajib diisi.',
            'execution_end_date.after_or_equal' => 'Tanggal selesai pelaksanaan harus setelah atau sama dengan tanggal mulai pelaksanaan.',
        ]);

        $data = $request->only(['title', 'kategori', 'description', 'start_date', 'end_date', 'location', 'quota', 'status', 'execution_start_date', 'execution_end_date']);

        if ($request->hasFile('image_file')) {
            // Optional: delete old image if needed, for simplicity we just store new one
            $data['image_path'] = $request->file('image_file')->store('programs/images', 'public');
        }

        if ($request->hasFile('document_file')) {
            $data['document_path'] = $request->file('document_file')->store('programs/documents', 'public');
        }

        if ($request->hasFile('template_report_file')) {
            $data['template_report_path'] = $request->file('template_report_file')->store('programs/templates', 'public');
        }

        if ($request->hasFile('template_report_2_file')) {
            $data['template_report_2_path'] = $request->file('template_report_2_file')->store('programs/templates', 'public');
        }

        $program->update($data);

        return redirect('/admin/programs')->with('success', 'Program berhasil diperbarui.');
    }

    public function deleteProgram($id)
    {
        $program = TrainingProgram::findOrFail($id);

        // Cek apakah sudah ada registrasi
        if ($program->registrations()->count() > 0) {
            return back()->with('error', 'Program tidak dapat dihapus karena sudah ada pendaftar.');
        }

        $program->delete();
        return redirect('/admin/programs')->with('success', 'Program berhasil dihapus.');
    }

    public function updateRequirement(Request $request, $id)
    {
        $requirement = ProgramRequirement::findOrFail($id);

        $request->validate([
            'name'        => 'required|string',
            'is_required' => 'nullable',
        ]);

        $requirement->update([
            'name'        => $request->name,
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Syarat berkas berhasil diperbarui.');
    }

    public function addRequirement(Request $request, $id)
    {
        $program = TrainingProgram::findOrFail($id);

        $request->validate([
            'requirement_name' => 'required|string',
            'is_required'      => 'boolean',
        ]);

        $program->requirements()->create([
            'name'        => $request->requirement_name,
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Syarat berkas berhasil ditambahkan.');
    }

    public function deleteRequirement($id)
    {
        $requirement = ProgramRequirement::findOrFail($id);
        $requirement->delete();

        return back()->with('success', 'Syarat berkas berhasil dihapus.');
    }

    public function updateTopic(Request $request, $id)
    {
        $topic = ProgramTopic::findOrFail($id);

        $request->validate([
            'name'                 => 'required|string|max:255',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'execution_start_date' => 'nullable|date',
            'execution_end_date'   => 'nullable|date|after_or_equal:execution_start_date',
        ], [
            'name.required' => 'Nama topik wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai pendaftaran harus setelah atau sama dengan tanggal mulai.',
            'execution_end_date.after_or_equal' => 'Tanggal selesai pelaksanaan harus setelah atau sama dengan tanggal mulai pelaksanaan.',
        ]);

        $topic->update([
            'name'                 => $request->name,
            'start_date'           => $request->start_date ?: null,
            'end_date'             => $request->end_date ?: null,
            'execution_start_date' => $request->execution_start_date ?: null,
            'execution_end_date'   => $request->execution_end_date ?: null,
        ]);

        return back()->with('success', 'Topik berhasil diperbarui.');
    }

    public function addTopic(Request $request, $id)
    {
        $program = TrainingProgram::findOrFail($id);

        $request->validate([
            'topic_name'           => 'required|string|max:255',
            'topic_start_date'     => 'nullable|date',
            'topic_end_date'       => 'nullable|date|after_or_equal:topic_start_date',
            'topic_exec_start'     => 'nullable|date',
            'topic_exec_end'       => 'nullable|date|after_or_equal:topic_exec_start',
        ], [
            'topic_name.required' => 'Nama topik wajib diisi.',
            'topic_end_date.after_or_equal' => 'Tanggal selesai pendaftaran harus setelah atau sama dengan tanggal mulai.',
            'topic_exec_end.after_or_equal' => 'Tanggal selesai pelaksanaan harus setelah atau sama dengan tanggal mulai pelaksanaan.',
        ]);

        $program->topics()->create([
            'name'                 => $request->topic_name,
            'start_date'           => $request->topic_start_date,
            'end_date'             => $request->topic_end_date,
            'execution_start_date' => $request->topic_exec_start,
            'execution_end_date'   => $request->topic_exec_end,
        ]);

        return back()->with('success', 'Topik berhasil ditambahkan.');
    }

    public function deleteTopic($id)
    {
        $topic = ProgramTopic::findOrFail($id);
        $topic->delete();

        return back()->with('success', 'Topik berhasil dihapus.');
    }

    // ================================================================
    // MANAJEMEN VERIFIKASI PENDAFTARAN
    // ================================================================
    public function verifications(Request $request)
    {
        $status = $request->input('status', 'pending');

        $registrations = TrainingRegistration::with(['user', 'trainingProgram'])
            ->withCount('requirementDocuments')
            ->where('verification_status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.verifications.index', compact('registrations', 'status'));
    }

    public function verifyForm($id)
    {
        $registration = TrainingRegistration::with(['user', 'trainingProgram', 'programTopic', 'companyApplication', 'requirementDocuments.requirement'])->findOrFail($id);
        return view('admin.verifications.form', compact('registration'));
    }

    public function verify(Request $request, $id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes'  => 'nullable|string|max:1000',
            'invitation_letter'   => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'verification_status.required' => 'Status verifikasi wajib dipilih.',
            'invitation_letter.mimes'      => 'Surat rekomendasi harus berformat PDF.',
            'invitation_letter.max'        => 'Ukuran file maksimal 10MB.',
        ]);

        $data = [
            'verification_status' => $request->verification_status,
            'verification_notes'  => $request->verification_notes,
        ];

        // Upload surat pengantar jika ada
        if ($request->hasFile('invitation_letter') && $request->verification_status === 'verified') {
            $path = $request->file('invitation_letter')->store('invitation-letters', 'public');
            $data['invitation_letter_path']          = $path;
            $data['invitation_letter_uploaded_at']   = now();
        }

        $registration->update($data);

        $msg = $request->verification_status === 'verified'
            ? 'Pendaftaran berhasil diverifikasi dan surat rekomendasi telah dikirim.'
            : 'Pendaftaran telah ditolak.';

        return redirect('/admin/verifikasi')->with('success', $msg);
    }

    public function exportRegistrations(Request $request)
    {
        $status = $request->input('status', 'all');
        $filename = 'Data_Pelamar_' . date('Ymd_His') . '.csv';

        $query = TrainingRegistration::with([
            'user', 
            'trainingProgram', 
            'companyApplication', 
            'requirementDocuments.requirement'
        ]);

        if ($status && $status !== 'all') {
            $query->where('verification_status', $status);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID Pendaftaran', 'NIP', 'Nama Lengkap', 'Email', 
            'Jabatan / Unit Kerja', 'Instansi / Perusahaan', 
            'Program Tujuan', 'Kategori Program', 'Tanggal Mendaftar', 
            'Status Verifikasi', 'Dokumen Persyaratan (Link)', 'Surat Rekomendasi (Link)'
        ];

        $callback = function() use($registrations, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM so Excel opens it with the correct encoding
            fputs($file, "\xEF\xBB\xBF");
            
            // Use semicolon separator to align with Indonesian / European Excel defaults
            fputcsv($file, $columns, ';');

            foreach ($registrations as $reg) {
                // Formatting documents
                $dokumenArr = [];
                if ($reg->requirementDocuments) {
                    foreach ($reg->requirementDocuments as $doc) {
                        $reqName = $doc->requirement->name ?? 'Dokumen';
                        $url = asset('storage/' . $doc->document_path);
                        $dokumenArr[] = $reqName . ': ' . $url;
                    }
                }
                $dokumenStr = implode("\n", $dokumenArr);

                $suratPengantarUrl = $reg->invitation_letter_path 
                    ? asset('storage/' . $reg->invitation_letter_path) 
                    : '-';

                $row = [
                    $reg->id,
                    $reg->user->nip ? '="' . $reg->user->nip . '"' : '-',
                    $reg->user->name ?? '-',
                    $reg->user->email ?? '-',
                    $reg->companyApplication->jabatan ?? $reg->user->position ?? '-',
                    $reg->companyApplication->nama_instansi ?? '-',
                    $reg->trainingProgram->title ?? '-',
                    $reg->trainingProgram ? ucfirst($reg->trainingProgram->kategori) : '-',
                    $reg->created_at->format('Y-m-d H:i:s'),
                    ucfirst($reg->verification_status),
                    $dokumenStr,
                    $suratPengantarUrl
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ================================================================
    // MANAJEMEN PENGGUNA
    // ================================================================
    public function users(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('role', 'user')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")->orWhere('nip', 'like', "%$search%")->orWhere('email', 'like', "%$search%"))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function userDetail($id)
    {
        $user = User::with(['registrations.trainingProgram', 'registrations.companyApplication'])->findOrFail($id);
        return view('admin.users.detail', compact('user'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nip'      => 'required|string|max:30|unique:users,nip',
            'position' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:user,admin',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'nip.required'      => 'NIP wajib diisi.',
            'nip.unique'        => 'NIP sudah terdaftar.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'     => $request->name,
            'nip'      => $request->nip,
            'position' => $request->position,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/admin/users')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'nip'      => 'required|string|max:30|unique:users,nip,' . $id,
            'position' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:user,admin',
        ]);

        $data = $request->only(['name', 'nip', 'position', 'email', 'role']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect('/admin/users')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Jangan hapus akun sendiri
        if (auth()->id() === (int)$id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect('/admin/users')->with('success', 'Akun pengguna berhasil dihapus.');
    }

    // ================================================================
    // LAPORAN / DOKUMEN SELEKSI
    // ================================================================
    public function selectionDocuments(Request $request)
    {
        $result = $request->input('result');
        $docs = SelectionDocument::with(['companyApplication.trainingRegistration.user',
                                          'companyApplication.trainingRegistration.trainingProgram'])
            ->when($result, fn($q) => $q->where('selection_result', $result))
            ->latest()
            ->paginate(15);

        return view('admin.documents.index', compact('docs', 'result'));
    }

    public function downloadSelectionDoc($id)
    {
        $doc = SelectionDocument::findOrFail($id);
        return response()->download(storage_path('app/public/' . $doc->document_path));
    }
    // ================================================================
    // MANAJEMEN KONSULTASI
    // ================================================================

    public function consultations()
    {
        $consultations = Consultation::with('user')->latest()->get();
        return view('admin.consultations.index', compact('consultations'));
    }

    public function showConsultation($id)
    {
        $consultation = Consultation::with('user')->findOrFail($id);
        return view('admin.consultations.show', compact('consultation'));
    }

    public function replyConsultation(Request $request, $id)
    {
        $request->validate([
            'tanggapan' => 'required|string|max:2000',
        ]);

        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'tanggapan'       => $request->tanggapan,
            'status'          => 'approved', // Automatically approve when replied
            'ditanggapi_pada' => now(),
        ]);

        return redirect('/admin/consultations/' . $id)
            ->with('success', 'Tanggapan berhasil dikirim.');
    }

    // ================================================================
    // MONITORING PENDIDIKAN
    // ================================================================

    public function monitoringPendidikan()
    {
        $registrations = TrainingRegistration::with(['user', 'trainingProgram', 'educationActivities'])
            ->whereHas('trainingProgram', function($q) {
                $q->where('kategori', 'pendidikan');
            })
            ->latest()
            ->paginate(15);

        return view('admin.education.monitoring', compact('registrations'));
    }

    public function exportMonitoringPendidikan()
    {
        $filename = 'Monitoring_Pendidikan_' . date('Ymd_His') . '.csv';

        $registrations = TrainingRegistration::with(['user', 'trainingProgram', 'educationActivities'])
            ->whereHas('trainingProgram', function($q) {
                $q->where('kategori', 'pendidikan');
            })
            ->latest()
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID Pendaftaran', 'NIP', 'Nama Lengkap', 'Program', 
            'LOG (Link)', 'LOA (Link)', 'Laporan Semester (Link)', 'Lap. Akhir Studi/KHS (Link)', 'Ijazah (Link)'
        ];

        $callback = function() use($registrations, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            foreach ($registrations as $reg) {
                $semesterLinks = [];
                foreach ($reg->educationActivities as $act) {
                    $semesterLinks[] = $act->semester_name . ': ' . asset('storage/' . $act->document_path);
                }
                $semesterStr = implode("\n", $semesterLinks);

                $row = [
                    $reg->id,
                    $reg->user->nip ? '="' . $reg->user->nip . '"' : '-',
                    $reg->user->name ?? '-',
                    $reg->trainingProgram->title ?? '-',
                    $reg->log_path ? asset('storage/' . $reg->log_path) : '-',
                    $reg->loa_path ? asset('storage/' . $reg->loa_path) : '-',
                    $semesterStr ? $semesterStr : '-',
                    $reg->final_report_path ? asset('storage/' . $reg->final_report_path) : '-',
                    $reg->final_doc_path ? asset('storage/' . $reg->final_doc_path) : '-'
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Monitoring Pelatihan - Laporan Akhir Peserta.
     */
    public function monitoringPelatihan()
    {
        $registrations = TrainingRegistration::with(['user', 'trainingProgram'])
            ->whereHas('trainingProgram', function($q) {
                $q->where('kategori', 'pelatihan');
            })
            ->whereNotNull('final_report_path')
            ->latest()
            ->paginate(15);

        return view('admin.training.monitoring', compact('registrations'));
    }

    public function exportMonitoringPelatihan()
    {
        $filename = 'Monitoring_Pelatihan_' . date('Ymd_His') . '.csv';

        $registrations = TrainingRegistration::with(['user', 'trainingProgram'])
            ->whereHas('trainingProgram', function($q) {
                $q->where('kategori', 'pelatihan');
            })
            ->whereNotNull('final_report_path')
            ->latest()
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID Pendaftaran', 'NIP', 'Nama Lengkap', 'Program', 'Laporan Akhir (Link)'
        ];

        $callback = function() use($registrations, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            foreach ($registrations as $reg) {
                $row = [
                    $reg->id,
                    $reg->user->nip ? '="' . $reg->user->nip . '"' : '-',
                    $reg->user->name ?? '-',
                    $reg->trainingProgram->title ?? '-',
                    $reg->final_report_path ? asset('storage/' . $reg->final_report_path) : '-'
                ];
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download Laporan Akhir Pelatihan.
     */
    public function downloadTrainingReport($id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        if (!$registration->final_report_path) {
            abort(404, 'Laporan tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $registration->final_report_path));
    }

    /**
     * Reset Laporan Akhir Pelatihan agar peserta bisa upload ulang.
     */
    public function resetTrainingReport($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        
        $registration->update([
            'final_report_path' => null,
        ]);

        return back()->with('success', 'Laporan akhir pelatihan berhasil di-reset. Peserta kini dapat mengunggah ulang laporan mereka.');
    }

    /**
     * Reset Berkas Akhir Pendidikan.
     */
    public function resetEducationReport($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        
        $registration->update([
            'final_report_path'      => null,
            'final_doc_path'         => null,
            'is_education_completed' => false,
        ]);

        return back()->with('success', 'Berkas akhir pendidikan berhasil di-reset. Status pendaftaran peserta kembali menjadi "Berlangsung".');
    }

    /**
     * Reset Berkas LOG.
     */
    public function resetLog($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        
        $registration->update([
            'log_path' => null,
        ]);

        return back()->with('success', 'Berkas LOG berhasil di-reset. Peserta kini dapat mengunggah ulang LOG mereka.');
    }

    /**
     * Reset Berkas LOA.
     */
    public function resetLoa($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        
        $registration->update([
            'loa_path' => null,
        ]);

        return back()->with('success', 'Berkas LOA berhasil di-reset. Peserta kini dapat mengunggah ulang LOA mereka.');
    }
}
