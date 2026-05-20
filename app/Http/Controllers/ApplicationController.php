<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingRegistration;
use App\Models\CompanyApplication;
use App\Models\SelectionDocument;
use App\Models\EducationActivity;
use App\Models\User;

class ApplicationController extends Controller
{
    /**
     * Dashboard user — tampilkan semua registrasi dan statusnya.
     */
    public function dashboard(Request $request)
    {
        $year = $request->input('year');

        $registrations = TrainingRegistration::with(['trainingProgram', 'programTopic', 'companyApplication.selectionDocument'])
            ->where('user_id', Auth::id())
            ->when($year, function ($query, $year) {
                return $query->whereHas('trainingProgram', function ($q) use ($year) {
                    $q->whereYear('start_date', $year);
                });
            })
            ->latest()
            ->get();

        // Get unique years for filter
        $years = TrainingRegistration::where('user_id', Auth::id())
            ->whereHas('trainingProgram')
            ->get()
            ->pluck('trainingProgram.start_date')
            ->map->format('Y')
            ->unique()
            ->sortDesc();

        return view('dashboard.index', compact('registrations', 'year', 'years'));
    }

    /**
     * Form pendaftaran ke perusahaan tujuan.
     */
    public function applyCompany($registrationId)
    {
        $registration = TrainingRegistration::with('trainingProgram')
            ->where('user_id', Auth::id())
            ->findOrFail($registrationId);

        // Harus sudah verified dan punya surat pengantar
        if (!$registration->isVerified() || !$registration->hasInvitationLetter()) {
            return redirect('/dashboard')->with('error', 'Anda belum memiliki surat rekomendasi dari admin.');
        }

        // Cek belum daftar perusahaan
        if ($registration->companyApplication) {
            return redirect('/dashboard')->with('error', 'Anda sudah mendaftarkan diri ke perusahaan.');
        }

        return view('dashboard.company-apply', compact('registration'));
    }

    /**
     * Simpan pendaftaran ke perusahaan.
     */
    public function storeCompanyApplication(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        if (!$registration->isVerified() || !$registration->hasInvitationLetter() || $registration->companyApplication) {
            return redirect('/dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'company_name'     => 'required|string|max:255',
            'company_address'  => 'required|string',
            'applied_position' => 'required|string|max:255',
            'selection_result' => 'required|in:passed,not_passed',
            'document'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes'            => 'nullable|string|max:500',
        ], [
            'company_name.required'     => 'Nama perusahaan wajib diisi.',
            'company_address.required'  => 'Alamat perusahaan wajib diisi.',
            'applied_position.required' => 'Posisi yang dilamar wajib diisi.',
            'selection_result.required' => 'Hasil seleksi wajib dipilih.',
            'document.required'         => 'File dokumen hasil seleksi wajib diupload.',
        ]);

        $app = CompanyApplication::create([
            'training_registration_id' => $registration->id,
            'company_name'             => $request->company_name,
            'company_address'          => $request->company_address,
            'applied_position'         => $request->applied_position,
            'status'                   => $request->selection_result === 'passed' ? 'selected' : 'not_selected',
        ]);

        $path = $request->file('document')->store('selection-documents', 'public');

        SelectionDocument::create([
            'company_application_id' => $app->id,
            'document_path'          => $path,
            'selection_result'       => $request->selection_result,
            'notes'                  => $request->notes,
        ]);

        return redirect('/dashboard')->with('success', 'Berhasil mengunggah hasil seleksi. Proses pendaftaran selesai.');
    }

