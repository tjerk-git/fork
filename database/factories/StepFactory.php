<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Factories\Factory;

class StepFactory extends Factory
{
    protected $model = Step::class;

    public function definition()
    {
        return [
            'scenario_id' => Scenario::factory(),
            'order' => $this->faker->numberBetween(1, 10),
            'question_type' => $this->faker->randomElement(['intro', 'open_question', 'multiple_choice_question', 'tussenstap']),
            'description' => $this->faker->paragraph(),
            'open_question' => null,
            'multiple_choice_question' => null,
            'multiple_choice_option_1' => null,
            'multiple_choice_option_2' => null,
            'multiple_choice_option_3' => null,
            'attachment' => null,
            'fork_to_step' => null,
            'fork_condition' => null,
            'hidden' => false,
        ];
    }

    public function intro()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => 'intro',
                'description' => $this->faker->paragraph(),
            ];
        });
    }

    public function openQuestion()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => 'open_question',
                'open_question' => $this->faker->sentence() . '?',
            ];
        });
    }

    public function multipleChoice()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => 'multiple_choice_question',
                'multiple_choice_question' => $this->faker->sentence() . '?',
                'multiple_choice_option_1' => $this->faker->word(),
                'multiple_choice_option_2' => $this->faker->word(),
                'multiple_choice_option_3' => $this->faker->word(),
            ];
        });
    }

    public function tussenstap()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => 'tussenstap',
                'description' => $this->faker->paragraph(),
            ];
        });
    }
}
