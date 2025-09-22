<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'no_induk',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'cetakan_edisi',
        'klasifikasi',
        'id_kategori',
        'jumlah_eksemplar',
        'asal',
        'harga',
        'keterangan',
        'cover_image',
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }
}
