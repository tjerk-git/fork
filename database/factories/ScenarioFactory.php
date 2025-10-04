<?php

// create a Scenario Factory
namespace Database\Factories;

use App\Models\Scenario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScenarioFactory extends Factory
{
    protected $model = Scenario::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'user_id' => \App\Models\User::factory(),
            'attachment' => null,
            'slug' => $this->faker->unique()->slug(),
            'access_code' => null,
            'is_public' => $this->faker->boolean(),
        ];
    }
}