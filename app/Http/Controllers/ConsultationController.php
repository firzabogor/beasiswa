<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Tampilkan daftar konsultasi milik user yang login.
     */
    public function index()
    {
        $consultations = Consultation::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('consultation.index', compact('consultations'));
    }

    /**
     * Tampilkan form ajuan konsultasi baru.
     */
    public function create()
    {
        return view('consultation.create');
    }

    /**
     * Simpan ajuan konsultasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tujuan'   => 'required|string|max:100',
            'pimpinan' => 'required|string|max:100',
            'alasan'   => 'required|string|max:1000',
            'jadwal'   => 'required|date|after:now',
        ], [
            'tujuan.required'   => 'Tujuan konsultasi wajib dipilih.',
            'pimpinan.required' => 'Pilih pimpinan yang dituju.',
            'alasan.required'   => 'Alasan konsultasi wajib diisi.',
            'jadwal.required'   => 'Jadwal konsultasi wajib dipilih.',
            'jadwal.after'      => 'Jadwal harus di masa mendatang.',
        ]);

        Consultation::create([
            'user_id'  => Auth::id(),
            'tujuan'   => $request->tujuan,
            'pimpinan' => $request->pimpinan,
            'alasan'   => $request->alasan,
            'jadwal'   => $request->jadwal,
            'status'   => 'pending',
        ]);

        return redirect('/konsultasi')
            ->with('success', 'Ajuan konsultasi berhasil dikirim. Menunggu konfirmasi.');
    }

    /**
     * Tampilkan profil / detail konsultasi (untuk halaman profil legacy).
     */
    public function profile()
    {
        return view('consultation.profile');
    }
}
