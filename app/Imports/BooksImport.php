<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Book([
            'judul' => $row['judul'],
            'penulis' => $row['penulis'],
            'isbn' => $row['isbn'],
            'category_id' => Category::where('nama', $row['kategori'])->first()->id ?? null,
            'penerbit' => $row['penerbit'],
            'tahun_terbit' => $row['tahun_terbit'],
            'stok' => $row['stok'],
            'deskripsi' => $row['deskripsi'] ?? null,
        ]);
    }
}
