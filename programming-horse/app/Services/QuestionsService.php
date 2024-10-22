<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use League\Csv\Reader;
class QuestionsService
{
    public function validateCsv(string $csvFilePath): array
    {
        //Creates array data from csv to import to the database
        try{

            //This Reader function makes it easier to read csv files and loop
            //through them to build our final array
        $csv = Reader::createFromPath($csvFilePath);
        $csv->setHeaderOffset(0);
         $questions = $csv->getRecords();
           
         $data = [];

         foreach($questions as $question)
         {
            $data[] = [
                "language" => $question["language"],
                "topic_id" => $question["topic_id"],
                "question" => $question["question"],
                "correct_answer" => $question["correct_answer"],
                "incorrect_1" => $question["incorrect_1"],
                "incorrect_2" => $question["incorrect_2"],
                "incorrect_3" => $question["incorrect_3"],
                "validated" => $question["validated"],
            ];
         }
        } catch(\Exception $e)
        {
            Log::error($e);
            Log::error($e->getMessage());
        }
        return $data;
    }
    
    public function insertQuestions(array $questions): void
    {
        //inserts the prepared data for questions into the questions table
        try 
        {
            DB::table('questions')->insert($questions);
        } catch(\Exception $e)
        {
            Log::error($e);
            Log::error($e->getMessage());
        }
    }


}
?>