    /**
     * Form upload surat seleksi dari perusahaan.
     */
    public function uploadSelection($applicationId)
    {
        $application = CompanyApplication::whereHas('trainingRegistration', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($applicationId);

        if ($application->selectionDocument) {
            return redirect('/dashboard')->with('error', 'Dokumen seleksi sudah pernah diupload.');
        }

        return view('dashboard.upload-selection', compact('application'));
    }

    /**
     * Simpan dokumen hasil seleksi.
     */
    public function storeSelectionDocument(Request $request, $applicationId)
    {
        $application = CompanyApplication::whereHas('trainingRegistration', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($applicationId);

        if ($application->selectionDocument) {
            return redirect('/dashboard')->with('error', 'Dokumen sudah diupload sebelumnya.');
        }

        $request->validate([
            'selection_result' => 'required|in:passed,not_passed',
            'document'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes'            => 'nullable|string|max:500',
        ], [
            'selection_result.required' => 'Hasil seleksi wajib dipilih.',
            'document.required'         => 'File dokumen wajib diupload.',
            'document.mimes'            => 'Format file harus PDF, JPG, atau PNG.',
            'document.max'              => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('document')->store('selection-documents', 'public');

        SelectionDocument::create([
            'company_application_id' => $application->id,
            'document_path'          => $path,
            'selection_result'       => $request->selection_result,
            'notes'                  => $request->notes,
        ]);

        // Update status company application
        $application->update([
            'status' => $request->selection_result === 'passed' ? 'selected' : 'not_selected',
        ]);

        return redirect('/dashboard')->with('success', 'Dokumen hasil seleksi berhasil diupload. Proses selesai.');
    }

    /**
     * Download surat pengantar.
     */
    public function downloadInvitation($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        if (!$registration->invitation_letter_path) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $registration->invitation_letter_path));
    }

    // ================================================================
    // WORKFLOW PENDIDIKAN (BEASISWA)
    // ================================================================

    /**
     * Form upload LOG & LOA (setelah lolos seleksi beasiswa).
     */
    public function uploadLogLoa($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);
        
        // Cek jika sudah diupload
        if ($registration->log_path && $registration->loa_path) {
            return redirect('/dashboard')->with('error', 'Berkas LOG & LOA sudah pernah diupload.');
        }

        return view('dashboard.education.upload-log-loa', compact('registration'));
    }

    /**
     * Simpan hasil seleksi akhir beasiswa.
     */
    public function storeLogLoa(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        $rules = [
            'selection_final_result' => 'required|in:passed,not_passed',
        ];
        $messages = [
            'selection_final_result.required' => 'Hasil seleksi wajib dipilih.',
        ];

        // Jika lolos, LOG & LOA wajib
        if ($request->selection_final_result === 'passed') {
            $rules['log_file'] = 'required|file|mimes:pdf|max:5120';
            $rules['loa_file'] = 'required|file|mimes:pdf|max:5120';
            $messages['log_file.required'] = 'File LOG wajib diupload jika lolos seleksi.';
            $messages['loa_file.required'] = 'File LOA wajib diupload jika lolos seleksi.';
        } else {
            // Jika tidak lolos, dokumen bukti seleksi wajib
            $rules['selection_final_doc'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $messages['selection_final_doc.required'] = 'Dokumen bukti hasil seleksi wajib diupload jika tidak lolos.';
        }

        $request->validate($rules, $messages);

        $updateData = [
            'selection_final_result'   => $request->selection_final_result,
        ];

        // Simpan dokumen bukti seleksi jika tidak lolos
        if ($request->selection_final_result === 'not_passed' && $request->hasFile('selection_final_doc')) {
            $selDocPath = $request->file('selection_final_doc')->store('education/selection-docs', 'public');
            $updateData['selection_final_doc_path'] = $selDocPath;
        }

        // Jika lolos, simpan LOG & LOA
        if ($request->selection_final_result === 'passed') {
            $updateData['log_path'] = $request->file('log_file')->store('education/logs', 'public');
            $updateData['loa_path'] = $request->file('loa_file')->store('education/loas', 'public');
        }

        $registration->update($updateData);

        if ($request->selection_final_result === 'passed') {
            return redirect('/dashboard')->with('success', 'Selamat! Anda lolos seleksi akhir beasiswa. Silakan lanjutkan ke tahapan Laporan Akhir.');
        } else {
            return redirect('/dashboard')->with('success', 'Dokumen hasil seleksi berhasil disimpan. Proses pendaftaran Anda berakhir di tahapan ini.');
        }
    }

    /**
     * Form tambah aktivitas/laporan semester.
     */
    public function createActivity($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);
        
        $nextSemesterNumber = $registration->educationActivities()->count() + 1;
        $nextSemesterName = 'Semester ' . $nextSemesterNumber;

        return view('dashboard.education.add-activity', compact('registration', 'nextSemesterName'));
    }

    /**
     * Simpan aktivitas/laporan semester.
     */
    public function storeActivity(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        $request->validate([
            'semester_name' => 'required|string|max:100',
            'document'      => 'required|file|mimes:pdf|max:5120',
            'notes'         => 'nullable|string|max:500',
        ]);

        $path = $request->file('document')->store('education/activities', 'public');

        EducationActivity::create([
            'training_registration_id' => $registration->id,
            'semester_name'            => $request->semester_name,
            'document_path'            => $path,
            'notes'                   => $request->notes,
        ]);

        return redirect('/dashboard')->with('success', 'Laporan aktivitas semester berhasil ditambahkan.');
    }

    /**
     * Form upload berkas akhir (selesai pendidikan).
     */
    public function uploadFinalDoc($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);
        return view('dashboard.education.upload-final', compact('registration'));
    }

