<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reflection_answer>
 */
class Reflection_answerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition()
    // {
    //     return [
    //         'content' => $this->faker->sentence,
    //         'answer' => $this->faker->boolean(),
    //         'fk_question' => $this->faker->randomElement(\App\Models\Reflection_question::pluck('id')),
    //     ];
    // }
    private static $answerCounter = 0;
    private static $currentQuestionId = 1;
    private static $value=1;

    public function definition()
    {
        $content="Taip";
        self::$value=1;
        if(self::$answerCounter==1) {
            self::$value=-1;
            $content="Ne";
        }
        if(self::$answerCounter>1) {
            self::$answerCounter = 0;
            self::$currentQuestionId++;
        }

        self::$answerCounter++;
        return [
            'content' => $content,
            'value' => self::$value,
            'fk_question' =>self::$currentQuestionId,
        ];
    }
}
