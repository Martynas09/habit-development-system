<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reflection_question>
 */
class Reflection_questionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $sequence = 0;
    public function definition()
    {
        return [
            'number' => ++$this->sequence,
            'content' => $this->faker->sentence(3) . '?',
            'required' => $this->faker->boolean(),
        ];
    }
}
