<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan_habit>
 */
class Plan_habitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fk_plan' => $this->faker->randomElement(\App\Models\Plan::pluck('id')),
            'fk_habit' => $this->faker->randomElement(\App\Models\Habit::pluck('id')),
        ];
    }
}
