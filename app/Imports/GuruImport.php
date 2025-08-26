<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'password' => Hash::make('simperpus123'),
                'role' => 'guru',
            ]
        );

        return Guru::updateOrCreate(
            ['nip' => $row['nip']],
            [
                'user_id' => $user->id,
                'nama' => $row['nama'],
            ]
        );
    }
}
