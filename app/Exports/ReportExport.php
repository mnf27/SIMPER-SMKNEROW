<?php

namespace App\Exports;

use App\Models\Loan;
use Illuminate\Contracts\View\View;
;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $reports = Loan::with(['siswa', 'book'])
            ->when($this->request->tanggal_mulai, fn($q) =>
                $q->whereDate('tanggal_pinjam', '>=', $this->request->tanggal_mulai))
            ->when($this->request->tanggal_selesai, fn($q) =>
                $q->whereDate('tanggal_pinjam', '<=', $this->request->tanggal_selesai))
            ->when($this->request->siswa, fn($q) =>
                $q->whereHas('siswa', fn($q2) =>
                    $q2->where('nama', 'like', "%{$this->request->siswa}%")
                        ->orWhere('nis', 'like', "%{$this->request->siswa}%")))
            ->latest()->get();

        return view('admin.reports.export', compact('reports'));
    }
}
