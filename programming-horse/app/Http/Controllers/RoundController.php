<?php

namespace App\Http\Controllers;

use App\Models\Round;
use Illuminate\Http\Request;
use App\Models\Question;

class RoundController extends Controller
{ 
    // Create a new round
    public function store(Request $request)
    {
        // Step 1: Validate incoming data, ensuring `game_id` is present in session
    $validatedData = $request->validate([
        'game_id' => 'required|exists:games,game_id',
        'round_num' => 'required|integer',
        'question_id' => 'required|exists:questions,question_id',
        'selection' => 'required|string',
    ]);

    // Retrieve the game ID from the session, checking for its existence
    $gameId = session('game_id');
    if (!$gameId) {
        return redirect()->route('game.selection')->withErrors('Game ID is missing from the session.');
    } 

    //$nextRoundNum = (int) Round::where('game_id', $gameId)->max('round_num') + 1;
    // Step 2: Fetch the question and determine the answers
    $question = Question::findOrFail($validatedData['question_id']);
    $answers = [
        $question->correct_answer, 
        $question->incorrect_1, 
        $question->incorrect_2, 
        $question->incorrect_3
    ];

    // Step 3: Generate COM answer and determine correctness of user answer
    $comAnswer = $answers[array_rand($answers)];
    $isCorrect = $validatedData['selection'] === $question->correct_answer;
    $roundWinner = $isCorrect ? 'USER' : ($comAnswer === $question->correct_answer ? 'COM' : 'none');

    // Step 4: Store the round in the database
    $round = Round::create([
        'game_id' => $validatedData['game_id'],
        'round_id' => Round::where('game_id', $validatedData['game_id'])->max('round_id') + 1,
        'question_id' => $validatedData['question_id'],
        'answer_selected' => $validatedData['selection'],
        'round_winner' => $roundWinner,
        'is_correct' => $isCorrect ? 'yes' : 'no',
    ]);

    // Debugging: Check round creation
    if (!$round) {
        return back()->withErrors('Failed to store the round.');
    }

 /*    // Step 5: Store the results in session individually
    session([
        'user_selection' => $validatedData['selection'],
        'com_selection' => $comAnswer,
        'round_winner' => $roundWinner,
        'is_correct' => $isCorrect ? 'yes' : 'no',
    ]); */

    $nextRoundNum = session('round_num', 1) + 1;
    session(['round_num' => $nextRoundNum]);

    // Fetch the next question based on topic and language
    $question = Question::where('topic_id', session('topic_id'))
                ->where('language', session('programming_language'))
                ->whereNotIn('question_id', function ($query) use ($gameId) {
                    $query->select('question_id')
                          ->from('rounds')
                          ->where('game_id', $gameId);
                })
                ->inRandomOrder()
                ->first();

    // If there’s no question left, handle end of game
    if (!$question) {
        return redirect()->route('game.end')->with('message', 'No more questions for this game.');
    }

    // Update session with the new question data
    session(['question_id' => $question->question_id]);

    // Redirect to playgame view with the updated question and round data
    return view('playgame', [
        'gameId' => $gameId,
        'question' => $question,
        'round_num' => $nextRoundNum
    ]);
    
    // Step 6: Redirect to playgame without complex with() chaining
    return redirect()->route('playgame')->with([
        'question' => $question,
        'user_selection' => $validatedData['selection'],
        'com_selection' => $comAnswer,
        'round_winner' => $roundWinner,
    ]);
    }

    // Get all rounds for a specific game
    public function getRoundsByGame($gameId)
    {
        return Round::where('game_id', $gameId)->get();
    }

    // Update a round (e.g., to set the round winner)
    public function update(Request $request, $id)
    {
        $round = Round::findOrFail($id);
        
        $request->validate([
            'round_winner' => 'required|string|in:user,computer,tie', // Allow tie as an option
            'round_num' => 'sometimes|integer', // Allow updating round_num if needed
        ]);

        // Always update the round_winner based on the request
        $round->round_winner = $request->round_winner;

        // Optionally update round_num if provided
        if ($request->has('round_num')) {
            $round->round_num = $request->round_num;
        }

        $round->save(); // Save changes
        return $round;
    }
}
