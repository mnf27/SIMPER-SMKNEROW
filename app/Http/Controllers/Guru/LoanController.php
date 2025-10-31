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
        $request->validate([
            'eksemplar_id' => 'required|exists:eksemplar,id',
        ]);

        $buku = Buku::find($id);

        if (!$buku) {
            return back()->with('error', 'Data buku tidak ditemukan.');
        }

        $sudahPinjam = Peminjaman::where('id_user', Auth::id())
            ->where('eksemplar_id', $request->eksemplar_id)
            ->whereIn('status', ['aktif', 'menunggu'])
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam eksemplar ini atau masih menunggu konfirmasi.');
        }

        Peminjaman::create([
            'id_user' => Auth::id(),
            'eksemplar_id' => $request->eksemplar_id,
            'tanggal_pinjam' => now(),
            'status' => 'menunggu', // misal nanti akan dikonfirmasi admin
            'jumlah' => 1,
        ]);

        return back()->with('success', 'Permintaan peminjaman dikirim, silahkan konfirmasi ke admin.');
    }

    public function history()
    {
        $peminjaman = Peminjaman::where('id_user', Auth::id())
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('guru.loans.history', compact('peminjaman'));
    }
}
