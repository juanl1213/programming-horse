<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\StudyGuide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class StudyGuideControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_study_guide_with_relations_returns_correct_data()
    {
        $user = User::create();
        $game = Game::factory()->create(['user_id' => $user->id]);
        $studyGuide = StudyGuide::factory()->create(['game_id' => $game->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson(route('study-guide.show', $studyGuide->id));

        $response->assertOk()
                 ->assertJsonStructure([
                     'study_guide' => ['id', 'game_id', 'user_id', 'topic_id', 'language', 'score'],
                     'game' => ['id', 'language', 'topic_id', 'user_id'],
                     'user' => ['id', 'user_name', 'email'],
                     'topic' => [], // Assuming topic relationship exists
                     'language',
                 ]);
    }

    public function test_show_study_guides_displays_performance_data()
    {
        $user = User::create(['user_role' => 'Admin']);
        StudyGuide::factory()->create([
            'topic_id' => 1,
            'language' => 'Python',
            'score' => 80,
        ]);

        $response = $this->actingAs($user)->get(route('studyguides.index'));

        $response->assertOk()
                 ->assertViewIs('studyguides')
                 ->assertViewHas('performanceData')
                 ->assertSee('Data Types') // Assuming topic names are displayed
                 ->assertSee('Python'); // Assuming language names are displayed
    }

    public function test_show_study_guides_logs_average_data()
    {
        Log::shouldReceive('info')->once()->with('Averages:', \Mockery::type('array'));

        $user = User::create(['user_role' => 'Admin']);
        StudyGuide::factory()->create(['topic_id' => 1, 'language' => 'Java', 'score' => 85]);

        $this->actingAs($user)->get(route('studyguides.index'));
    }

    public function test_get_games_by_topic_and_language_returns_filtered_games()
    {
        $game = Game::factory()->create(['topic_id' => 1, 'language' => 'Python']);

        $response = $this->postJson(route('games.by_topic_language'), [
            'topic_id' => 1,
            'language' => 'Python',
        ]);

        $response->assertOk()
                 ->assertJsonStructure([
                     '*' => ['id', 'language', 'topic_id', 'user_id'],
                 ]);
    }

    public function test_get_games_by_topic_and_language_validates_input()
    {
        $response = $this->postJson(route('games.by_topic_language'), [
            'topic_id' => 999, // Non-existent topic
            'language' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['topic_id', 'language']);
    }
}
