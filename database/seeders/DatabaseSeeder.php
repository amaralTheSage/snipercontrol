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
            'name' => 'teste',
            'email' => 'teste@gmail.com',
            'password' => Hash::make('senha123'),
        ]);


        User::factory()->create([
            'name' => 'gabriel',
            'email' => 'gabriel@g',
            'password' => Hash::make('senha123'),
        ]);
    }
}
