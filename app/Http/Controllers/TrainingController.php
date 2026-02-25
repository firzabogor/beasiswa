<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function index()
    {
        // Mock data if DB is empty for demonstration
        $programs = TrainingProgram::all();
        
        if($programs->isEmpty()) {
            $programs = collect([
                (object)[
                    'id' => 1,
                    'title' => 'Diklatpim Tingkat IV',
                    'description' => 'Pendidikan dan Pelatihan Kepemimpinan Tingkat IV untuk membentuk kompetensi kepemimpinan operasional.',
                    'start_date' => '2024-03-01 08:00:00',
                    'end_date' => '2024-06-01 16:00:00',
                    'location' => 'Kampus BPSDMP Jakarta',
                    'quota' => 30,
                    'status' => 'open'
                ],
                (object)[
                    'id' => 2,
                    'title' => 'Workshop IT Security',
                    'description' => 'Pelatihan keamanan informasi untuk staf IT.',
                    'start_date' => '2024-04-10 09:00:00',
                    'end_date' => '2024-04-12 15:00:00',
                    'location' => 'Online (Zoom)',
                    'quota' => 50,
                    'status' => 'open'
                ]
            ]);
        }

        return view('training.index', compact('programs'));
    }

    public function show($id)
    {
        // Mock finding data
        $program = TrainingProgram::find($id);
        
        if(!$program) {
             // Mock data fallback
             if($id == 1) {
                $program = (object)[
                    'id' => 1,
                    'title' => 'Diklatpim Tingkat IV',
                    'description' => 'Pendidikan dan Pelatihan Kepemimpinan Tingkat IV untuk membentuk kompetensi kepemimpinan operasional.',
                    'start_date' => '2024-03-01 08:00:00',
                    'end_date' => '2024-06-01 16:00:00',
                    'location' => 'Kampus BPSDMP Jakarta',
                    'quota' => 30,
                    'status' => 'open'
                ];
             } else {
                 abort(404);
             }
        }

        return view('training.show', compact('program'));
    }

    public function register($id)
    {
        // Logic to save registration
        // For now, redirect with success message
        return redirect('/riwayat-diklat')->with('success', 'Berhasil mendaftar diklat!');
    }

    public function history()
    {
        return view('training.history');
    }
}
