<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => Hash::make('123'),
        ]);
        User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'username' => 'testuser2',
            'password' => Hash::make('1234'),
        ]);
    }
}
