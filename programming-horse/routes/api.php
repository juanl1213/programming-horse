<?php

use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\GameController;


Route::post('/chat-completion', [OpenAIController::class, 'generateChatCompletion']);
Route::post('/finish-game', [GameController::class, 'finishGame']);