<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

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
            $rombelNama = trim($row[6]);
            $tglLahir = Carbon::parse($row[7]);

            // Normalisasi nama rombel dari Excel agar match DB
            $rombelFormatted = str_replace('-', ' ', $rombelNama);
            $rombelFormatted = preg_replace('/([A-Z]+)(\d+)/', '$1 $2', $rombelFormatted);

            // Cari rombel di DB
            $rombel = Rombel::where('nama', $rombelFormatted)->first();

            // === Generate username ===
            $namaParts = explode(' ', $nama);
            $inisial = '';

            for ($i = 0; $i < min(3, count($namaParts)); $i++) {
                $inisial .= strtolower(substr($namaParts[$i], 0, 1));
            }

            // Kalau kurang dari 3 huruf, ambil dari awal nama
            if (strlen($inisial) < 3) {
                $inisial = strtolower(substr(str_replace(' ', '', $nama), 0, 3));
            }

            // username = inisial + ddmmyy
            $username = $inisial . $tglLahir->format('dmy');

            // Pastikan unik
            $originalUsername = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

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
                'id_rombel' => $rombel ? $rombel->id : null, // nanti diisi manual
            ]);
        }
    }
}
