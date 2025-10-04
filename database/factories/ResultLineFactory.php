<?php

namespace Database\Factories;

use App\Models\ResultLine;
use App\Models\Result;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultLineFactory extends Factory
{
    protected $model = ResultLine::class;

    public function definition()
    {
        return [
            'result_id' => Result::factory(),
            'step_id' => Step::factory(),
            'value' => $this->faker->sentence(),
            'type' => 'answer',
        ];
    }
}
