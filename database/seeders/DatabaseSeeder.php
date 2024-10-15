<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scenario;
use App\Models\Step;
use App\Models\Component;
use App\Models\Result;
use App\Models\ResultLine;

use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => Faker::create()->unique()->safeEmail
        // ]);

        // // create a scenario
        // $user = User::first();

        // $scenario = $user->scenarios()->create([
        //     'name' => 'Test Scenario',
        //     'description' => 'This is a test scenario',
        //     'slug' => 'test-scenario',
        //     'access_code' => '123456',
        //     'is_public' => true,
        // ]);

        // // create a component
        // $step = $scenario->steps()->create([
        //     'order' => 1,
        //     'condition' => 'true',
        //     'description' => 'This is a test step',
        //     'fork_to_step' => null,
        // ]);

        // $component = Component::create([
        //     'type' => 'text',
        //     'body' => 'This is a test component',
        //     'required' => true,
        //     'step_id' => $step->id,
        // ]);

        // // create a component option
        // $component->options()->create([
        //     'body' => 'Option 1',
        //     'component_id' => $component->id,
        // ]);

        //         // create a component option
        // $component->options()->create([
        //     'body' => 'Option 2',
        //     'component_id' => $component->id,
        // ]);

        //         // create a component option
        // $component->options()->create([
        //     'body' => 'Option 3',
        //     'component_id' => $component->id,
        // ]);

        $scenario = Scenario::find(1);

        // create a result
        $result = $scenario->results()->create([
            'session' => Faker::create()->uuid,
            'ip' => Faker::create()->ipv4,
            'browser' => Faker::create()->userAgent,
            'started_at' => Faker::create()->dateTime,
            'ended_at' => Faker::create()->dateTime,
            'scenario_id' => 1,
        ]);

        // create a result line
        $result->lines()->create([
            'step_id' => 1,
            'value' => 'This is a test value',
            'type' => 'text',
        ]);

             // create a result line
        $result->lines()->create([
            'step_id' => 2,
            'value' => 'and this is for two',
            'type' => 'text',
        ]);
    
    }
}
