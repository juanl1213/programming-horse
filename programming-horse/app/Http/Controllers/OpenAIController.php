<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use OpenAI\Client;
use App\Models\StudyGuide;
use App\Models\User;
use App\Models\Question;
use App\Models\Round;
use OpenAI\Contracts\TransporterContract;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{

    protected string $apiKey;
    protected OpenAI\Client $openaiclient;

    public function __construct()
    {
        $this->apiKey = 'api-key goes here';
        $this->openaiclient = OpenAI::client($this->apiKey);
    }

    public function getRecommendationsForGame(Request $request)
    {
    $topicId = $request->input('topic_id');
    $language = $request->input('language');

    // Map topic_id to topic name
    $topics = [
        1 => 'Data Types',
        2 => 'Object Oriented Programming',
        3 => 'Data Structures',
        4 => 'Variable Types & Declarations',
    ];
    $topicName = $topics[$topicId] ?? 'Unknown Topic';

    if ($topicName === 'Unknown Topic' || !$language) {
        return response()->json(['error' => 'Invalid topic or language.'], 400);
    }
    $incorrectQuestions = session('incorrect_questions', []);
    $incorrectQuestionsFormatted = '';
    if (!empty($incorrectQuestions)) {
        $incorrectQuestionsFormatted = "Here are some of the questions the user got wrong:\n";
        foreach ($incorrectQuestions as $index => $question) {
            $incorrectQuestionsFormatted .= ($index + 1) . ". " . $question . "\n";
        }
    } else {
        $incorrectQuestionsFormatted = "The user has no specific incorrectly answered questions.";
    }

    // Prompt for OpenAI
    $prompt = "Generate three text-based recommendations and three video-based recommendations for improving performance in the topic '$topicName' for the language '$language'.
Examples of text-based recommendations include website links, articles, blog posts, etc. For text-based recommendations, please only include links to free reputable
(e.g. GeeksForGeeks, W3School, FreeCodeCamp, official documentation, etc). Ensure the links are pubicly accessible. Examples of video recommendations include YouTube video links.
PLEASE refine your recommendations based on the incorrectly answered questions: '$incorrectQuestionsFormatted'";


    
    try {
        $completion = $this->openaiclient->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        // Extract the recommendations
        $recommendations = $completion['choices'][0]['message']['content'];
        


        return response()->json([
            'topic' => $topicName,
            'language' => $language,
            'recommendations' => $recommendations,
        ]);
    } catch (\Exception $e) {
        Log::error('Hit catch statement. Failed to generate recommendations.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return response()->json(['error' => 'Failed to generate recommendations.'], 500);
    }
}


    public function getLowestScoreRecommendation(Request $request)
    {
        // Predefined topics (or fetch them from the database if dynamic)
        $topics = [
            1 => 'Data Types',
            2 => 'Object Oriented Programming',
            3 => 'Data Structures',
            4 => 'Variable Types & Declarations',
        ];

        // Step 1: Fetch scores grouped by topic and language
        $lowestScore = StudyGuide::select('topic_id', 'language', DB::raw('AVG(score) as average_score'))
            ->groupBy('topic_id', 'language')
            ->orderBy('average_score', 'asc') // Get the lowest score first
            ->first();

        if (!$lowestScore) {
            return response()->json(['message' => 'No data available to generate recommendations.']);
        }


        // Step 2: Resolve topic name and language
        $topicName = $topics[$lowestScore->topic_id] ?? 'Unknown Topic';
        $language = $lowestScore->language;

        // Step 3: Generate recommendations for the topic and language with the lowest score
        $prompt = "Generate three text-based recommendations and three video-based recommendations for improving performance in the topic '$topicName' for the language '$language'.
        Examples of text-based recommendations include website links, articles, blog posts, etc. For text-based recommendations, please only include links to free reputable
        (e.g. GeeksForGeeks, W3School, FreeCodeCamp, official documentation, etc). Examples of video recommendations include YouTube video links. Please ensure that the resources cotained
        in the links provided actually exist and have been revised or created within no more than 4 years.";

        try {
            $completion = $this->openaiclient->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            // Extract recommendations
            $recommendations = $completion['choices'][0]['message']['content'];

            

            return response()->json([
                'topic' => $topicName,
                'language' => $language,
                'recommendations' => $recommendations,
            ]);
        } catch (\Exception $e) {

            Log::error('Hit catch statement. Failed to generate recommendations.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    

            return response()->json(['error' => 'Failed to generate recommendations.'], 500);
        }
    }


    public function generateChatCompletion(Request $request)
    {
        $userMessage = $request->input('message');

        try {
            $completion = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

            return response()->json([
                'response' => $completion['choices'][0]['message']['content'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createStudyGuide(Request $request)
    {
        
        $gameId = $request->input('game_id');
        $userName = $request->input('user_name');
        $totalQuestions = Round::where('round_winner', $userName)
            ->where('game_id', 1)->count();
        // Find the user
        $user = User::where('user_name', $userName);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate the study guide content
        $incorrectAnswers = array_values(Round::where('game_id',1)->pluck('question_id')->toArray());
        $questionList = Question::whereIn('question_id',$incorrectAnswers)->select('topic_id','question','incorrect_1','incorrect_2','incorrect_3')->get()->toArray();
        $language = Game::where('game_id', $gameId)->pluck('language')->first();
        $topic_id = Game::where('game_id', $gameId)->pluck('topic_id')->first();
        $studyGuideContent = $this->generateStudyGuide($gameId, $userName, $language, $topic_id, $questionList, $totalQuestions);

        // Parse the CSV data
        $lines = explode("\n", trim($studyGuideContent));
        $header = str_getcsv(array_shift($lines)); // Get the header row
        $data = str_getcsv($lines[0]); // Assuming there's only one row of data
        // Combine header and data into an associative array
        $csvArray = array_combine($header, $data);
        // Validate the required fields
        $requiredFields = ['game_id', 'user_name', 'language', 'topic_id', 'recommendations_written_1', 'recommendations_written_2', 'recommendations_written_3', 'recommendations_video_1', 'recommendations_video_2', 'recommendations_video_3'];
        
        foreach ($requiredFields as $field) {
            if (!isset($csvArray[$field])) {
                return response()->json(['error' => "Missing required field: $field"], 422);
            }
        }

        // Save the study guide to the database
        $studyGuide = new StudyGuide();
        $studyGuide->game_id = $csvArray['game_id'];
        $studyGuide->user_name = $csvArray['user_name'];
        $studyGuide->language = $csvArray['language']; 
        $studyGuide->topic_id = $csvArray['topic_id'];
        $studyGuide->recommendations_written_1 = $csvArray['recommendations_written_1'];
        $studyGuide->recommendations_written_2 = $csvArray['recommendations_written_2'];
        $studyGuide->recommendations_written_3 = $csvArray['recommendations_written_3'];
        $studyGuide->recommendations_video_1 = $csvArray['recommendations_video_1'];
        $studyGuide->recommendations_video_2 = $csvArray['recommendations_video_2'];
        $studyGuide->recommendations_video_3 = $csvArray['recommendations_video_3'];
        $studyGuide->created_by = 'system';
        $studyGuide->save();

        return response()->json(['message' => 'Study guide created successfully', 'study_guide' => $studyGuide]);
    }


    private function generateStudyGuide($gameId, $userName, $language, $topicId, $incorrectAnswers, $totalQuestions)
    {
        // Calculate percentage of correct answers
        $correctAnswersCount = $totalQuestions - count($incorrectAnswers);
        $percentCorrect = ($correctAnswersCount / $totalQuestions) * 100;

        // Construct the prompt based on the incorrect answers
        $prompt = "Here is the data from the current game, each key is associated with a value, please use these key value pairs for the remainder of this prompt:\n";
        $prompt .= "Key value pairs:\n";
        $prompt .= "    \"game_id\" => $gameId\n";
        $prompt .= "    \"user_name\" => '$userName'\n";
        $prompt .= "    \"language\" => '$language'\n";
        $prompt .= "    \"topic_id\" => $topicId\n";
        $prompt .= "    \"question_set\" => [\n";

        foreach ($incorrectAnswers as $options) {
            $q = $options['question'];
            $a = [$options['incorrect_1'], $options['incorrect_2'], $options['incorrect_3']];
            $prompt .= "        \"$q\" => [\n";
            $prompt .= "            \"question\" => \"$q\",\n";
            $prompt .= "            \"incorrect_answer_set\" => [" . implode(", ", $a) . "]\n";
            $prompt .= "        ],\n";
        }
        $prompt .= "    ]\n\n";
        $prompt .= "With the information above, please generate a set of recommendations, 3 text-based recommendations along with 3 video-based recommendations in CSV format.\n";
        $prompt .= "The CSV format should go as follows:\n\n";
        $prompt .= "Each CSV column must map to the following structure:\n";
        $prompt .= "    \"game_id\" => \"game_id\"\n";
        $prompt .= "    \"user_name\" => \"user_name\"\n";
        $prompt .= "    \"language\" => \"language\"\n";
        $prompt .= "    \"topic_id\" => \"topic_id\"\n";
        $prompt .= "    \"your first text-based recommendation\" => \"recommendations_written_1\"\n";
        $prompt .= "    \"your second text-based recommendation\" => \"recommendations_written_2\"\n";
        $prompt .= "    \"your third text-based recommendation\" => \"recommendations_written_3\"\n";
        $prompt .= "    \"your first video-based recommendation\" => \"recommendations_video_1\"\n";
        $prompt .= "    \"your second video-based recommendation\" => \"recommendations_video_2\"\n";
        $prompt .= "    \"your third video-based recommendation\" => \"recommendations_video_3\"\n\n";
        $prompt .= "Please use the game_id, the user_name, language, and topic_id from the key value pairs above.\n";
        $prompt .= "ONLY GENERATE CSV FILE NOTHING ELSE.";

        // Make the API request to OpenAI
        $response = $this->openaiclient->chat()->create([
            'model'=> 'gpt-3.5-turbo',
            'messages'=>[
                ['role'=>'user','content'=>$prompt]
            ]
            ]);
        // Check for a successful response
        if ($response) {
            // Return the CSV data
            $csvData = $response['choices'][0]['message']['content'];

            return $csvData;
        } else {
            // Handle error response
            throw new \Exception('Error generating study guide: ' . $response->body());
        }  
    }
}