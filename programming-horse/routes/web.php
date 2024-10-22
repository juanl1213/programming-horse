<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/topics', function () {
    return view('topics');
})->name('topics');

Route::get('/playgame', function () {
    return view('playgame');
})->name('playgame');

Route::get('/index', function () {
    return view('welcome');
});

// routes/web.php
Route::post('/toggle-dark-mode', [DarkModeController::class, 'toggle'])->name('toggle-dark-mode');

Route::get('/rules', function () {
    return view('rules');
})->name('rules');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('topics')->group(function () {
    // Get all topics
    Route::get('/', [TopicController::class, 'index']);

    // Get a specific topic
    Route::get('/{id}', [TopicController::class, 'show']);

    // Create a new topic
    Route::post('/', [TopicController::class, 'store']);

    // Update an existing topic
    Route::put('/{id}', [TopicController::class, 'update']);

    // Delete a topic
    Route::delete('/{id}', [TopicController::class, 'destroy']);

    // Get questions based on selected topic and programming language
    Route::post('/questions', [TopicController::class, 'getQuestionsByTopicAndLanguage']);
});

Route::prefix('rounds')->group(function () {
    // Create a new round
    Route::post('/submit-answer', [RoundController::class, 'store']);

    // Get all rounds for a specific game
    Route::get('/game/{gameId}', [RoundController::class, 'getRoundsByGame']);

    // Update a round (e.g., to set the round winner)
    Route::put('/{id}', [RoundController::class, 'update']);
});

Route::prefix('questions')->group(function () {
    // Get all questions with their answers
    Route::get('/', [QuestionsController::class, 'index']);

    // Get a specific question with its answers
    Route::get('/{id}', [QuestionsController::class, 'show']);

    // Create a new question with answers
    Route::post('/', [QuestionsController::class, 'store']);

    // Update an existing question with answers
    Route::put('/{id}', [QuestionsController::class, 'update']);

    // Delete a question and its answers
    Route::delete('/{id}', [QuestionsController::class, 'destroy']);

    // Get questions by topic and language
    Route::post('/filter', [QuestionsController::class, 'getQuestionsByTopicAndLanguage']);
});

Route::prefix('games')->group(function () {
    // Get a specific game by ID
    Route::get('/{id}', [GameController::class, 'show']);

    // Create a new game
    Route::post('/', [GameController::class, 'store']);

    // Update an existing game (to continue or update winner)
    Route::put('/{id}', [GameController::class, 'update']);

    // Restart a game
    Route::post('/{id}/restart', [GameController::class, 'restart']);
});

require __DIR__.'/auth.php';
