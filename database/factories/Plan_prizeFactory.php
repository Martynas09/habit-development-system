<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan_prize>
 */
class Plan_prizeFactory extends Factory
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
            'fk_prize' => $this->faker->randomElement(\App\Models\Prize::pluck('id')),
        ];
    }
}
