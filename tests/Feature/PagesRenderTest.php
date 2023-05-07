<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesRenderTest extends TestCase
{
  use RefreshDatabase;

  public function test_plans_page_is_displayed(): void
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
      ->get('/plans');

    $response->assertOk();
  }
  public function test_goals_page_is_displayed(): void
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
      ->get('/goals');

    $response->assertOk();
  }
  public function test_schedule_page_is_displayed(): void
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
      ->get('/schedule');

    $response->assertOk();
  }
  public function test_challenges_page_is_displayed(): void
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
      ->get('/challenges');

    $response->assertOk();
  }
  public function test_leaderboard_page_is_displayed(): void
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
      ->get('/leaderboard');

    $response->assertOk();
  }
  public function test_journal_page_is_displayed(): void
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
      ->get('/journal');

    $response->assertOk();
  }
  public function test_report_page_is_displayed(): void
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
      ->get('/report');

    $response->assertOk();
  }
  public function test_profile_page_is_displayed(): void
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
      ->get('/profile');

    $response->assertOk();
  }
}
