<?php

namespace App\Http\Controllers\Admin;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'buku'])->latest()->paginate(10);

        return view('admin.loans.index', compact('loans'));
    }

    public function exportExcel()
{
    return Excel::download(new LoansExport, 'laporan_peminjaman.xlsx');
}
}
