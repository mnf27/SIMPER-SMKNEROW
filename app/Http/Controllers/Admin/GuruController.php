<?php

namespace App\Http\Controllers\Admin;

use App\Imports\GuruImport;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        return view('admin.guru.index', compact('guru'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new GuruImport, $request->file('file'));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diimport!');
    }
}
