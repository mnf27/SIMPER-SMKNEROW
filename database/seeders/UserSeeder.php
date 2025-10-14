<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@smknrowo.sch.id',
            'password' => Hash::make('simperpus123'),
            'role' => 'admin',
            'jenis_kelamin' => 'P',
        ]);
    }
}
