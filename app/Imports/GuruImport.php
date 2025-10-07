<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class GuruImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // skip 6 baris header pertama
        $rows = $rows->skip(6);

        foreach ($rows as $row) {
            if (!$row[1])
                continue; // skip kosong

            $nama = trim($row[1]);
            $jk = $row[3] == 'L' ? 'L' : 'P';
            $nuptk = $row[2];
            $nip = $row[6];
            $status = $row[7];
            $tglLahir = \Carbon\Carbon::parse($row[5]);

            // username = ddmmyy
            $username = $tglLahir->format('dmy');

            $user = User::create([
                'nama' => $nama,
                'username' => $username,
                'email' => $username . '@smkrowo.sch.id',
                'password' => Hash::make('123456'),
                'role' => 'guru',
                'jenis_kelamin' => $jk,
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nuptk' => $nuptk,
                'nip' => $nip,
                'status_kepegawaian' => $status,
            ]);
        }
    }
}
