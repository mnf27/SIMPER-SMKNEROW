<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function modal(array $row)
    {
        $guru = Guru::updateOrCreate(
            ['nip' => $row['nip']],
            [
                'nama' => $row['nama'],
                'email' => $row['email'],
                'alamat' => $row['alamat'] ?? null,
                'no_telp' => $row['no_telp'] ?? null,
            ]
        );

        User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['nama'],
                'role' => 'guru',
                'password' => Hash::make($row['nip']),
            ]
        );

        return $guru;
    }
}
