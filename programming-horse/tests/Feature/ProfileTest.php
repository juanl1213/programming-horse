<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        // Create test user
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'user_role' => 'User',
        ]);

        // Navigate to profile page
        $response = $this
            ->actingAs($user)
            ->get('/profile');

        // Confirm successful completion of the test (no errors encountered)
        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        // Create test user
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'user_role' => 'User',
        ]);

        // Send a request to update the user profile
        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        // Confirm the session has no errors and redirects to the profile page
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        // Change user name
        $user->user_name = 'Jane Doe';

        // Confirm the user entry reflects the new name
        $this->assertSame('Jane Doe', $user->user_name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        // Create test user
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'user_role' => 'User',
        ]);

        // Send a request to update the user profile
        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        // Confirm the session has no errors and redirects to the profile page
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        // Assert that email verification is unchanged (null)
        $this->assertNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        // Create test user
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'user_role' => 'User',
        ]);

        // Send a request to delete the user account and confirm with a password
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        // Confirm the session has no errors and redirects to the profile page
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        // Assert that the user was not authenticated (no user account)
        $this->assertGuest();
        // Assert that the user entry is now null 
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        // Create test user
        $user = User::create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'user_role' => 'User',
        ]);

        // Send a request to delete the password from the user profile (wrong password confirmation is entered)
        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        // Confirm the session has a user deletion error and redirects to the profile page
        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');
        // Assert that the user entry is not null (deletion unsuccessful)
        $this->assertNotNull($user->fresh());
    }
}