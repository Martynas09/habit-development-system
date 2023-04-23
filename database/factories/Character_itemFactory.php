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
    public $index=0;
    public function definition()
    {

        $picturesArray = [
            'head2.png',
            'head3.png',
            'head4.png',
            'head5.png',
            'head6.png',
            'top2.png',
        ];
        $picture_url=$picturesArray[$this->index];
        if($picture_url=="head2.png" || $picture_url=="head3.png" || $picture_url=="head4.png" || $picture_url=="head5.png" || $picture_url=="head6.png"){
            $category="head";
        }elseif( $picture_url=="top2.png"){
            $category="top";
        }
        $this->index++;
    
        return [
            'title' => $this->faker->word,
            'picture' => $picture_url,
            'rarity' => $this->faker->randomElement(['common', 'rare', 'uncommon', 'very rare']),
            'category' => $category,
            'fk_level' => $this->faker->randomElement(\App\Models\Level::pluck('id')),
        ];
    }
}
