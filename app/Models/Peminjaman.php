<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id_user',
        'eksemplar_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'jumlah',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'tanggal_dikembalikan' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Buku
    public function eksemplar()
    {
        return $this->belongsTo(Eksemplar::class, 'eksemplar_id');
    }

    public function buku()
    {
        return $this->hasOneThrough(
            Buku::class,
            Eksemplar::class,
            'id',
            'id',
            'eksemplar_id',
            'buku_id'
        );
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
