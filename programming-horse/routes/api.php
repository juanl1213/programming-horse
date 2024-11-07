<?php

use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StudyGuideController;


Route::post('/chat-completion', [OpenAIController::class, 'generateChatCompletion']);
Route::post('/finish-game', [GameController::class, 'finishGame']);
Route::get('/study-guides/{id}', [StudyGuideController::class, 'getStudyGuideWithRelations']);
Route::post('/games/{id}/generate-study-guide', [OpenAIController::class, 'generateStudyGuide']);
