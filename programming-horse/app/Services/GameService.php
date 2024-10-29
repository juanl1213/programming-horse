<?php

namespace App\Services;
use App\Models\Question; 
use App\Models\Round;
use Illuminate\Support\Facades\DB;

class GameService {
function loadQuestion($gameId, $topicId, $language) {
    // Fetch the first question that is relevant to the selected topic and language
    $question = Question::where('topic_id', $topicId)
                        ->where('language', $language)
                        ->whereNotIn('question_id', function ($query) use ($gameId) {
                            $query->select('question_id')
                                  ->from('rounds')
                                  ->where('game_id', $gameId);
                        })
                        ->inRandomOrder() // Randomize the order
                        ->first();

    // Initialize the result array
    $result = [];

    // Check if a question was found
    if ($question) {
        // Create an array of answers
        $answers = [
            $question->correct_answer,
            $question->incorrect_1,
            $question->incorrect_2,
            $question->incorrect_3,
        ];

        // Shuffle the answers
        shuffle($answers);

        // Prepare the result format
        $result = [
            'question_id' => $question->question_id,
            'topic_id' => $question->topic_id,
            'question' => $question->question,
            'answers' => array_values($answers), // Reset keys
        ];
    }
        return $result;
    }
        function insertRound($gameId, $roundNum, $questionId, $answerSelected, $roundWinner, $isCorrect) {
            // Create a new round instance
            $round = new Round();
            
            // Set the round properties
            $round->round_num = $roundNum;
            $round->game_id = $gameId;
            $round->question_id = $questionId;
            $round->answer_selected = $answerSelected;
            $round->round_winner = $roundWinner;
            $round->is_correct = $isCorrect;
        
            // Save the round to the database
            $round->save();
    }

    function getIncorrectAnswers($gameId, $userName) {
        // Fetch all rounds for the specific game where the answer was incorrect
        $incorrectRounds = Round::where('game_id', $gameId)
                                ->where('is_correct', 'incorrect') 
                                ->whereHas('game', function($query) use ($userName) {
                                    $query->where('user_name', $userName);
                                })
                                ->with('question') 
                                ->get();
    
        // Initialize the result array
        $result = [];
    
        // Iterate over the incorrect rounds
        foreach ($incorrectRounds as $round) {
            // Get the question and its answers
            $question = $round->question; // Assuming you have a question relationship defined
    
            if ($question) {
                $answers = [
                    $question->correct_answer,
                    $question->incorrect_1,
                    $question->incorrect_2,
                    $question->incorrect_3,
                ];
                // Add the question and its answers to the result array
                $result[$question->question] = $answers;
            }
        }
    
        return $result;
    }
}
