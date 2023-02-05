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
        $picture_url=$this->faker->randomElement(["head1.png","head2.png","head3.png","top1.png","top2.png","top3.png","bottom1.png","bottom2.png","bottom3.png","shoes1.png","shoes2.png","shoes3.png"]);
        if($picture_url=="head1.png" || $picture_url=="head2.png" || $picture_url=="head3.png"){
            $category="head";
        }elseif($picture_url=="top1.png" || $picture_url=="top2.png" || $picture_url=="top3.png"){
            $category="top";
        }elseif($picture_url=="bottom1.png" || $picture_url=="bottom2.png" || $picture_url=="bottom3.png"){
            $category="bottom";
        }elseif($picture_url=="shoes1.png" || $picture_url=="shoes2.png" || $picture_url=="shoes3.png"){
            $category="shoes";
        }
    
        return [
            'title' => $this->faker->word,
            'picture' => $picture_url,
            'rarity' => $this->faker->randomElement(['common', 'rare', 'uncommon', 'very rare']),
            'category' => $category,
            'fk_level' => $this->faker->randomElement(\App\Models\Level::pluck('id')),
        ];
    }
}
