<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      $level=1;
      $xp=fake()->numberBetween(0, 500);
      if ($xp < 100) {
        $level= 1;
      }
      if ($xp >= 100 && $xp < 200) {
        $level= 2;
      }
      if ($xp >= 200 && $xp < 300) {
        $level= 3;
      }
      if ($xp >= 300 && $xp < 400) {
        $level= 4;
      }
      if ($xp >= 400) {
        $level= 5;
      }
        return [
            'username' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'xp' => $xp,
            'level' => $level,
            'avatar' => 'head1.png',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
