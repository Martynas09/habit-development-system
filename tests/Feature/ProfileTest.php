<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
  use RefreshDatabase;

  public function test_profile_information_can_be_updated(): void
  {
    $user = User::factory()->create([
      'username' => 'Test User',
      'email' => 'test@test',
      'email_verified_at' => now(),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => 'ZbwWAZpB5n',
      'xp' => 344,
      'avatar' => 'head1.png',
    ]);

    $response = $this
      ->actingAs($user)
      ->patch('/profile', [
        'username' => 'Test User',
        'email' => 'test@example.com',
      ]);

    $response
      ->assertSessionHasNoErrors()
      ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->username);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
  }

  public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
  {
    $user = User::factory()->create();

    $response = $this
      ->actingAs($user)
      ->patch('/profile', [
        'name' => 'Test User',
        'email' => $user->email,
      ]);

    $response
      ->assertSessionHasNoErrors()
      ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
  }

  public function test_user_can_delete_their_account(): void
  {
    $user = User::factory()->create();

    $response = $this
      ->actingAs($user)
      ->delete('/profile', [
        'password' => 'password',
      ]);

    $response
      ->assertSessionHasNoErrors()
      ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
  }

  public function test_correct_password_must_be_provided_to_delete_account(): void
  {
    $user = User::factory()->create();

    $response = $this
      ->actingAs($user)
      ->from('/profile')
      ->delete('/profile', [
        'password' => 'wrong-password',
      ]);

    $response
      ->assertSessionHasErrors('password')
      ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
  }
}
