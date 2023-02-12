<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'duration' => $this->faker->randomElement([15, 30, 45, 60, 75, 90, 105, 120]),
            'title' => $this->faker->randomElement(['Bėgiojimas','Knygos skaitymas','Meditacija','Sportas','Mokymasis','Pasivaikščiojimas']),
        ];
    }
}
