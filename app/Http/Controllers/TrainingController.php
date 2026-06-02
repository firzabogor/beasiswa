<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use App\Models\RegistrationDocument;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /**
     * Daftar semua program pelatihan yang tersedia.
     */
    public function index(Request $request)
    {
        $kategori = $request->input('kategori', 'pelatihan');
        $search   = $request->input('search');
        $year     = $request->input('year');

        $programs = TrainingProgram::withCount('requirements')->where('kategori', $kategori)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('start_date', $year);
            })
            ->orderBy('start_date')
            ->get();

        // Get all unique years for the filter
        $years = TrainingProgram::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('training.index', compact('programs', 'kategori', 'search', 'year', 'years'));
    }

    /**
     * Detail satu program pelatihan.
     */
    public function show($id)
    {
        $program = TrainingProgram::with('topics')->findOrFail($id);

        // Cek apakah user sudah mendaftar program ini
        $alreadyRegistered = false;
        if (Auth::check()) {
            $alreadyRegistered = TrainingRegistration::where('user_id', Auth::id())
                ->where('training_program_id', $id)
                ->exists();
        }

        // Hitung statistik program
        $registrants = $program->registrations()->count();
        $endorsements = $program->registrations()->whereNotNull('invitation_letter_path')->count();
        $finalPassed = $program->registrations()->where('selection_final_result', 'passed')->count();

        return view('training.show', compact('program', 'alreadyRegistered', 'registrants', 'endorsements', 'finalPassed'));
    }

    /**
     * Tampilkan form pendaftaran (dengan input syarat berkas).
     */
    public function showRegisterForm($id)
    {
        $program = TrainingProgram::with(['requirements', 'topics'])->findOrFail($id);

        if (!$program->isRegistrationOpen()) {
            $msg = $program->isRegistrationExpired()
                ? 'Maaf, masa pendaftaran program ini telah berakhir pada ' . $program->end_date->format('d M Y') . '.'
                : 'Program pendaftaran sudah ditutup.';
            return redirect('/diklat/' . $id)->with('error', $msg);
        }

        // Cek apakah user sudah mendaftar
        $alreadyRegistered = TrainingRegistration::where('user_id', Auth::id())
            ->where('training_program_id', $id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect('/diklat/' . $id)->with('error', 'Anda sudah terdaftar di program ini.');
        }

        // Jika tidak ada syarat berkas khusus, langsung saja daftar?
        // User minta form upload, jadi pendaftaran dialihkan kesini.
        return view('training.register_form', compact('program'));
    }

    /**
     * Daftarkan user ke program pelatihan dengan mengunggah berkas.
     */
    public function register(Request $request, $id)
    {
        $program = TrainingProgram::with(['requirements', 'topics'])->findOrFail($id);

        // Validasi dasar
        if (!$program->isRegistrationOpen()) {
            $msg = $program->isRegistrationExpired()
                ? 'Maaf, masa pendaftaran program ini telah berakhir pada ' . $program->end_date->format('d M Y') . '.'
                : 'Program ini sudah ditutup.';
            return back()->with('error', $msg);
        }

        if (!$program->hasQuota()) {
            return back()->with('error', 'Maaf, kuota program ini sudah penuh.');
        }

        $alreadyRegistered = TrainingRegistration::where('user_id', Auth::id())
            ->where('training_program_id', $id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'Anda sudah mendaftarkan diri ke program ini.');
        }

        // Validasi berkas berdasarkan requirements
        $rules = [];
        $messages = [];
        foreach ($program->requirements as $req) {
            $key = 'req_' . $req->id;
            $rules[$key] = ($req->is_required ? 'required|' : 'nullable|') . 'file|mimes:pdf,jpg,jpeg,png|max:5120';
            $messages[$key . '.required'] = 'Berkas ' . $req->name . ' wajib diunggah.';
            $messages[$key . '.mimes']    = 'Format berkas ' . $req->name . ' harus PDF, JPG, atau PNG.';
        }

        if ($program->topics->count() > 0) {
            $rules['program_topic_id'] = 'required|exists:program_topics,id';
            $messages['program_topic_id.required'] = 'Anda harus memilih salah satu topik program.';
        }

        $request->validate($rules, $messages);

        // Validasi kadaluarsa topik
        if ($request->filled('program_topic_id')) {
            $topic = $program->topics->firstWhere('id', $request->program_topic_id);
            if ($topic && $topic->end_date && $topic->end_date->startOfDay() < now()->startOfDay()) {
                return back()->with('error', 'Maaf, masa pendaftaran untuk topik "' . $topic->name . '" telah berakhir.');
            }
        }

        // Create registration
        $registration = TrainingRegistration::create([
            'user_id'             => Auth::id(),
            'training_program_id' => $id,
            'program_topic_id'    => $request->program_topic_id,
            'status'              => 'pending',
            'registered_at'       => now(),
            'verification_status' => 'pending',
        ]);

        // Save documents
        foreach ($program->requirements as $req) {
            $key = 'req_' . $req->id;
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('registrations/documents', 'public');
                RegistrationDocument::create([
                    'training_registration_id' => $registration->id,
                    'program_requirement_id'   => $req->id,
                    'document_path'            => $path,
                ]);
            }
        }

        return redirect('/dashboard')
            ->with('success', 'Pendaftaran program "' . $program->title . '" berhasil dikirim! Silakan tunggu verifikasi admin.');
    }

    /**
     * Riwayat pendaftaran diklat milik user yang sedang login.
     */
    public function history()
    {
        $registrations = TrainingRegistration::with('trainingProgram')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('training.history', compact('registrations'));
    }
}
