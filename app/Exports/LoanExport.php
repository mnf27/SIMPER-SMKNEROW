<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class LoanExport implements FromView, WithTitle
{
    use Exportable;

    protected $filters;

    public function __construct($request)
    {
        $this->filters = $request;
    }

    public function view(): View
    {
        $query = Peminjaman::with(['user', 'buku.kategori', 'user.siswa.rombel'])
            ->orderBy('tanggal_pinjam', 'desc');

        // Filter tanggal
        if ($this->filters->filled('start_date') && $this->filters->filled('end_date')) {
            $query->whereBetween('tanggal_pinjam', [
                $this->filters->start_date,
                $this->filters->end_date
            ]);
        }

        // Filter user
        if ($this->filters->filled('user_id')) {
            $query->where('id_user', $this->filters->user_id);
        }

        // Filter rombel
        if ($this->filters->filled('rombel_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('id_rombel', $this->filters->rombel_id);
            });
        }

        // Filter kategori
        if ($this->filters->filled('category_id')) {
            $query->whereHas('buku', function ($q) {
                $q->where('id_kategori', $this->filters->category_id);
            });
        }

        // Filter buku
        if ($this->filters->filled('book_id')) {
            $query->where('id_buku', $this->filters->book_id);
        }

        $peminjaman = $query->get();

        return view('admin.reports.export', compact('peminjaman'));
    }

    public function title(): string
    {
        return 'Laporan Peminjaman';
    }
}
