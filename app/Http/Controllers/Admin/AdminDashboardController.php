<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'jumlah_buku' => Buku::count(),
            'peminjaman_aktif' => Peminjaman::where('status', 'aktif')->count(),
            'jumlah_guru' => User::where('role', 'guru')->count(),
            'jumlah_siswa' => User::where('role', 'siswa')->count(),
        ]);
    }
}
