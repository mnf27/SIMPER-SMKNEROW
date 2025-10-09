<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $judul = $request->judul; // kirimkan judul dari tombol "Pinjam" di view

        // Cari salah satu buku dengan judul yang sama dan stok masih ada
        $buku = Buku::where('judul', $judul)
            ->where('jumlah_eksemplar', '>', 0)
            ->first();

        if (!$buku) {
            return back()->with('error', 'Stok buku "' . $judul . '" habis.');
        }

        // Cek apakah user masih meminjam buku ini
        $sudahPinjam = Peminjaman::where('id_user', Auth::id())
            ->where('id_buku', $buku->id)
            ->where('status', 'aktif')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda masih meminjam buku "' . $judul . '".');
        }

        // Simpan data peminjaman
        Peminjaman::create([
            'id_user' => Auth::id(),
            'id_buku' => $buku->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'status' => 'aktif',
            'jumlah' => 1,
        ]);

        // Kurangi stok buku
        $buku->decrement('jumlah_eksemplar');

        return back()->with('success', 'Buku "' . $judul . '" berhasil dipinjam.');
    }

    public function history()
    {
        $peminjaman = Peminjaman::with('buku.kategori')
            ->where('id_user', Auth::id())
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('siswa.loans.history', compact('peminjaman'));
    }
}
