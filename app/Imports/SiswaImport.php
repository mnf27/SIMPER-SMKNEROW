<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class SiswaImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // skip 6 baris header pertama
        $rows = $rows->skip(6);

        foreach ($rows as $row) {
            if (!$row[1])
                continue;

            $nama = trim($row[1]);
            $jk = $row[3] == 'L' ? 'L' : 'P';
            $nipd = $row[2];
            $nisn = $row[4];
            $tglLahir = \Carbon\Carbon::parse($row[7]);

            // username = 3 huruf nama depan + ddmmyy
            $prefix = strtolower(substr(str_replace(' ', '', $nama), 0, 3));
            $username = $prefix . $tglLahir->format('dmy');

            $user = User::create([
                'nama' => $nama,
                'username' => $username,
                'email' => $username . '@smkrowo.sch.id',
                'password' => Hash::make('1234556'),
                'role' => 'siswa',
                'jenis_kelamin' => $jk,
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nipd' => $nipd,
                'nisn' => $nisn,
                'id_rombel' => null, // nanti diisi manual
            ]);
        }
    }
}
