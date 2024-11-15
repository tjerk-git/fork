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
       // find user with email tjerk.dijkstra@icoud.com
         $user = User::where('email', 'tjerk.dijkstra@icloud.com')->first();

         // create a new scenario
        $scenario = Scenario::factory()->create([
            'name' => 'Test scenario',
            'description' => 'This is a test scenario',
            'user_id' => $user->id,
            'is_public' => true,
        ]);

    }
}
