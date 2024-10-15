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
            'description' => $this->faker->paragraph(),
            'attachment' => $this->faker->imageUrl(),
            'slug' => $this->faker->slug(),
            'access_code' => $this->faker->word(),
            'is_public' => $this->faker->boolean(),
        ];
    }
}