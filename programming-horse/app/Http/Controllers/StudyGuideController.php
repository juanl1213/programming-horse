<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $language = $studyGude->language; // Get associated language

        return response()->json([
            'study_guide' => $studyGuide,
            'game' => $game,
            'user' => $user,
            'topic' => $topic,
            'language' => $language, 
        ]);
    }
}