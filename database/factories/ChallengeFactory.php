<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->randomElement(['Šaltas dušas','Bėgiojimas','Meditacija','Knygos skaitymas']),
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(10, 60),
            'xpGiven' => $this->faker->numberBetween(1, 10),
            'timesPerWeek' => $this->faker->numberBetween(1, 7),
            'type' => $this->faker->randomElement(['public','private']),
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
        ];
    }
}
