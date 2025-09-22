<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        return view('siswa.dashboard', [
            'peminjaman_saya' => Peminjaman::where('id_user', Auth::id())->get(),
        ]);
    }
}
