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

        Log::info('HEADER KEYS: ' . json_encode(array_keys($normalized)));

        $noInduk = $normalized['no_induk'] ?? null;
        $judul = $normalized['judul'] ?? null;

        // Validasi wajib
        if (empty($noInduk) || empty($judul)) {
            $this->invalid++;
            Log::warning("Baris dilewati karena no_induk/judul kosong: " . json_encode($row));
            return null;
        }

        // Ambil data kolom lainnya
        $penulis = $normalized['penulis'] ?? null;
        $penerbit = $normalized['penerbit'] ?? null;
        $tahun = $normalized['tahun'] ?? null;
        $cetakanEdisi = $normalized['cetakanedisi'] ?? $normalized['cetakan_edisi'] ?? null;
        $klasifikasi = $normalized['no_class'] ?? null;
        $asal = $normalized['asal'] ?? null;
        $harga = is_numeric($normalized['harga'] ?? null) ? $normalized['harga'] : 0;
        $keterangan = $normalized['keterangan'] ?? null;

        $cetakanEdisi = $cetakanEdisi !== '' ? $cetakanEdisi : null;
        $klasifikasi = $klasifikasi !== '' ? $klasifikasi : null;

        // Buat atau ambil buku
        $buku = Buku::firstOrCreate(
            [
                'judul' => $judul,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun,
                'klasifikasi' => $klasifikasi,
            ],
            [
                'cetakan_edisi' => $cetakanEdisi,
                'asal' => $asal,
                'harga' => $harga,
                'keterangan' => $keterangan,
                'jumlah_eksemplar' => 0, // awalnya 0, nanti akan diupdate
            ]
        );

        if ($buku->wasRecentlyCreated) {
            $this->addedBooks++;
            Log::info("Buku baru ditambahkan: {$judul}");
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
