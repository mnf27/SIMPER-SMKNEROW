<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id_user',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'jumlah',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        if ($this->tanggal_dikembalikan) {
            return 'dikembalikan';
        }

        if ($this->tanggal_kembali && $this->tanggal_kembali->isPast() && !$this->tanggal_dikembalikan) {
            return 'terlambat';
        }

        return 'aktif';
    }
}
