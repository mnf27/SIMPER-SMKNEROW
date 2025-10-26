<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nuptk',
        'nip',
        'jenis_kelamin',
        'status_kepegawaian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
