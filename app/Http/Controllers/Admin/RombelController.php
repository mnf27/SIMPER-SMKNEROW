<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $rombels = Rombel::latest()->paginate(10);
        return view('admin.rombels.index', compact('rombels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rombels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
        ]);

        Rombel::create($request->all());

        return redirect()->route('admin.rombels.index')->with('success', 'Rombel berhasil ditambahkan.');
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
    public function edit(Rombel $rombel)
    {
        return view('admin.rombels.edit', compact('rombel'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
        ]);

        $rombel->update($request->all());

        return redirect()->route('admin.rombels.index')->with('success', 'Rombel berhasil diperbarui.');
    }

    public function destroy(Rombel $rombel)
    {
        $rombel->delete();
        return redirect()->route('admin.rombels.index')->with('success', 'Rombel berhasil dihapus.');
    }
}
