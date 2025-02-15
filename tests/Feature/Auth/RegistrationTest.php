<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Character_item;
use App\Models\Level;

class RegistrationTest extends TestCase
{
  use RefreshDatabase;

  public function test_registration_screen_can_be_rendered(): void
  {
    $response = $this->get('/register');

    $response->assertStatus(200);
  }

  public function test_new_users_can_register(): void
  {
    Level::factory(5)->create();
    Character_item::factory(4)->create();
    $response = $this->post('/register', [
      'username' => 'Testing',
      'email' => 'testing@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
  }
}
