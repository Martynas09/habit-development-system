<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Users_characterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'head' => $this->faker->randomElement(\App\Models\Character_item::pluck('id')),
            'top' => $this->faker->randomElement(\App\Models\Character_item::pluck('id')),
            'bottom' => $this->faker->randomElement(\App\Models\Character_item::pluck('id')),
            'shoes' => $this->faker->randomElement(\App\Models\Character_item::pluck('id')),
            'fk_user' => $this->faker->randomElement(\App\Models\User::pluck('id')),
        ];
    }
}
