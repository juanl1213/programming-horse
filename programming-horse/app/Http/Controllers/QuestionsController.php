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

        return view('admin.questionstable'); // Show the filtering form
    }

    // Get a specific question with its answers
    public function show($id)
    {
        return Question::with(['topic', 'answers'])->findOrFail($id);
    }

    // Create a new question with answers
    public function store(Request $request)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
             'language' => 'required|string|max:255',
        'topic_id' => 'required|integer',
        'question' => 'required|string',
        'correct_answer' => 'required|string',
        'incorrect_1' => 'required|string',
        'incorrect_2' => 'required|string',
        'incorrect_3' => 'required|string',
        'validated' => 'required|boolean',
        ]);

        Question::create([
            'language' => $request->input('language'),
            'topic_id' => $request->input('topic_id'),
            'question' => $request->input('question'),
            'correct_answer' => $request->input('correct_answer'),
            'incorrect_1' => $request->input('incorrect_1'),
            'incorrect_2' => $request->input('incorrect_2'),
            'incorrect_3' => $request->input('incorrect_3'),
            'validated' => $request->input('validated'),
        ]);


        return redirect()->route('questions.filter')->with('success', 'Question added successfully.');    }


    public function create()
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.add-question'); // View for adding a question
    }

    // Update an existing question with answers
    public function update(Request $request)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
             'language' => 'required|string|max:255',
            'question' => 'required|string',
            'correct_answer' => 'required|string',
            'incorrect_1' => 'required|string',
            'incorrect_2' => 'required|string',
            'incorrect_3' => 'required|string',
            'validated' => 'required|boolean',
        ]);
        

        $question = Question::where('question_id', $request->question_id)->firstOrFail();


        $question->update(        [    
            'question' => $request->question,
            'correct_answer' => $request->correct_answer,
            'incorrect_1' => $request->incorrect_1,
            'incorrect_2' => $request->incorrect_2,
            'incorrect_3' => $request->incorrect_3,
            'validated' => $request->validated,
        ]);

        $updated_question = Question::where('question_id', $request->question_id)
                             ->get();

     /*    dd($updated_question); */




  /*       // Find and update the question
    $question = Question::where('question_id', $question_id)->firstOrFail();



    $question->update([
        'question_id' => $request->question_id,
        'question' => $request->question,
        'correct_answer' => $request->correct_answer,
         'incorrect_1' => $request->incorrect_1,
        'incorrect_2' => $request->incorrect_2,
        'incorrect_3' => $request->incorrect_3, 
    'validated' => $request->validated
    ]); */

        // Redirect to the filter route with POST method
        return redirect()->route('questions.filter', [
            'topic_id' => $request->input('topic_id'),
            'language' => $request->input('language'),
        ])->with('success', 'Question updated successfully.');
    }

    // Delete a question and its answers
    public function destroy($id)
    {
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Find the question by ID and delete it
    $question = Question::where('question_id', 50000)->firstOrFail();
    $question->delete();

    // Redirect to the questions filter page with a success message
    return redirect()->route('questions.filter')->with('success', 'Question deleted successfully.');
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
