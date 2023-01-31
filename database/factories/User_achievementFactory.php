<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User_achievement>
 */
class User_achievementFactory extends Factory
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
            'fk_achievement' => $this->faker->randomElement(\App\Models\Achievement::pluck('id')),
        ];
    }
}
