<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        // update otomatis status "terlambat"
        Peminjaman::where('status', 'aktif')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        // ambil data peminjaman beserta relasi user dan buku dari eksemplar
        $query = Peminjaman::with(['user', 'eksemplar.buku']);

        if ($status) {
            $query->where('status', $status);
        }

        $loans = $query->latest()->paginate(10);
        $users = User::all();
        $eksemplars = Eksemplar::with('buku')->where('status', 'tersedia')->get();

        return view('admin.loans.index', compact('loans', 'users', 'eksemplars', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'eksemplar_id' => 'required|exists:eksemplar,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $eksemplar = Eksemplar::findOrFail($request->eksemplar_id);

        if ($eksemplar->status !== 'tersedia') {
            return back()->with('error', 'Eksemplar ini sedang dipinjam atau tidak tersedia.');
        }

        // buat peminjaman baru
        Peminjaman::create([
            'id_user' => $request->id_user,
            'id_buku' => $eksemplar->buku_id,
            'eksemplar_id' => $eksemplar->id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'aktif',
            'jumlah' => 1,
        ]);

        // ubah status eksemplar jadi dipinjam
        $eksemplar->update(['status' => 'dipinjam']);

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function returnBook(Peminjaman $loan)
    {
        if (!in_array($loan->status, ['aktif', 'terlambat'])) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $loan->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => Carbon::now(),
        ]);

        if ($loan->eksemplar) {
            $loan->eksemplar->update(['status' => 'tersedia']);
        }

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
