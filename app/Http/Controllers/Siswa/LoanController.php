<?php

namespace App\Http\Controllers\Siswa;

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

        // Cari salah satu buku dengan judul yang sama dan stok masih ada
        $buku = Buku::find($id);

        if (!$buku) {
            return back()->with('error', 'Data buku tidak ditemukan.');
        }

        $userId = Auth::id();

        $jumlahHariIni = Peminjaman::where('id_user', $userId)
            ->whereDate('tanggal_pinjam', now()->toDateString())
            ->count();

        if ($jumlahHariIni >= 2) {
            return back()->with('error', 'Anda sudah mencapai batas 2 kali peminjaman hari ini.');
        }

        $masihMeminjam = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['aktif', 'terlambat'])
            ->exists();

        if ($masihMeminjam) {
            return back()->with('error', 'Anda masih memiliki buku yang belum dikembalikan.');
        }

        // Cek apakah user masih meminjam buku ini
        $sudahPinjam = Peminjaman::where('id_user', $userId)
            ->where('eksemplar_id', $request->eksemplar_id)
            ->whereIn('status', ['aktif', 'menunggu'])
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam eksemplar ini atau masih menunggu konfirmasi.');
        }

        // Simpan data peminjaman
        Peminjaman::create([
            'id_user' => Auth::id(),
            'eksemplar_id' => $request->eksemplar_id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now(),
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

        return view('siswa.loans.history', compact('peminjaman'));
    }
}
