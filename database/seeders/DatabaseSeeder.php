<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    //    // find user with email tjerk.dijkstra@icoud.com
    //      $user = User::where('email', 'tjerk.dijkstra@icloud.com')->first();

    //      // create a new scenario
    //     $scenario = Scenario::factory()->create([
    //         'name' => 'Test scenario',
    //         'description' => 'This is a test scenario',
    //         'user_id' => $user->id,
    //         'is_public' => true,
    //     ]);

    // create a test user with email tjerk.dijkstra@icloud.com
        $user = User::create([
            'name' => 'Tjerk Dijkstra',
            'email' => 'tjerk.dijkstra@icloud.com',
        ]);

    }
}
