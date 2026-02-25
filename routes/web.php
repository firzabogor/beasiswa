<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function () {
    return redirect('/diklat');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', function () {
    return redirect('/login');
});

// Training Routes
use App\Http\Controllers\TrainingController;

Route::get('/diklat', [TrainingController::class, 'index']);
Route::get('/diklat/{id}', [TrainingController::class, 'show']);
Route::post('/diklat/{id}/daftar', [TrainingController::class, 'register']);
Route::get('/riwayat-diklat', [TrainingController::class, 'history']);

// Legacy / Profile
Route::get('/konsultasi/profil', function () {
    return view('consultation.profile');
});
