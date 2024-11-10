<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Services\GameService;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\QuestionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;

Route::get('/study-guide',[OpenAIController::class, 'createStudyGuide']);

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/selection', function () {
    return view('selection');
})->name('selection');

Route::post('/playgame', [GameController::class, 'startGame'])->name('playgame');

Route::get('/index', function () {
    return view('welcome');
});
// In web.php
Route::get('/playgame/load-new-question/{gameId}/{topicId}/{language}', [GameService::class, 'loadQuestion']);

// routes/web.php
Route::post('/toggle-dark-mode', [DarkModeController::class, 'toggle'])->name('toggle-dark-mode');

Route::get('/rules', function () {
    return view('rules');
})->name('rules');

Route::get('/admin', function () {
    return view('admin.admindatabase');
})->name('admin');

Route::get('/userstable', [ProfileController::class, 'index'])->name('userstable');
Route::put('/users/{id}', [ProfileController::class, 'adminupdate'])->name('users.update');

Route::get('/question', [QuestionsController::class, 'index'])->name('question'); // Show the filtering form
Route::match(['get', 'post'], '/question/filter', [QuestionsController::class, 'filter'])->name('questions.filter');// Route to display the edit form for a specific question
Route::get('/questions/{question_id}/edit', [QuestionsController::class, 'edit'])->name('questions.edit');
// Route to update a question (PUT only)
Route::delete('/questions/{question_id}', [QuestionsController::class, 'destroy'])->name('questions.destroy');

Route::get('/questions/create', [QuestionsController::class, 'create'])->name('questions.create');
Route::post('/questions', [QuestionsController::class, 'store'])->name('questions.store');

Route::put('/questions/update', [QuestionsController::class, 'update'])->name('questions.update');

Route::get('/topicstable', [TopicController::class, 'index'])->name('topicstable');

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

/* Route::prefix('questions')->group(function () {
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
}); */

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
