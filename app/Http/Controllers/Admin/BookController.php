<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    private function saveCover($file, $oldPath = null)
    {
        // Hapus cover lama jika ada
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Buku::with('kategori')->latest()->paginate(10);
        $categories = Kategori::all();

        return view('admin.books.index', compact('books', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|unique:buku,no_induk',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'cetakan_edisi' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'id_kategori' => 'required|exists:kategori,id',
            'jumlah_eksemplar' => 'required|integer|min:0',
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $book)
    {
        $categories = Kategori::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $book)
    {
        $request->validate([
            'no_induk' => 'required|unique:buku,no_induk,' . $book->id,
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'cetakan_edisi' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'id_kategori' => 'required|exists:kategori,id',
            'jumlah_eksemplar' => 'required|integer|min:0',
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

    /**
     * Remove the specified resource from storage.
     */
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
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        $import = new BooksImport;
        Excel::import($import, $request->file('file'));

        $messages = [];

        if ($import->added > 0) {
            $messages[] = $import->added . " data berhasil diimport.";
        }

        if ($import->skipped > 0) {
            $messages[] = $import->skipped . " data dilewati (duplikat no_induk).";
        }

        if ($import->invalid > 0) {
            $messages[] = $import->invalid . " data dilewati (judul/no_induk kosong).";
        }

        return redirect()->route('admin.books.index')->with('success', implode(' ', $messages));
    }
}
