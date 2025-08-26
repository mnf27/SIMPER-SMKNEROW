<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoansExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Loan::with(['user', 'book'])->get();
    }

    public function map($loans): array
    {
        return [
            $loans->id,
            $loans->user->nama ?? $loans->user->email,
            $loans->books->judul ?? '-',
            $loans->tanggal_peminjaman,
            $loans->tanggal_jatuh_tempo,
            $loans->tanggal_pengembalian ?? '-',
            ucfirst($loans->status),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Peminjam',
            'Judul Buku',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
        ];
    }
}
