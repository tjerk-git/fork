<?php

namespace Tests\Feature;

use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_view_scenarios_index(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('scenarios.index'));

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.index');
    }

    public function test_guest_cannot_view_scenarios_index(): void
    {
        $response = $this->get(route('scenarios.index'));

        $response->assertRedirect(route('login.show'));
    }

    public function test_user_can_create_scenario(): void
    {
        $this->actingAs($this->user);

        $scenarioData = [
            'name' => 'Test Scenario',
            'description' => 'This is a test scenario',
            'is_public' => true,
            'access_code' => 'TEST123',
        ];

        $response = $this->post(route('scenarios.store'), $scenarioData);

        $response->assertRedirect();
        $this->assertDatabaseHas('scenarios', [
            'name' => 'Test Scenario',
            'description' => 'This is a test scenario',
            'user_id' => $this->user->id,
        ]);

        // Check that intro step was created
        $scenario = Scenario::where('name', 'Test Scenario')->first();
        $this->assertDatabaseHas('steps', [
            'scenario_id' => $scenario->id,
            'question_type' => 'intro',
            'order' => 0,
        ]);
    }

    public function test_user_can_view_scenario(): void
    {
        $this->actingAs($this->user);

        $scenario = Scenario::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('scenarios.show', $scenario));

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.show');
        $response->assertViewHas('scenario', $scenario);
    }

    public function test_user_can_edit_scenario(): void
    {
        $this->actingAs($this->user);

        $scenario = Scenario::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('scenarios.edit', $scenario));

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.edit');
    }

    public function test_user_can_update_scenario(): void
    {
        $this->actingAs($this->user);

        $scenario = Scenario::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'name' => 'Updated Scenario Name',
            'description' => 'Updated description',
            'is_public' => false,
            'access_code' => 'NEW123',
        ];

        $response = $this->put(route('scenarios.update', $scenario), $updateData);

        $response->assertRedirect(route('scenarios.show', $scenario));
        $this->assertDatabaseHas('scenarios', [
            'id' => $scenario->id,
            'name' => 'Updated Scenario Name',
            'description' => 'Updated description',
        ]);
    }

    public function test_user_can_delete_scenario(): void
    {
        $this->actingAs($this->user);

        $scenario = Scenario::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('scenarios.destroy', $scenario));

        $response->assertRedirect(route('scenarios.index'));
        $this->assertDatabaseMissing('scenarios', [
            'id' => $scenario->id,
        ]);
    }

    public function test_user_can_toggle_scenario_visibility(): void
    {
        $this->actingAs($this->user);

        $scenario = Scenario::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => false,
        ]);

        $response = $this->put(route('scenarios.toggle-visibility', $scenario));

        $response->assertRedirect();
        $this->assertDatabaseHas('scenarios', [
            'id' => $scenario->id,
            'is_public' => true,
        ]);
    }

    public function test_public_scenario_can_be_viewed_by_slug(): void
    {
        $scenario = Scenario::factory()->create([
            'is_public' => true,
            'access_code' => null,
        ]);

        $response = $this->get("/scenarios/start/{$scenario->slug}");

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.publicShow');
    }

    public function test_private_scenario_shows_not_public_page(): void
    {
        $scenario = Scenario::factory()->create([
            'is_public' => false,
        ]);

        $response = $this->get("/scenarios/start/{$scenario->slug}");

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.notPublic');
    }

    public function test_scenario_with_access_code_shows_access_code_page(): void
    {
        $scenario = Scenario::factory()->create([
            'is_public' => true,
            'access_code' => 'SECRET123',
        ]);

        $response = $this->get("/scenarios/start/{$scenario->slug}");

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.accessCode');
    }

    public function test_correct_access_code_grants_access_to_scenario(): void
    {
        $scenario = Scenario::factory()->create([
            'is_public' => true,
            'access_code' => 'SECRET123',
        ]);

        $response = $this->post("/scenarios/start/{$scenario->slug}", [
            'accessCode' => 'SECRET123',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('scenarios.publicShow');
    }

    public function test_incorrect_access_code_redirects_back_with_error(): void
    {
        $scenario = Scenario::factory()->create([
            'is_public' => true,
            'access_code' => 'SECRET123',
        ]);

        $response = $this->post("/scenarios/start/{$scenario->slug}", [
            'accessCode' => 'WRONG',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Verkeerde toegangscode');
    }

    public function test_scenario_requires_name(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('scenarios.store'), [
            'description' => 'Test without name',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_access_code_must_be_minimum_6_characters(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('scenarios.store'), [
            'name' => 'Test Scenario',
            'access_code' => 'ABC', // Too short
        ]);

        $response->assertSessionHasErrors('access_code');
    }
}
