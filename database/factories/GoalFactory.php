<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'status' => $this->faker->randomElement(['not in progress', 'in progress', 'completed']),
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
        ];
    }
}
