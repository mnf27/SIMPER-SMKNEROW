<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rombel;

class RombelSeeder extends Seeder
{
    public function run(): void
    {
        $jurusanList = [
            'APAT' => 1, // satu kelas per tingkat
            'DPB' => 1,
            'DKV' => 2, // dua kelas per tingkat
            'TSM' => 2,
        ];

        $tingkatList = [
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        foreach ($tingkatList as $tingkatAngka => $tingkatRomawi) {
            foreach ($jurusanList as $jurusan => $jumlahKelas) {
                if ($jumlahKelas === 1) {
                    Rombel::create([
                        'nama' => "{$tingkatRomawi} {$jurusan}",
                        'tingkat' => $tingkatAngka,
                        'jurusan' => $jurusan,
                    ]);
                } else {
                    for ($i = 1; $i <= $jumlahKelas; $i++) {
                        Rombel::create([
                            'nama' => "{$tingkatRomawi} {$jurusan} {$i}",
                            'tingkat' => $tingkatAngka,
                            'jurusan' => $jurusan,
                        ]);
                    }
                }
            }
        }
    }
}
