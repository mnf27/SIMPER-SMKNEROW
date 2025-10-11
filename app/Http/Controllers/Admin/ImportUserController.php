<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;
use App\Imports\SiswaImport;

class ImportUserController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user')->latest()->paginate(10);
        $siswa = Siswa::with('user')->latest()->take(20)->paginate(10);

        return view('admin.import.index', compact('guru', 'siswa'));
    }

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new GuruImport, $request->file('file'));

        return back()->with('success', 'Data guru berhasil diimport');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return back()->with('success', 'Data siswa berhasil diimport');
    }
}
