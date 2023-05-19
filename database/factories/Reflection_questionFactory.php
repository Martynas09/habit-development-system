<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reflection_question>
 */
class Reflection_questionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $sequence = 0;
    private $questions = [
        'Ar jums nuolat pavyksta susirasti laiko savo tikslams kiekvieną dieną / savaitę?',
        'Ar manote, kad darote pažangą siekdami savo tikslų?',
        'Ar galite palaikyti nuoseklią kasdienę rutiną ar tvarkaraštį?',
        'Ar galite efektyviai tvarkyti laiką, kad subalansuotumėte darbą, asmeninį gyvenimą ir tikslų siekimą?',
        'Ar pavyko įveikti kokių nors iššūkių ar kliūčių, kurios kiltų siekiant savo tikslų?'
    ];
    public function definition()
    {
        return [
            'number' => ++$this->sequence,
            'content' => $this->questions[$this->sequence - 1],
            'required' => $this->faker->boolean(),
        ];
    }
}
