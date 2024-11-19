<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GameService;
use App\Models\Game;
use App\Models\Round;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GameController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function loadNewQuestion($gameId, $topicId, $language)
    {
        $question = $this->gameService->loadQuestion($gameId, $topicId, $language);
        if ($question) {
            \Log::info('Question found:', $question); // Log question data for debugging
            return response()->json($question);
        } else {
            \Log::info('No question returned from loadQuestion.');
            return response()->json([], 404);
        }
    }

    public function startGame(Request $request)
    {
        // Validate or retrieve existing game data
        $validated = $request->validate([
            'programming_language' => 'required|in:Python,C++,Java',
            'topic_id' => 'required|integer|between:1,4',
        ]);

        $game = Game::create([
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'language' => $validated['programming_language'],
            'topic_id' => $validated['topic_id'],
            'game_state' => 'active',
            'game_status' => 'active',
            'game_winner' => 'none',
        ]);

        session([
            'user_points' => 0,
            'com_points' => 0,
            'game_id' => $game->game_id,
            'programming_language' => $validated['programming_language'],
            'topic_id' => $validated['topic_id'],
            'round_num' => 1, // Start with round 1
        ]);

        //$gameId = $game->game_id;
        // Load the first question
        //$question = $this->gameService->loadQuestion($game->game_id, $validated['topic_id'], $validated['programming_language']);

        $question = Question::where('topic_id', $validated['topic_id'])
                    ->where('language', $validated['programming_language'])
                    ->inRandomOrder()
                    ->first();

        if ($question) {
            session(['question_id' => $question->question_id]);
        }

        session(['question' => $question]);
        session(['prompt' => $question->question]);

     /*    session(['question' => $question]);
  
        session(['question_id' => $question->question_id]); */

        // Pass the game and question data to the view
        return view('playgame', [
            'gameId' => $game->game_id,
            'question' => $question,
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

    // Redirect back to the game view with the next question
    return redirect()->route('playgame')->with([
            'question' => $nextQuestion,
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,game_id',
            'round_num' => 'required|integer',
            'question_id' => 'required|exists:questions,question_id',
            'answer_selected' => 'required|string',
        ]);

        $question = Question::find($validated['question_id']);
        $isCorrect = $validated['answer_selected'] === $question->correct_answer;
        $roundWinner = $isCorrect ? 'USER' : 'COM';

        Round::create([
            'game_id' => $validated['game_id'],
            'round_num' => $validated['round_num'],
            'question_id' => $validated['question_id'],
            'answer_selected' => $validated['answer_selected'],
            'round_winner' => $roundWinner,
            'is_correct' => $isCorrect,
        ]);

        return redirect()->route('playgame')->with('success', 'Answer submitted successfully.');
    }

    public function getIncorrectAnswers($gameId, $userName)
    {
        $incorrectAnswers = $this->gameService->getIncorrectAnswers($gameId, $userName);
        return response()->json($incorrectAnswers);
    }
}
