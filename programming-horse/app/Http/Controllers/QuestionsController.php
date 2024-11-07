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
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.questions-filter'); // Show the filtering form
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
    public function update(Request $request, $question_id)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Validate the   request data
        $request->validate([
            'language' => 'required|string|max:255',
            'question' => 'required|string',
            'correct_answer' => 'required|string',
            'incorrect_1' => 'required|string',
            'incorrect_2' => 'required|string',
            'incorrect_3' => 'required|string',
            'validated' => 'required|boolean',
        ]);

        // Find and update the question
    $question = Question::where('question_id', $question_id)->firstOrFail();
    $question->update($request->only([
        'language', 'question', 'correct_answer', 'incorrect_1', 
        'incorrect_2', 'incorrect_3', 'validated'
    ]));

     // Clear any previous error flash data
     session()->forget('errors');

        // Redirect to the filter route with POST method
    return redirect()->route('questions.filter')
    ->withInput([
        'topic_id' => $request->input('topic_id'),
        'language' => $request->input('language')
    ])->with('success', 'Question updated successfully.');
    }

    // Delete a question and its answers
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->answers()->delete(); // Delete associated answers
        $question->delete();
        return response()->noContent();
    }
    
    
    // Filter questions based on topic_id and language
    public function filter(Request $request)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

         // Validate the input
         $request->validate([
            'topic_id' => 'required|integer',
            'language' => 'required|string|max:255',
        ]);

        // Fetch questions that match the topic_id and language
        $questions = Question::where('topic_id', $request->topic_id)
                             ->where('language', $request->language)
                             ->get();

        // Pass the filtered questions to the view
        return view('admin.questionstable', compact('questions', 'request'));
    }

    public function edit($question_id)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Retrieve the specific question to edit
        $editQuestion = Question::where('question_id', $question_id)->first();

        if (!$editQuestion) {
            return redirect()->route('questions')->with('error', 'Question not found.');
        }

        // Pass the question to the edit view
        return view('admin.edit-question', compact('editQuestion'));
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
