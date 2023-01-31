<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan_task>
 */
class Plan_taskFactory extends Factory
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
            'fk_task' => $this->faker->randomElement(\App\Models\Task::pluck('id')),
            'fk_plan_goal' => $this->faker->randomElement(\App\Models\Plan_goal::pluck('id')),
            'fk_reminder' => $this->faker->randomElement(\App\Models\Reminder::pluck('id')),
            'execution_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'is_done' => $this->faker->boolean(),
        ];
    }
}
