<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        return view('guru.dashboard', [
            'peminjaman_saya' => Peminjaman::where('id_user', Auth::id())->paginate(10),
        ]);
    }
}
