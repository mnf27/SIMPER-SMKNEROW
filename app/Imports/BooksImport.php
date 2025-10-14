<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Eksemplar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BooksImport implements ToModel, WithHeadingRow
{
    public $addedBooks = 0;
    public $addedEksemplars = 0;
    public $skipped = 0;
    public $invalid = 0;

    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        // Normalisasi key heading
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[strtolower(str_replace([' ', '.', '/'], '_', trim($key)))] = trim($value);
        }

        $noInduk = $normalized['no_induk'] ?? null;
        $judul = $normalized['judul'] ?? null;

        // Validasi wajib
        if (empty($noInduk) || empty($judul)) {
            $this->invalid++;
            return null;
        }

        // Ambil data kolom lainnya
        $penulis = $normalized['penulis'] ?? null;
        $penerbit = $normalized['penerbit'] ?? null;
        $tahun = $normalized['tahun'] ?? null;
        $cetakanEdisi = $normalized['cetakan_edisi'] ?? ($normalized['cetakan/edisi'] ?? null);
        $klasifikasi = $normalized['no_class'] ?? null;
        $asal = $normalized['asal'] ?? null;
        $harga = is_numeric($normalized['harga'] ?? null) ? $normalized['harga'] : 0;
        $keterangan = $normalized['keterangan'] ?? null;

        // Buat atau ambil buku
        $buku = Buku::firstOrCreate(
            [
                'judul' => $judul,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun,
                'cetakan_edisi' => $cetakanEdisi,
                'klasifikasi' => $klasifikasi,
            ],
            [
                'asal' => $asal,
                'harga' => $harga,
                'keterangan' => $keterangan,
                'jumlah_eksemplar' => 0, // awalnya 0, nanti akan diupdate
            ]
        );

        if ($buku->wasRecentlyCreated) {
            $this->addedBooks++;
        }

        // Cek apakah eksemplar sudah ada
        if (Eksemplar::where('no_induk', $noInduk)->exists()) {
            $this->skipped++;
            Log::info("Duplikat no_induk dilewati: {$noInduk}");
            return null;
        }

        // Buat eksemplar baru
        Eksemplar::create([
            'buku_id' => $buku->id,
            'no_induk' => $noInduk,
            'status' => 'tersedia',
        ]);

        $buku->increment('jumlah_eksemplar');

        $this->addedEksemplars++;
    }
}
