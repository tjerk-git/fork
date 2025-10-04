<?php

namespace Database\Factories;

use App\Models\Result;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function definition()
    {
        return [
            'scenario_id' => Scenario::factory(),
            'session' => $this->faker->uuid(),
            'ip' => $this->faker->ipv4(),
            'browser' => $this->faker->userAgent(),
            'email' => $this->faker->optional()->email(),
        ];
    }
}
