<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function store($id)
    {
        $books = Book::findOrFail($id);

        if ($books->stok < 1) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $loans = new Loan();
        $loans->user_id = Auth::id();
        $loans->book_id = $id;
        $loans->tanggal_peminjaman = Carbon::now();
        $loans->tanggal_jatuh_tempo = Carbon::now()->addDays(7);
        $loans->status = 'aktif';
        $loans->save();

        $books->decrement('stok');

        return redirect()->back()->with('success', 'Buku berhasil dipinjam!');
    }
}
