<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoundController extends Controller
{ 
    // Create a new round
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,game_id',
            'question_id' => 'required|exists:questions,id', // Assuming questions table has an 'id' column
            'answer_selected' => 'required|string', // The answer selected by the user
            'round_num' => 'required|integer', // Ensure round_num is included
            'is_correct' => 'required|string',
        ]);

        // Create and return the new round
        $round = Round::create($request->all());
        return response()->json($round, 201); // Return the created round with a 201 status
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
