<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    // Get a specific game by ID
    public function show($id)
    {
        return Game::findOrFail($id);
    }

    // Create a new game
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'game_state' => 'required|in:in_progress,completed',
            'game_status' => 'required|string|max:255', // e.g., "HOR"
        ]);

        $game = Game::create($request->all()); // Create and return the new game
        return response()->json($game, 201); // Return the created game with a 201 status
    }

    // Update an existing game (to continue)
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        
        $request->validate([
            'game_state' => 'sometimes|required|in:in_progress,completed',
            'game_status' => 'sometimes|required|string|max:255',
            'game_winner' => 'nullable|string|in:user,computer', // Specify who won the game
        ]);

        // Update only if the game state is 'completed' to set the winner
        if ($request->has('game_state') && $request->game_state === 'completed') {
            $request->validate([
                'game_winner' => 'required|string|in:user,computer', // Winner must be specified
            ]);
        }

        $game->update($request->only('game_state', 'game_status', 'game_winner'));
        return $game;
    }

    // Restart a game
    public function restart($id)
    {
        $game = Game::findOrFail($id);

        // Reset game fields to start a new game
        $game->game_state = 'in_progress';
        $game->game_status = ''; // Reset to initial status
        $game->game_winner = null; // No winner yet

        $game->save();
        return $game;
    }

    public function finishGame(Request $request)
    {
        // Assume $request contains the user's game data including incorrect answers
        $userName = $request->input('user_name');
        $incorrectAnswers = $request->input('incorrect_answers'); // Array of incorrect answers
        
        // Generate the study guide text
        $studyGuideText = $this->generateStudyGuideText($incorrectAnswers);

        // Send the request to the API to create a study guide
        $response = Http::post('https://your-api-endpoint.com/create-study-guide', [
            'user_name' => $userName,
            'study_guide' => $studyGuideText,
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Study guide created successfully!']);
        } else {
            return response()->json(['error' => 'Failed to create study guide.'], 500);
        }
    }

    private function generateStudyGuideText($incorrectAnswers)
    {
        $text = "This user got these incorrect answers:\n\n";
        
        foreach ($incorrectAnswers as $answer) {
            $text .= "Question: {$answer['question']}\n";
            $text .= "Your Answer: {$answer['selected_answer']}\n";
            $text .= "Correct Answer: {$answer['correct_answer']}\n\n";
        }

        $text .= "Recommendations for improvement:\n";
        $text .= "1. Review the correct answers and explanations.\n";
        $text .= "2. Practice similar questions on this topic.\n";
        $text .= "3. Consider studying the following resources:\n";
        $text .= "- Online tutorials\n";
        $text .= "- Discussion forums for peer support\n";

        return $text;
    }
}
