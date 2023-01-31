<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reflection_answer>
 */
class Reflection_answerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'answer' => $this->faker->boolean(),
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
            'fk_question' => $this->faker->randomElement(\App\Models\Reflection_question::pluck('id')),
        ];
    }
}
