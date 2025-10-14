<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eksemplar;
use App\Models\Buku;
use Illuminate\Http\Request;

class EksemplarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'no_induk' => 'required|string|max:100|unique:eksemplar,no_induk',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        Eksemplar::create([
            'buku_id' => $request->buku_id,
            'no_induk' => $request->no_induk,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.books.show', $request->buku_id)
            ->with('success', 'Eksemplar berhasil ditambahkan!');
    }

    /**
     * Hapus eksemplar dari buku.
     */
    public function destroy(Eksemplar $eksemplar)
    {
        $bukuId = $eksemplar->buku_id;
        $eksemplar->delete();

        return redirect()
            ->route('admin.books.show', $bukuId)
            ->with('success', 'Eksemplar berhasil dihapus!');
    }
}
