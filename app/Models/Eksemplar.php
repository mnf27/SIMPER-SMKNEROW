<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eksemplar extends Model
{
    protected $table = 'eksemplar';
    protected $fillable = ['buku_id', 'no_induk', 'status'];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
