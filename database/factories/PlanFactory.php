<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'active' => $this->faker->boolean(),
            'color' => $this->faker->randomElement(['#ffccc7','#ffd8bf','#ffe7ba','#fff1b8','#ffffb8','#f4ffb8','#d9f7be','#b5f5ec','#bae0ff','#d6e4ff','#efdbff','#ffd6e7']),
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
        ];
    }
}
