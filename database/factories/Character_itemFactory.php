<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character_item>
 */
class Character_itemFactory extends Factory
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
            'picture' => 'https://source.unsplash.com/random/300x300',
            'rarity' => $this->faker->randomElement(['common', 'rare', 'uncommon', 'very rare']),
            'fk_level' => $this->faker->randomElement(\App\Models\Level::pluck('id')),
        ];
    }
}
