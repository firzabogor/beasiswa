<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Halaman utama
Route::get('/', [HomeController::class, 'index']);

// Auth Routes — hanya bisa diakses jika BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Training / Diklat
    Route::get('/diklat',              [TrainingController::class, 'index']);
    Route::get('/diklat/{id}',         [TrainingController::class, 'show']);
    Route::get('/diklat/{id}/daftar',  [TrainingController::class, 'showRegisterForm']);
    Route::post('/diklat/{id}/daftar', [TrainingController::class, 'register']);
    Route::get('/riwayat-diklat',      [TrainingController::class, 'history']);

    // Dashboard User & Alur Pendaftaran
    Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('dashboard');
    Route::get('/registrasi/{id}/daftar-perusahaan',  [ApplicationController::class, 'applyCompany']);
    Route::post('/registrasi/{id}/daftar-perusahaan', [ApplicationController::class, 'storeCompanyApplication']);
    Route::get('/aplikasi/{id}/upload-seleksi',        [ApplicationController::class, 'uploadSelection']);
    Route::post('/aplikasi/{id}/upload-seleksi',       [ApplicationController::class, 'storeSelectionDocument']);
    Route::get('/registrasi/{id}/surat-pengantar',     [ApplicationController::class, 'downloadInvitation']);

    // Konsultasi
    Route::get('/konsultasi',         [ConsultationController::class, 'index']);
    Route::get('/konsultasi/buat',    [ConsultationController::class, 'create']);
    Route::post('/konsultasi',        [ConsultationController::class, 'store']);
    Route::get('/konsultasi/profil',  [ConsultationController::class, 'profile']);

    // Workflow Pendidikan
    Route::get('/pendidikan/{id}/upload-log-loa', [ApplicationController::class, 'uploadLogLoa']);
    Route::post('/pendidikan/{id}/upload-log-loa', [ApplicationController::class, 'storeLogLoa']);
    Route::get('/pendidikan/{id}/tambah-aktivitas', [ApplicationController::class, 'createActivity']);
    Route::post('/pendidikan/{id}/tambah-aktivitas', [ApplicationController::class, 'storeActivity']);
    Route::get('/pendidikan/{id}/upload-akhir', [ApplicationController::class, 'uploadFinalDoc']);
    Route::post('/pendidikan/{id}/upload-akhir', [ApplicationController::class, 'storeFinalDoc']);

    // Workflow Pelatihan - Laporan Akhir
    Route::get('/pelatihan/{id}/upload-loa', [ApplicationController::class, 'uploadLoa']);
    Route::post('/pelatihan/{id}/upload-loa', [ApplicationController::class, 'storeLoa']);
    Route::get('/pelatihan/{id}/upload-laporan', [ApplicationController::class, 'uploadTrainingReport']);
    Route::post('/pelatihan/{id}/upload-laporan', [ApplicationController::class, 'storeTrainingReport']);
});

// Admin Panel — hanya admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Manajemen Program Pelatihan
    Route::get('/programs',              [AdminController::class, 'programs']);
    Route::get('/programs/create',       [AdminController::class, 'createProgram']);
    Route::get('/programs/{id}',         [AdminController::class, 'showProgram']);
    Route::post('/programs',             [AdminController::class, 'storeProgram']);
    Route::get('/programs/{id}/edit',    [AdminController::class, 'editProgram']);
    Route::put('/programs/{id}',         [AdminController::class, 'updateProgram']);
    Route::delete('/programs/{id}',      [AdminController::class, 'deleteProgram']);
    Route::post('/programs/{id}/requirements', [AdminController::class, 'addRequirement']);
    Route::delete('/requirements/{id}',        [AdminController::class, 'deleteRequirement']);
    Route::post('/programs/{id}/topics',       [AdminController::class, 'addTopic']);
    Route::put('/topics/{id}',                 [AdminController::class, 'updateTopic']);
    Route::delete('/topics/{id}',              [AdminController::class, 'deleteTopic']);
    Route::put('/requirements/{id}',           [AdminController::class, 'updateRequirement']);
    Route::delete('/requirements/{id}',        [AdminController::class, 'deleteRequirement']);

    // Manajemen Verifikasi Pendaftaran
    Route::get('/verifikasi',            [AdminController::class, 'verifications']);
    Route::get('/verifikasi/{id}',       [AdminController::class, 'verifyForm']);
    Route::post('/verifikasi/{id}',      [AdminController::class, 'verify']);
    Route::get('/export-registrations',  [AdminController::class, 'exportRegistrations']);

    // Manajemen Pengguna
    Route::get('/users',                 [AdminController::class, 'users']);
    Route::get('/users/create',          [AdminController::class, 'createUser']);
    Route::post('/users',                [AdminController::class, 'storeUser']);
    Route::get('/users/{id}',            [AdminController::class, 'userDetail']);
    Route::get('/users/{id}/edit',       [AdminController::class, 'editUser']);
    Route::put('/users/{id}',            [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}',         [AdminController::class, 'deleteUser']);

    // Dokumen Seleksi
    Route::get('/documents',             [AdminController::class, 'selectionDocuments']);
    Route::get('/documents/{id}/download', [AdminController::class, 'downloadSelectionDoc']);

    // Manajemen Konsultasi
    Route::get('/consultations',         [AdminController::class, 'consultations']);
    Route::get('/consultations/{id}',    [AdminController::class, 'showConsultation']);
    Route::post('/consultations/{id}/reply', [AdminController::class, 'replyConsultation']);

    // Monitoring Pendidikan
    Route::get('/monitoring-pendidikan', [AdminController::class, 'monitoringPendidikan']);
    Route::get('/monitoring-pendidikan/export', [AdminController::class, 'exportMonitoringPendidikan']);
    Route::post('/monitoring-pendidikan/{id}/reset', [AdminController::class, 'resetEducationReport']);
    Route::post('/monitoring-pendidikan/{id}/reset-log', [AdminController::class, 'resetLog']);
    Route::post('/monitoring-pendidikan/{id}/reset-loa', [AdminController::class, 'resetLoa']);

    // Monitoring Pelatihan (Laporan Akhir)
    Route::get('/monitoring-pelatihan', [AdminController::class, 'monitoringPelatihan']);
    Route::get('/monitoring-pelatihan/export', [AdminController::class, 'exportMonitoringPelatihan']);
    Route::get('/monitoring-pelatihan/{id}/download', [AdminController::class, 'downloadTrainingReport']);
    Route::post('/monitoring-pelatihan/{id}/reset', [AdminController::class, 'resetTrainingReport']);
    Route::post('/monitoring-pelatihan/{id}/reset-seleksi', [AdminController::class, 'resetSelectionResult']);

    // Pengaturan Aplikasi
    Route::get('/settings', [AdminController::class, 'settings']);
    Route::post('/settings', [AdminController::class, 'updateSettings']);
});
