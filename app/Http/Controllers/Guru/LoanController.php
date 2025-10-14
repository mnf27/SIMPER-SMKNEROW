<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function store(Request $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return back()->with('error', 'Data buku tidak ditemukan.');
        }

        if ($buku->jumlah_eksemplar <= 0) {
            return back()->with('error', 'Stok buku habis, tidak dapat dipinjam.');
        }

        $sudahPinjam = Peminjaman::where('id_user', Auth::id())
            ->where('id_buku', $buku->id)
            ->where('status', 'aktif')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini.');
        }

        Peminjaman::create([
            'id_user' => Auth::id(),
            'id_buku' => $buku->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'aktif',
            'jumlah' => 1,
        ]);

        $buku->decrement('jumlah_eksemplar');

        return back()->with('success', 'Buku berhasil dipinjam.');
    }

    public function history()
    {
        $peminjaman = Peminjaman::where('id_user', Auth::id())
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('guru.loans.history', compact('peminjaman'));
    }
}
