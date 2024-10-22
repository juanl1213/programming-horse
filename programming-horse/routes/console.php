<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\QuestionsService;
use App\Services\SimulateGameService;
use App\Models\Round;
use Illuminate\Support\Facades\DB;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('check', function(){
    $rounds = DB::table('rounds')->where('is_correct', '=', 'incorrect')
    ->where('game_id', '=', 1)->where('round_winner', '=', 'computer')->get();
    dd($rounds);
    // test code to check these conditions
    
});

Artisan::command('simulate-game {gamesFilePath} {roundsFilePath}', function(string $gamesFilePath, string $roundsFilePath){
    
    $simulateGameService = new SimulateGameService;    

    try
    {
            $games = $simulateGameService->prepareGameData($gamesFilePath);
            $simulateGameService->insertData($games, 'games');
            $rounds = $simulateGameService->prepareRoundsData($roundsFilePath);
            $simulateGameService->insertData($rounds, 'rounds');
    }catch (\Exception $e)
    {
        Log::error("Error importing questions from: " . $e->getMessage());
        $this->error("Failed to import questions from. Check the logs for more details.");
    }
    $this->info("Successfully Imported" . "!");
});

//This is how we will automate inserting all the questions to the database
Artisan::command('hydrate-questions {csvFilePath}', function(string $csvFilePath){

    //Note the csvfile path entered while using the command must be
    // a valid file otherwise it will log the error and will not be imported
    
    //Our question service contains the logic for things to do with questions
    $questionService = new QuestionsService;
    try
    {
        $questions = $questionService->validateCsv($csvFilePath);
        //if the questions have more than 0 agter validating we then attempt
        //to insert into the database
        if(count($questions) > 0)
        {
            $questionService->insertQuestions($questions);
        }else
        {
            Log::warning("No valid questions found in $csvFilePath.");
            echo "No valid questions found. Please check the CSV file.";
        }
    }catch (\Exception $e)
    {
        Log::error("Error importing questions from $csvFilePath: " . $e->getMessage());
        $this->error("Failed to import questions from $csvFilePath. Check the logs for more details.");
    }
    $this->info("Successfully Imported $csvFilePath" . "!");
});

