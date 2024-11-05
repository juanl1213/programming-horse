<?php

namespace App\Http\Controllers;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;;
use Illuminate\Http\Request;

class TopicController extends Controller
{
   // Get all topics
   public function index()
   {
       return Topic::all();
   }

   // Get a specific topic
   public function show($id)
   {
       return Topic::with('questions')->findOrFail($id);
   }

   // Create a new topic
   public function store(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'programming_language' => 'required|string|max:255',
           'description' => 'nullable|string|max:500', // Validate description
       ]);

       return Topic::create($request->all());
   }

   // Update an existing topic
   public function update(Request $request, $id)
   {
       $topic = Topic::findOrFail($id);
       $topic->update($request->only('name', 'programming_language', 'description'));
       return $topic;
   }

   // Delete a topic
   public function destroy($id)
   {
       $topic = Topic::findOrFail($id);
       $topic->delete();
       return response()->noContent();
   }

   // Get questions based on selected topic and programming language
   public function getQuestionsByTopicAndLanguage(Request $request)
   {
       $request->validate([
           'topic_id' => 'required|exists:topics,id',
           'language' => 'required|string',
       ]);

       // Retrieve questions based on the topic and programming language
       return Questions::where('topic_id', $request->topic_id)
           ->whereHas('topic', function ($query) use ($request) {
               $query->where('programming_language', $request->language);
           })
           ->with(['answers']) // Include answers for each question
           ->get();
   } 
}
