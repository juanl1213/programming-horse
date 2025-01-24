<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Question;
use App\Models\Round;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_game_creates_game_and_redirects_to_playgame(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post('/games/start', [
            'programming_language' => 'Python',
            'topic_id' => 1,
        ]);

        $response->assertSessionHasNoErrors()->assertViewIs('playgame');

        $game = Game::first();
        $this->assertSame('Python', $game->language);
        $this->assertSame(1, $game->topic_id);
        $this->assertSame($user->id, $game->user_id);

        $this->assertTrue(Session::has('game_id'));
        $this->assertTrue(Session::has('question'));
    }

    public function test_start_game_requires_valid_data(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post('/games/start', [
            'programming_language' => 'InvalidLang',
            'topic_id' => 999,
        ]);

        $response->assertSessionHasErrors(['programming_language', 'topic_id']);
    }

    public function test_load_new_question_returns_question_data(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);
        $game = Game::create(['user_id' => $user->id, 'language' => 'Python', 'topic_id' => 1]);
        $question = Question::create([
            'language' => 'Python',
            'question' => 'What is 1+2',
            'topic_id' => '2',
            'correct_answer' => '3',
            'incorrect_1' => '4',
            'incorrect_2' => '5',
            'incorrect_3' => '6',
            'validated' => '0',
        ]);
        $response = $this->actingAs($user)->get("/games/{$game->id}/question/{$question->topic_id}/{$question->language}");

        $response->assertStatus(200)->assertJson($question->toArray());
    }

    public function test_load_new_question_returns_404_if_no_question(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);
        $game = Game::create(['user_id' => $user->id, 'language' => 'Python', 'topic_id' => 1]);

        $response = $this->actingAs($user)->get("/games/{$game->id}/question/999/Python");

        $response->assertStatus(404);
    }

    public function test_next_round_redirects_to_playgame_with_next_question(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);
        $game = Game::create(['user_id' => $user->id, 'language' => 'Python', 'topic_id' => 1]);
        $question = Question::create([
            'language' => 'Python',
            'question' => 'What is 1+2',
            'topic_id' => '2',
            'correct_answer' => '3',
            'incorrect_1' => '4',
            'incorrect_2' => '5',
            'incorrect_3' => '6',
            'validated' => '0',
        ]);
        Session::put('game_id', $game->id);
        Session::put('topic_id', $question->topic_id);
        Session::put('programming_language', $question->language);

        $response = $this->actingAs($user)->post('/games/next-round');

        $response->assertRedirect('/playgame');
        $this->assertNotNull(Session::get('question'));
    }

    public function test_submit_answer_stores_round_and_redirects(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);
        $game = Game::create(['user_id' => $user->id, 'language' => 'Python', 'topic_id' => 1]);
        $question = Question::create([
            'language' => 'Python',
            'topic_id' => '2',
            'question' => 'What is 1+2',
            'correct_answer' => '3',
            'incorrect_1' => '4',
            'incorrect_2' => '5',
            'incorrect_3' => '6',
            'validated' => '0',
        ]);

        $response = $this->actingAs($user)->post('/games/submit-answer', [
            'game_id' => $game->id,
            'round_num' => 1,
            'question_id' => $question->id,
            'answer_selected' => 'Answer A',
        ]);

        $response->assertRedirect('/playgame');

        $this->assertDatabaseHas('rounds', [
            'game_id' => $game->id,
            'question_id' => $question->id,
            'round_winner' => 'USER',
            'is_correct' => true,
        ]);
    }

    public function test_submit_answer_fails_with_invalid_data(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post('/games/submit-answer', []);

        $response->assertSessionHasErrors(['game_id', 'round_num', 'question_id', 'answer_selected']);
    }

    public function test_get_incorrect_answers_returns_json(): void
    {
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'user_role' => 'User',
            'password' => 'password',
        ]);

        $this->actingAs($user);

        $game = Game::create(['user_id' => $user->id, 'language' => 'Python', 'topic_id' => 1]);

        $this->mock(\App\Services\GameService::class, function ($mock) use ($game) {
            $mock->shouldReceive('getIncorrectAnswers')
                ->with($game->id)
                ->andReturn(['Question 1', 'Question 2']);
        });

        $response = $this->getJson("/games/{$game->id}/incorrect-answers/{$user->user_name}");

        $response->assertOk()->assertJson(['Question 1', 'Question 2']);
    }
}