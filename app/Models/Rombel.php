<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'nama',
        'tingkat',
        'jurusan',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_rombel');
    }

    public function getTingkatLabelAttribute()
    {
        return match ($this->tingkat) {
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
            default => $this->tingkat, // biar aman kalau ada data lain
        };
    }
}