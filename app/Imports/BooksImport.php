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

    public $added = 0;
    public $skipped = 0;
    
    public function model(array $row)
    {
        $exists = Book::where('isbn', $row['isbn'])->exists();

        if ($exists) {
            $this->skipped++;
            return null;
        }

        $this->added++;

        return new Book([
            'judul' => $row['judul'],
            'penulis' => $row['penulis'],
            'isbn' => $row['isbn'],
            'category_id' => Category::where('nama', $row['kategori'])->value('id'),
            'penerbit' => $row['penerbit'],
            'tahun_terbit' => $row['tahun_terbit'],
            'stok' => $row['stok'],
            'deskripsi' => $row['deskripsi'] ?? null,
        ]);
    }
}
