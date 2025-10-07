<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::select(
            'judul',
            'penulis',
            'penerbit',
            'tahun_terbit',
            'cetakan_edisi',
            'klasifikasi',
            'asal',
            'harga',
            DB::raw('SUM(jumlah_eksemplar) as total_eksemplar'),
            DB::raw('COUNT(*) as total_data')
        )
            ->groupBy(
                'judul',
                'penulis',
                'penerbit',
                'tahun_terbit',
                'cetakan_edisi',
                'klasifikasi',
                'asal',
                'harga'
            )
            ->with('kategori');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $buku = $query->paginate(10);

        return view('guru.books.index', compact('buku'));
    }
}
