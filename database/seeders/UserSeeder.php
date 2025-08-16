<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Guru 1',
            'email' => 'guru@mail.com',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);
        User::create([
            'name' => 'Siswa 1',
            'email' => 'siswa@mail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);
    }
}
