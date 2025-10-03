<?php

namespace App\Imports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BooksImport implements ToModel, WithHeadingRow
{
    public $added = 0;
    public $skipped = 0;
    public $invalid = 0;

    public function headingRow(): int
    {
        return 4; // header mulai di baris ke-4
    }

    public function model(array $row)
    {
        // Normalisasi key heading
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[strtolower(str_replace([' ', '.', '/'], '_', $key))] = $value;
        }

        // Normalisasi nilai
        $noInduk = isset($normalized['no_induk'])
            ? trim(strval($normalized['no_induk']))
            : null;

        $judul = isset($normalized['judul'])
            ? trim(strval($normalized['judul']))
            : null;

        if (empty($noInduk) || empty($judul)) {
            $this->invalid++;
            return null;
        }

        // Cek duplikat dengan nilai yang sudah dibersihkan
        if (Buku::where('no_induk', $noInduk)->exists()) {
            Log::info("Skipped karena duplikat no_induk: " . $noInduk);
            $this->skipped++;
            return null;
        }

        $this->added++;

        return new Buku([
            'no_induk' => $noInduk,
            'judul' => $judul,
            'penulis' => isset($normalized['penulis']) ? trim($normalized['penulis']) : null,
            'penerbit' => isset($normalized['penerbit']) ? trim($normalized['penerbit']) : null,
            'tahun_terbit' => $normalized['tahun'] ?? null,
            'cetakan_edisi' => $normalized['cetakan_edisi'] ?? null,
            'klasifikasi' => $normalized['no_class'] ?? null,
            'id_kategori' => 1, // kategori default
            'jumlah_eksemplar' => $normalized['jumlah_eksemplar'] ?? 0,
            'asal' => isset($normalized['asal']) ? trim($normalized['asal']) : null,
            'harga' => is_numeric($normalized['harga'] ?? null) ? $normalized['harga'] : 0,
            'keterangan' => isset($normalized['keterangan']) ? trim($normalized['keterangan']) : null,
        ]);
    }
}
