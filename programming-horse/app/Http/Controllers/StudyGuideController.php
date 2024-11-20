<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\StudyGuide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StudyGuideController extends Controller
{
    public function getStudyGuideWithRelations($studyGuideId)
    {
        // Retrieve a study guide by ID
        $studyGuide = StudyGuide::findOrFail($studyGuideId);

        // Accessing relationships
        $game = $studyGuide->game; // Get associated game
        $user = $studyGuide->user; // Get associated user
        $topic = $studyGuide->topic; // Get associated topic
        $language = $studyGuide->language; // Get associated language

        return response()->json([
            'study_guide' => $studyGuide,
            'game' => $game,
            'user' => $user,
            'topic' => $topic,
            'language' => $language, 
        ]);
    }

    public function showStudyGuides()
{
    // Aggregate study guide data to calculate average scores by topic and language
    $averages = StudyGuide::select('topic_id', 'language', DB::raw('AVG(score) as average_score'))
        ->groupBy('topic_id', 'language')
        ->get();

        Log::info('Averages:', $averages->toArray());

    // Map topic IDs to their names (could also retrieve from the database if necessary)
    $topics = [
        1 => 'Data Types',
        2 => 'Object Oriented Programming',
        3 => 'Data Structures',
        4 => 'Variable Types & Declarations',
    ];

    $languages = ['Python', 'Java', 'C++'];

    // Transform the data for easy access in the Blade view
    $performanceData = [];
    foreach ($languages as $language) {
        foreach ($topics as $topicId => $topicName) {
            $performanceData[$language][$topicName] = null; // Default to "No Data Available"
        }
    }

    foreach ($averages as $average) {
        $topicName = $topics[$average->topic_id] ?? 'Unknown Topic';
        $language = $average->language;
 

        $performanceData[$language][$topicName] = round($average->average_score, 2);
        
    }

    Log::info('Performance Data:', $performanceData);


    return view('studyguides', compact('performanceData'));
}

    // Get questions by topic and language
    public function getGamesByTopicAndLanguage(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'language' => 'required|string',
        ]);

        // Assuming that questions are filtered by language using topics
        return Game::where('topic_id', $request->topic_id)
            ->with(['answers'])
            ->get();
    }
}