<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Loan::with(['user.siswa', 'user.guru', 'book'])
            ->when($request->tanggal_mulai, fn($q) =>
                $q->whereDate('tanggal_peminjaman', '>=', $request->tanggal_mulai))
            ->when($request->tanggal_selesai, fn($q) =>
                $q->whereDate('tanggal_peminjaman', '<=', $request->tanggal_selesai))
            ->when($request->siswa, fn($q) =>
                $q->whereHas('user.siswa', fn($q2) =>
                    $q2->where('nama', 'like', "%{$request->siswa}%")
                        ->orWhere('nis', 'like', "%{$request->siswa}%")))
            ->latest()
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    public function export(Request $request)
    {
        return Excel::download(new ReportExport($request), 'laporan_peminjaman.xlsx');
    }
}
