<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prize>
 */
class PrizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category=$this->faker->randomElement(['task', 'goal', 'habit', 'plan']);
        if($category=="task"){
            $fk_task=$this->faker->randomElement(\App\Models\Task::pluck('id'));
        }elseif($category=="goal"){
            $fk_goal=$this->faker->randomElement(\App\Models\Goal::pluck('id'));
        }elseif($category=="habit"){
            $fk_habit=$this->faker->randomElement(\App\Models\Habit::pluck('id'));
        }

        return [
            'title' => $this->faker->word,
            'category' => $category,
            'fk_task' => $fk_task ?? null,
            'fk_goal' => $fk_goal ?? null,
            'fk_habit' => $fk_habit ?? null,
            'fk_plan' => $this->faker->randomElement(\App\Models\Plan::pluck('id')),
        ];
    }
}
