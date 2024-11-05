<?php

namespace App\Http\Controllers;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;;

use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    // Get all questions with their answers
    public function index()
    {
          // Check if the authenticated user is an admin
          if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Fetch all questions from the database
        $questions = Question::all();

        // Pass the questions data to the view
        return view('admin.questionstable', compact('questions'));
    }

    // Get a specific question with its answers
    public function show($id)
    {
        return Question::with(['topic', 'answers'])->findOrFail($id);
    }

    // Create a new question with answers
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'language' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'answers' => 'required|array|min:4|max:4', // Expect exactly four answers
            'answers.*.content' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::create($request->only('content', 'language','topic_id'));

        // Save the answers
        foreach ($request->answers as $answer) {
            $question->answers()->create($answer);
        }

        return $question->load('answers');
    }

    // Update an existing question with answers
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->only('content', 'language','topic_id'));

        // Update answers (you can enhance this logic as needed)
        foreach ($request->answers as $index => $answer) {
            $question->answers()->updateOrCreate(
                ['id' => $answer['id']], // Assuming answer ID is provided
                $answer
            );
        }

        return $question->load('answers');
    }

    // Delete a question and its answers
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->answers()->delete(); // Delete associated answers
        $question->delete();
        return response()->noContent();
    }

    // Get questions by topic and language
    public function getQuestionsByTopicAndLanguage(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'language' => 'required|string',
        ]);

        // Assuming that questions are filtered by language using topics
        return Question::where('topic_id', $request->topic_id)
            ->with(['answers'])
            ->get();
    }
}
