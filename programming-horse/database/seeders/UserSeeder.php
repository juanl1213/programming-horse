<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 10 users (adjust the number as needed)
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'user_name' => $faker->userName,  // Random username
                'email' => $faker->unique()->safeEmail,  // Unique email
                'password' => Hash::make('password'),  // Example password
            ]);
        }
    }
}