    /**
     * Simpan berkas akhir.
     */
    public function storeFinalDoc(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        $request->validate([
            'final_report'   => 'required|file|mimes:pdf|max:10240',
            'final_document' => 'required|file|mimes:pdf|max:10240',
        ]);

        $reportPath = $request->file('final_report')->store('education/reports', 'public');
        $docPath = $request->file('final_document')->store('education/final', 'public');

        $registration->update([
            'final_report_path'      => $reportPath,
            'final_doc_path'         => $docPath,
            'is_education_completed' => true,
        ]);

        return redirect('/dashboard')->with('success', 'Selamat! Anda telah mengunggah Laporan Akhir/KHS dan Ijazah, serta menyelesaikan program pendidikan.');
    }

    // ================================================================
    // WORKFLOW PELATIHAN
    // ================================================================

    /**
     * Form upload LOA (Hasil Seleksi Akhir Pelatihan).
     */
    public function uploadLoa($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);
        
        if ($registration->trainingProgram->kategori !== 'pelatihan') {
            return redirect('/dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        if ($registration->loa_path) {
            return redirect('/dashboard')->with('error', 'Berkas LOA sudah pernah diupload.');
        }

        return view('dashboard.training.upload-loa', compact('registration'));
    }

    /**
     * Simpan Hasil Seleksi Akhir Pelatihan.
     */
    public function storeLoa(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        if ($registration->trainingProgram->kategori !== 'pelatihan') {
            return redirect('/dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        $rules = [
            'selection_final_result' => 'required|in:passed,not_passed',
        ];
        $messages = [
            'selection_final_result.required' => 'Hasil seleksi wajib dipilih.',
        ];

        if ($request->selection_final_result === 'passed') {
            $rules['loa_file'] = 'required|file|mimes:pdf|max:5120';
            $messages['loa_file.required'] = 'File LOA wajib diupload jika lolos seleksi.';
        } else {
            $rules['selection_final_doc'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $messages['selection_final_doc.required'] = 'Dokumen bukti hasil seleksi wajib diupload jika tidak lolos.';
        }

        $request->validate($rules, $messages);

        $updateData = [
            'selection_final_result' => $request->selection_final_result,
        ];

        if ($request->selection_final_result === 'not_passed' && $request->hasFile('selection_final_doc')) {
            $updateData['selection_final_doc_path'] = $request->file('selection_final_doc')->store('training/selection-docs', 'public');
        }

        if ($request->selection_final_result === 'passed') {
            $updateData['loa_path'] = $request->file('loa_file')->store('training/loas', 'public');
        }

        $registration->update($updateData);

        if ($request->selection_final_result === 'passed') {
            return redirect('/dashboard')->with('success', 'Selamat! Anda lolos seleksi akhir pelatihan. Silakan lanjutkan ke tahapan Laporan Akhir.');
        } else {
            return redirect('/dashboard')->with('success', 'Dokumen hasil seleksi berhasil disimpan. Proses pendaftaran Anda berakhir di tahapan ini.');
        }
    }

    /**
     * Form upload laporan akhir pelatihan.
     */
    public function uploadTrainingReport($registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        if ($registration->trainingProgram->kategori !== 'pelatihan') {
            return redirect('/dashboard')->with('error', 'Program ini bukan merupakan kategori pelatihan.');
        }

        return view('dashboard.training.upload-report', compact('registration'));
    }

    /**
     * Simpan laporan akhir pelatihan.
     */
    public function storeTrainingReport(Request $request, $registrationId)
    {
        $registration = TrainingRegistration::where('user_id', Auth::id())->findOrFail($registrationId);

        if ($registration->trainingProgram->kategori !== 'pelatihan') {
            return redirect('/dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'final_report' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'final_report.required' => 'File laporan akhir wajib diupload.',
            'final_report.mimes'    => 'Format file harus PDF, DOC, atau DOCX.',
            'final_report.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $path = $request->file('final_report')->store('training/reports', 'public');

        $registration->update([
            'final_report_path' => $path,
        ]);

        return redirect('/dashboard')->with('success', 'Laporan akhir pelatihan berhasil diunggah. Seluruh tahapan pelatihan telah selesai.');
    }
}
