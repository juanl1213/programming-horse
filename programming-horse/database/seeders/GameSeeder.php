<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();  // Get the first user (you can adjust this to target a specific user)
        $topic = Topic::inRandomOrder()->first();  // Get a random topic

        // Create one game for the selected user
        $game = Game::create([
            'user_name' => $user->user_name,
            'language' => 'en',  // You can set this as needed
            'topic_id' => $topic->topic_id,  // Randomly selected topic_id
            'game_state' => 'in_progress',  // Example state
            'game_status' => 'started',  // Example status
            'game_winner' => null,  // No winner yet
        ]);

    }
}
