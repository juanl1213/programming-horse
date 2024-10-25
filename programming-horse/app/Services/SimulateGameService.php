<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use League\Csv\Reader;
class SimulateGameService
{
    public function prepareGameData(string $csvFilePath): array
    {
        try{

            //This Reader function makes it easier to read csv files and loop
            //through them to build our final array
        $csv = Reader::createFromPath($csvFilePath);
        $csv->setHeaderOffset(0);
         $games = $csv->getRecords();
           
         $data = [];

         foreach($games as $game)
         {
            $data[] = [
                'game_id'=> $game["game_id"],
                'user_name'=> $game["user_name"],
                'language'=> $game["language"],
                'topic_id'=> $game["topic_id"],
                'game_state'=> $game["game_state"],
                'game_status'=> $game["game_status"],
                'game_winner'=> $game["game_winner"],
            ];
         }
        } catch(\Exception $e)
        {
            Log::error($e);
            Log::error($e->getMessage());
        }
        return $data;
    }
    public function prepareRoundsData(string $csvFilePath): array
    {
        try{

            //This Reader function makes it easier to read csv files and loop
            //through them to build our final array
        $csv = Reader::createFromPath($csvFilePath);
        $csv->setHeaderOffset(0);
         $rounds = $csv->getRecords();
           
         $data = [];

         foreach($rounds as $rounds)
         {
            $data[] = [
                'game_id'=> $rounds["game_id"],
                'round_num'=> $rounds["round_num"],
                'question_id' => $rounds['question_id'],
                'answer_selected'=> $rounds["answer_selected"],
                'round_winner'=> $rounds["round_winner"],
                'is_correct'=> $rounds["is_correct"],
            ];
         }
        } catch(\Exception $e)
        {
            Log::error($e);
            Log::error($e->getMessage());
        }
        return $data;
    }
    public function insertData(array $data, string $table): void
    {
        //inserts the prepared data for questions into the questions table
        try 
        {
            DB::table($table)->insert($data);
        } catch(\Exception $e)
        {
            Log::error($e);
            Log::error($e->getMessage());
        }
    }
}
?>