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
        $query = Peminjaman::with(['user', 'user.siswa.rombel', 'eksemplar.buku'])
            ->orderBy('tanggal_pinjam', 'desc');

        // Filter tanggal
        if ($this->filters->filled('tanggal_awal') && $this->filters->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pinjam', [
                $this->filters->tanggal_awal,
                $this->filters->tanggal_akhir
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

        // Filter buku
        if ($this->filters->filled('book_id')) {
            $query->whereHas('eksemplar.buku', function ($q) {
                $q->where('id', $this->filters->book_id);
            });
        }

        $peminjaman = $query->get();

        return view('admin.reports.export', compact('peminjaman'));
    }

    public function title(): string
    {
        return 'Laporan Peminjaman Buku';
    }
}
