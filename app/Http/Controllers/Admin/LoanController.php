<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
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

        // ambil query baru
        $query = Peminjaman::with(['user', 'buku']);

        if ($status) {
            $query->where('status', $status);
        }

        $loans = $query->latest()->paginate(10);
        $users = User::all();
        $books = Buku::where('jumlah_eksemplar', '>', 0)->get();

        return view('admin.loans.index', compact('loans', 'users', 'books', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_buku' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        Peminjaman::create([
            'id_user' => $request->id_user,
            'id_buku' => $request->id_buku,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'aktif', // sesuai default migrasi
            'jumlah' => 1,
        ]);

        // kurangi stok buku
        Buku::where('id', $request->id_buku)->decrement('jumlah_eksemplar');

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function returnBook(Peminjaman $loan, Request $request)
    {
        if ($loan->status !== 'aktif' && $loan->status !== 'terlambat') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $loan->status = 'dikembalikan';
        $loan->tanggal_dikembalikan = Carbon::now();

        // cek keterlambatan
        if ($loan->tanggal_dikembalikan->greaterThan(Carbon::parse($loan->tanggal_kembali))) {
            $loan->denda = $request->input('denda', 0); // kalau mau otomatis bisa hitung di sini
        }

        $loan->save();

        // balikin stok buku
        Buku::where('id', $loan->id_buku)->increment('jumlah_eksemplar');

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    public function addFine(Peminjaman $loan, Request $request)
    {
        $request->validate([
            'denda' => 'required|numeric|min:0',
        ]);

        $loan->denda = $request->denda;
        $loan->save();

        return back()->with('success', 'Denda berhasil ditambahkan.');
    }
}
