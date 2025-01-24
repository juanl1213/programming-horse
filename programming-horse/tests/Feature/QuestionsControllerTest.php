<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_redirects_non_admin_user()
    {
        $user = User::create(['user_role' => 'User']);

        $response = $this->actingAs($user)->get(route('questions.index'));

        $response->assertRedirect(route('dashboard'))
                 ->assertSessionHas('error', 'Unauthorized access.');
    }

    public function test_index_displays_for_admin_user()
    {
        $admin = User::create(['user_role' => 'Admin']);

        $response = $this->actingAs($admin)->get(route('questions.index'));

        $response->assertOk()
                 ->assertViewIs('admin.questionstable');
    }

    public function test_store_creates_question_for_admin()
    {
        $admin = User::create(['user_role' => 'Admin']);
        $questionData = [
            'language' => 'Python',
            'topic_id' => 1,
            'question' => 'What is Python?',
            'correct_answer' => 'A programming language',
            'incorrect_1' => 'A snake',
            'incorrect_2' => 'A fruit',
            'incorrect_3' => 'A car',
            'validated' => true,
        ];

        $response = $this->actingAs($admin)->post(route('questions.store'), $questionData);

        $response->assertRedirect(route('questions.filter'))
                 ->assertSessionHas('success', 'Question added successfully.');

        $this->assertDatabaseHas('questions', $questionData);
    }

    public function test_store_redirects_non_admin_user()
    {
        $user = User::create(['user_role' => 'User']);

        $response = $this->actingAs($user)->post(route('questions.store'), []);

        $response->assertRedirect(route('dashboard'))
                 ->assertSessionHas('error', 'Unauthorized access.');
    }

    public function test_update_modifies_question_for_admin()
    {
        $admin = User::create(['user_role' => 'Admin']);
        $question = Question::factory()->create();

        $updatedData = [
            'language' => 'Java',
            'question' => 'What is Java?',
            'correct_answer' => 'A programming language',
            'incorrect_1' => 'A coffee',
            'incorrect_2' => 'A place',
            'incorrect_3' => 'A plant',
            'validated' => true,
        ];

        $response = $this->actingAs($admin)->put(route('questions.update', $question->question_id), $updatedData);

        $response->assertRedirect(route('questions.filter'))
                 ->assertSessionHas('success', 'Question updated successfully.');

        $this->assertDatabaseHas('questions', array_merge(['question_id' => $question->question_id], $updatedData));
    }

    public function test_update_redirects_non_admin_user()
    {
        $user = User::create(['user_role' => 'User']);
        $question = Question::factory()->create();

        $response = $this->actingAs($user)->put(route('questions.update', $question->question_id), []);

        $response->assertRedirect(route('dashboard'))
                 ->assertSessionHas('error', 'Unauthorized access.');
    }

    public function test_destroy_deletes_question_for_admin()
    {
        $admin = User::create(['user_role' => 'Admin']);
        $question = Question::factory()->create();

        $response = $this->actingAs($admin)->delete(route('questions.destroy', $question->question_id));

        $response->assertRedirect(route('questions.index'))
                 ->assertSessionHas('success', 'Question deleted successfully.');

        $this->assertDatabaseMissing('questions', ['question_id' => $question->question_id]);
    }

    public function test_destroy_redirects_non_admin_user()
    {
        $user = User::create(['user_role' => 'User']);
        $question = Question::factory()->create();

        $response = $this->actingAs($user)->delete(route('questions.destroy', $question->question_id));

        $response->assertRedirect(route('dashboard'))
                 ->assertSessionHas('error', 'Unauthorized access.');
    }

    public function test_filter_returns_filtered_questions()
    {
        $admin = User::create(['user_role' => 'Admin']);
        Question::factory()->create(['topic_id' => 1, 'language' => 'Python']);
        Question::factory()->create(['topic_id' => 2, 'language' => 'Java']);

        $response = $this->actingAs($admin)->post(route('questions.filter'), [
            'topic_id' => 1,
            'language' => 'Python',
        ]);

        $response->assertOk()
                 ->assertViewIs('admin.questionstable')
                 ->assertViewHas('questions', function ($questions) {
                     return $questions->count() === 1 && $questions->first()->language === 'Python';
                 });
    }
}
