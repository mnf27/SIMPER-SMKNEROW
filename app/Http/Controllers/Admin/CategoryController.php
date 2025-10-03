<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Kategori::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Kategori sudah ada, silahkan masukkan kategori lain.',
            'nama.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        Kategori::create($request->only('nama'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
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
    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $category)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $category->id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Kategori sudah ada, silahkan masukkan kategori lain.',
            'nama.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        $category->update($request->only('nama'));

        return redirect()->route('admin.categories.index')
            ->with('succes', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
