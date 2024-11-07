<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GameService;

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
        // Validate the input
        $validated = $request->validate([
            'programming_language' => 'required|in:Python,C++,Java',
            'topic_id' => 'required|integer|between:1,4',
        ]);

        // Store the selections in the session
        session(['programming_language' => $validated['programming_language']]);
        session(['topic_id' => $validated['topic_id']]);

        return view('playgame');
    }

    public function saveRound(Request $request)
    {
        $this->gameService->insertRound(
            $request->gameId,
            $request->roundNum,
            $request->questionId,
            $request->answerSelected,
            $request->roundWinner,
            $request->isCorrect
        );
        
        return response()->json(['status' => 'Round saved successfully']);
    }

    public function getIncorrectAnswers($gameId, $userName)
    {
        $incorrectAnswers = $this->gameService->getIncorrectAnswers($gameId, $userName);
        return response()->json($incorrectAnswers);
    }
}
