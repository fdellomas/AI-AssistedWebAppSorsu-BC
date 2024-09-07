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
        User::create([
            'first_name' => 'admin',
            'middle_name' => 'admin',
            'last_name' => 'admin',
            'extension' => 'sr.',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin1234'),
            'role' => 'admin',
        ]);
    }
}
