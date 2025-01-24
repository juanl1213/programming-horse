<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Question;
use App\Models\Round;
use App\Models\StudyGuide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class RoundControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_round_and_redirects_to_playgame()
    {
        $user = User::create();
        $game = Game::factory()->create(['user_id' => $user->id]);
        $question = Question::factory()->create();

        Session::put('game_id', $game->game_id);

        $response = $this->actingAs($user)->post('/rounds/store', [
            'game_id' => $game->game_id,
            'round_num' => 1,
            'question_id' => $question->question_id,
            'selection' => $question->correct_answer,
        ]);

        $response->assertRedirect('/playgame');
        $this->assertDatabaseHas('rounds', [
            'game_id' => $game->game_id,
            'question_id' => $question->question_id,
            'answer_selected' => $question->correct_answer,
            'round_winner' => 'USER',
            'is_correct' => 'yes',
        ]);

        $this->assertEquals(Session::get('correct_answers'), 1);
    }

    public function test_store_handles_incorrect_answers()
    {
        $user = User::create();
        $game = Game::factory()->create(['user_id' => $user->id]);
        $question = Question::factory()->create(['correct_answer' => 'Correct']);

        Session::put('game_id', $game->game_id);

        $response = $this->actingAs($user)->post('/rounds/store', [
            'game_id' => $game->game_id,
            'round_num' => 1,
            'question_id' => $question->question_id,
            'selection' => 'Incorrect Answer',
        ]);

        $response->assertRedirect('/playgame');
        $this->assertDatabaseHas('rounds', [
            'game_id' => $game->game_id,
            'round_winner' => 'COM',
            'is_correct' => 'no',
        ]);

        $this->assertContains($question->question, Session::get('incorrect_questions'));
    }

    public function test_next_round_loads_new_question()
    {
        $user = User::create();
        $game = Game::factory()->create(['user_id' => $user->id]);
        $question = Question::factory()->create();

        Session::put('game_id', $game->game_id);
        Session::put('topic_id', $question->topic_id);
        Session::put('programming_language', $question->language);

        $response = $this->actingAs($user)->post('/rounds/next-round');

        $response->assertRedirect('/playgame');
        $this->assertNotNull(Session::get('question'));
    }

    public function test_get_rounds_by_game_returns_correct_data()
    {
        $game = Game::factory()->create();
        Round::factory()->create(['game_id' => $game->game_id]);

        $response = $this->getJson("/rounds/game/{$game->game_id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }

    public function test_update_round_winner()
    {
        $round = Round::create(['round_winner' => 'none']);

        $response = $this->patch("/rounds/update/{$round->id}", [
            'round_winner' => 'user',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('rounds', [
            'id' => $round->id,
            'round_winner' => 'user',
        ]);
    }

    public function test_store_fails_with_invalid_data()
    {
        $user = User::create();

        $response = $this->actingAs($user)->post('/rounds/store', []);

        $response->assertSessionHasErrors(['game_id', 'round_num', 'question_id', 'selection']);
    }

    public function test_update_round_validation()
    {
        $round = Round::factory()->create();

        $response = $this->patch("/rounds/update/{$round->id}", [
            'round_winner' => 'invalid',
        ]);

        $response->assertSessionHasErrors('round_winner');
    }
}
