<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Round;
use App\Models\Game;
use App\Models\Question;
use App\Models\User;
use App\Models\Topic;
use Faker\Factory as Faker;

class RoundSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();  // Get the first user (you can adjust this to target a specific user)
        $topic = Topic::inRandomOrder()->first();  // Get a random topic

        // Create one game for the selected user
        $game = Game::create([
            'user_name' => $user->user_name,
            'language' => 'Java',  // You can set this as needed
            'game_id' => 4,
            'topic_id' => $topic->topic_id,  // Randomly selected topic_id
            'game_state' => 'in_progress',  // Example state
            'game_status' => 'started',  // Example status
            'game_winner' => '',  // No winner yet
        ]);
        
        $game = Game::first();  // Get the first game (assuming one game per run)
        $faker = Faker::create();

        // Check if the game exists
        if ($game) {
            // Create 10 rounds for the game
            $correctAnswersCount = 0;  // To track the number of correct answers
            for ($i = 1; $i <= 10; $i++) {
                // Select a random question for this game based on topic_id and language
                $question = Question::where('topic_id', $game->topic_id)
                                    ->where('language', $game->language)
                                    ->whereNotIn('question_id', function ($query) use ($game) {
                                        $query->select('question_id')
                                              ->from('rounds')
                                              ->where('game_id', $game->id);
                                    })
                                    ->inRandomOrder()
                                    ->first();

                if ($question) {
                    $answerSelected = $faker->randomElement([
                        $question->correct_answer,
                        $question->incorrect_1,
                        $question->incorrect_2,
                        $question->incorrect_3,
                    ]);
                    $isCorrect = ($answerSelected == $question->correct_answer)? "correct" : "incorrect";

                    // Increment the correct answer count if the selected answer is correct
                    if ($isCorrect) {
                        $correctAnswersCount++;
                    }

                    // Save the round to the database
                    Round::create([
                        'round_num' => $i,
                        'game_id' => $game->game_id,
                        'question_id' => $question->question_id,
                        'answer_selected' => $answerSelected,
                        'round_winner' => $faker->randomElement([$game->user_name, 'another_user']),  // Random winner
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            // After rounds are created, determine and update the game winner
            // In this case, the winner is the user who answered the most questions correctly
            // You could modify this logic to base the winner on another criteria
            $user = User::where('user_name', $game->user_name)->first();
            
            // If the user has a higher number of correct answers, they are the winner
            if ($correctAnswersCount > 5) { // For example, if they answered more than 5 correctly, they win
                $game->update(['game_winner' => $user->user_name]);
            } else {
                // Alternatively, if you want to have a random winner for demo purposes
                $game->update(['game_winner' => $faker->randomElement([$game->user_name, 'another_user'])]);
            }
        }
    }
}
