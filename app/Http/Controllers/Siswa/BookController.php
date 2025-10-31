<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Eksemplar;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::withCount([
            'eksemplar as total_eksemplar',
            'eksemplar as eksemplar_tersedia' => function ($q) {
                $q->where('status', 'tersedia');
            },
            'eksemplar as eksemplar_dipinjam' => function ($q) {
                $q->where('status', 'dipinjam');
            },
        ]);

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $buku = $query->paginate(10);

        return view('siswa.books.index', compact('buku'));
    }

    public function getEksemplar($id)
    {
        $eksemplars = Eksemplar::where('buku_id', $id)
            ->where('status', 'tersedia')
            ->select('id', 'no_induk')
            ->get();

        return response()->json($eksemplars);
    }
}
