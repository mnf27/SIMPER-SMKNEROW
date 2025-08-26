<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'email' => 'admin@smknrowo.sch.id',
            'password' => Hash::make('simperpus123'),
            'role' => 'admin',
        ]);
    }
}
