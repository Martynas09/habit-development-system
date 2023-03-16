<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenged_user>
 */
class Challenged_userFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
            'fk_challenge' => $this->faker->randomElement(\App\Models\Challenge::pluck('id')),
            'status' => $this->faker->randomElement(['pending','accepted','rejected']),
        ];
    }
}
