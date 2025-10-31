<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Eksemplar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    private function saveCover($file, $oldPath = null)
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $manager = new ImageManager(new Driver());
        $resized = $manager->read($file)->scaleDown(800);

        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = 'covers/' . $filename;
        Storage::disk('public')->put($path, (string) $resized->encode());

        return $path;
    }

    public function index()
    {
        // Ambil buku sekaligus hitung jumlah eksemplar
        $books = Buku::withCount([
            'eksemplar as total_eksemplar',
            'eksemplar as eksemplar_tersedia' => function ($query) {
                $query->where('status', 'tersedia');
            },
            'eksemplar as eksemplar_dipinjam' => function ($query) {
                $query->where('status', 'dipinjam');
            },
        ])->latest()->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'cetakan_edisi' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'asal' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->saveCover($request->file('cover_image'));
        }

        Buku::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $book)
    {
        $book->load(['eksemplar']);
        return response()->json($book);
    }

    public function update(Request $request, Buku $book)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'cetakan_edisi' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'asal' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $data = $request->except(['cover_image', 'remove_cover']);

        if ($request->has('remove_cover') && $request->remove_cover == 1) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->saveCover($request->file('cover_image'), $book->cover_image);
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $book)
    {
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        $import = new BooksImport;
        Excel::import($import, $request->file('file'));

        $messages = [];
        if ($import->addedBooks > 0)
            $messages[] = "{$import->addedBooks} buku baru ditambahkan.";
        if ($import->addedEksemplars > 0)
            $messages[] = "{$import->addedEksemplars} eksemplar baru ditambahkan.";
        if ($import->skipped > 0)
            $messages[] = "{$import->skipped} eksemplar dilewati (duplikat).";
        if ($import->invalid > 0)
            $messages[] = "{$import->invalid} baris tidak valid (judul/no_induk kosong).";

        return redirect()->route('admin.books.index')->with('success', implode(' ', $messages));
    }

    public function addEksemplar(Request $request, Buku $book)
    {
        Log::info('Request masuk ke addEksemplar', [
            'book_id' => $book->id,
            'payload' => $request->all()
        ]);

        $request->validate([
            'no_induk' => 'required|string|max:50|unique:eksemplar,no_induk',
        ]);

        try {
            $eksemplar = $book->eksemplar()->create([
                'no_induk' => $request->no_induk,
                'status' => 'tersedia',
            ]);

            return response()->json(['success' => true, 'data' => $eksemplar]);
        } catch (\Throwable $e) {
            Log::error('Gagal tambah eksemplar: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteEksemplar($id)
    {
        $eksemplar = Eksemplar::find($id);

        if (!$eksemplar) {
            return response()->json(['success' => false, 'message' => 'Eksemplar tidak ditemukan'], 404);
        }

        $eksemplar->delete();
        return response()->json(['success' => true]);
    }
}
