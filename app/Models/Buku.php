<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'cetakan_edisi',
        'klasifikasi',
        'no_class',
        'asal',
        'harga',
        'jumlah_eksemplar',
        'keterangan',
        'cover_image',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'tahun_terbit' => 'integer',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }

    public function eksemplar()
    {
        return $this->hasMany(Eksemplar::class, 'buku_id');
    }
    
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/covers/' . $this->cover_image)
            : asset('images/default_cover.png');
    }
}
