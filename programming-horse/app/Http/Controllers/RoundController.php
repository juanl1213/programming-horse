<?php

namespace App\Http\Controllers;

use App\Models\Round;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Game;
use App\Models\StudyGuide;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


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

        if($isCorrect) {
            session(['correct_answers' => session('correct_answers', 0) + 1]);
        }

        if ($validatedData['selection'] === $comAnswer) {
            $roundWinner = 'none'; // User and COM selected the same answer
        } elseif ($isCorrect) {
            $roundWinner = 'USER'; // User's answer is correct
        } elseif ($comAnswer === $question->correct_answer) {
            $roundWinner = 'COM'; // COM's answer is correct
        } else {
            $roundWinner = 'none'; // Neither selected the correct answer
        }

        /*  $userPoints = session('user_points', 0);
            $comPoints = session('com_points', 0); */

        if ($roundWinner === 'USER') {
            session(['user_points' => session('user_points', 0) + 1]);
        } elseif ($roundWinner === 'COM') {
            session(['com_points' => session('com_points', 0) + 1]);    
        }

        /* session(['user_points' => $userPoints, 'com_points' => $comPoints]);*/
        session(['question' => $question]);
        // Step 4: Store the round in the database
        $round = Round::create([
            'game_id' => $validatedData['game_id'],
            'round_id' => Round::where('game_id', $validatedData['game_id'])->max('round_id') + 1,
            'question_id' => $validatedData['question_id'],
            'answer_selected' => $validatedData['selection'],
            'round_winner' => $roundWinner,
            'is_correct' => $isCorrect ? 'yes' : 'no',
        ]);

        // Step 5: Debugging: Check round creation
        if (!$round) {
            return back()->withErrors('Failed to store the round.');
        }

    $winner = null;
    $userScorePercentage = 0;
   if (session('user_points') >= 5) {
        $winner = 'USER';
        $totalRounds = Round::where('game_id', $gameId)->count();
      
        $userScorePercentage = (int) (session('correct_answers') / $totalRounds * 100);
    } elseif (session('com_points') >= 5) {
        $winner = 'COM';
        $totalRounds = Round::where('game_id', $gameId)->count();
        $userScorePercentage = (int) (session('correct_answers')  / $totalRounds * 100);
    } 

    if($winner) {
        $game = Game::where('game_id', $gameId)->firstOrFail();
        $game->update([
            'game_winner' => $winner,
        ]);

        StudyGuide::create([
            'user_id' => Auth::id(),
            'game_id' => $gameId,
            'language' => session('lang'),
            'topic_id' => session('topic_id'),
/*             'incorrect_answers' => $totalRounds - session('correct_answers', 0), // Total incorrect answers
 */     // Replace with logic for recommendations
            'score' => $userScorePercentage, // User's score percentage
            'recommendations_written_1' => "a",
            "recommendations_written_2" => "a",
            "recommendations_written_3" => "a",
            "recommendations_video_1" => "a",
            "recommendations_video_2" => "a",
            "recommendations_video_3" => "a",
        ]);

        $studyGuide = StudyGuide::where('game_id', $gameId)
        ->firstOrFail();

    }

        // Update session with the new question data
        session(['question_id' => $question->question_id]);

        // Redirect to playgame view with the updated question and round data
        /*   return view('playgame', [
            'user_selection' => $validatedData['selection'],
            'com_selection' => $comAnswer,
            'gameId' => $gameId,
            'question' => $question,
            'round_num' => $nextRoundNum
        ]); */
        
        session(['user_score_percentage' => $userScorePercentage]);
        // Step 6: Redirect to playgame without complex with() chaining
        return redirect()->route('playgame')->with([
            
            'question' => $question,
            'user_selection' => $validatedData['selection'],
            'com_selection' => $comAnswer,
            'round_winner' => $roundWinner,
            /*'user_points' => $userPoints,
              'com_points' => $comPoints, */
            'winner' => $winner,
        ]);
    }

    public function nextRound(Request $request)
    {
        // Increment the round number in session
        session(['round_num' => session('round_num', 1) + 1]);
   

        // Load the next question based on session data
        $nextQuestion = Question::where('topic_id', session('topic_id'))
            ->where('language', session('programming_language'))
            ->whereNotIn('question_id', function ($query) {
                $query->select('question_id')
                    ->from('rounds')
                    ->where('game_id', session('game_id'));
            })
            ->inRandomOrder()
            ->first();

        session(['prompt' => $nextQuestion->question]);
        session(['question' => $nextQuestion]);
        session(['question_id' => $nextQuestion->question_id]);
        session(['correct_answer' => $nextQuestion->correct_answer]);
   
        // Redirect back to the game view with the next question
        return redirect()->route('playgame')->with([
            'question' => $nextQuestion,
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
