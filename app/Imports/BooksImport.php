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
    public $invalid = 0;

    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }

        unset($row['no']);

        $judul = isset($row['judul']) ? trim((string) $row['judul']) : null;
        $isbn = isset($row['isbn']) ? trim((string) $row['isbn']) : null;

        if ($judul === null || $judul === '' || $isbn === null || $isbn === '') {
            $this->invalid++;
            return null;
        }

        if (Book::where('isbn', $isbn)->exists()) {
            $this->skipped++;
            return null;
        }

        $categoryId = null;
        if (!empty($row['kategori'])) {
            $categoryId = Category::whereRaw('LOWER(nama) = ?', [mb_strtolower(trim($row['kategori']))])
                ->value('id');
        }

        $this->added++;

        return new Book([
            'judul' => $judul,
            'penulis' => $row['penulis'] ?? null,
            'isbn' => $isbn,
            'category_id' => $categoryId,
            'penerbit' => $row['penerbit'] ?? null,
            'tahun_terbit' => !empty($row['tahun_terbit']) ? (int) $row['tahun_terbit'] : null,
            'stok' => isset($row['stok']) ? (int) $row['stok'] : 0,
            'deskripsi' => $row['deskripsi'] ?? null,
        ]);
    }
}
