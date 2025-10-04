<?php

namespace Tests\Feature;

use App\Models\Scenario;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StepTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Scenario $scenario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->scenario = Scenario::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_user_can_view_create_step_page(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('steps.create', ['scenario' => $this->scenario->id]));

        $response->assertStatus(200);
        $response->assertViewIs('steps.create');
    }

    public function test_user_can_create_intro_step(): void
    {
        $this->actingAs($this->user);

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'intro',
            'description' => 'Welcome to the scenario',
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $response->assertRedirect(route('scenarios.show', $this->scenario));
        $this->assertDatabaseHas('steps', [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'intro',
            'description' => 'Welcome to the scenario',
        ]);
    }

    public function test_user_can_create_open_question_step(): void
    {
        $this->actingAs($this->user);

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'What is your favorite color?',
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $response->assertRedirect(route('scenarios.show', $this->scenario));
        $this->assertDatabaseHas('steps', [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'What is your favorite color?',
        ]);
    }

    public function test_user_can_create_multiple_choice_step(): void
    {
        $this->actingAs($this->user);

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'multiple_choice_question',
            'multiple_choice_question' => 'Choose your answer',
            'multiple_choice_option_1' => 'Option A',
            'multiple_choice_option_2' => 'Option B',
            'multiple_choice_option_3' => 'Option C',
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $response->assertRedirect(route('scenarios.show', $this->scenario));
        $this->assertDatabaseHas('steps', [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'multiple_choice_question',
            'multiple_choice_question' => 'Choose your answer',
            'multiple_choice_option_1' => 'Option A',
        ]);
    }

    public function test_user_can_create_tussenstap(): void
    {
        $this->actingAs($this->user);

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'tussenstap',
            'description' => 'Intermediate step description',
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $response->assertRedirect(route('scenarios.show', $this->scenario));
        $this->assertDatabaseHas('steps', [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'tussenstap',
            'description' => 'Intermediate step description',
        ]);
    }

    public function test_user_can_view_edit_step_page(): void
    {
        $this->actingAs($this->user);

        $step = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
        ]);

        $response = $this->get(route('steps.edit', [
            'scenario' => $this->scenario,
            'step' => $step,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('steps.edit');
        $response->assertViewHas('step', $step);
    }

    public function test_user_can_update_step(): void
    {
        $this->actingAs($this->user);

        $step = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'Original question',
        ]);

        $updateData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'Updated question',
        ];

        $response = $this->put(route('steps.update', [
            'scenario' => $this->scenario,
            'step' => $step,
        ]), $updateData);

        $response->assertRedirect(route('steps.edit', [
            'scenario' => $this->scenario,
            'step' => $step,
        ]));

        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'open_question' => 'Updated question',
        ]);
    }

    public function test_user_can_delete_step(): void
    {
        $this->actingAs($this->user);

        $step = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        $response = $this->delete(route('steps.destroy', [
            'scenario' => $this->scenario,
            'step' => $step,
        ]));

        $response->assertRedirect(route('scenarios.show', $this->scenario));
        $this->assertDatabaseMissing('steps', [
            'id' => $step->id,
        ]);
    }

    public function test_step_order_is_incremented_automatically(): void
    {
        $this->actingAs($this->user);

        // Create first step (order should be 1)
        $step1Data = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'intro',
            'description' => 'Step 1',
        ];
        $this->post(route('steps.store', $this->scenario), $step1Data);

        // Create second step (order should be 2)
        $step2Data = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'Step 2',
        ];
        $this->post(route('steps.store', $this->scenario), $step2Data);

        $steps = Step::where('scenario_id', $this->scenario->id)
            ->orderBy('order')
            ->get();

        $this->assertEquals(1, $steps[0]->order);
        $this->assertEquals(2, $steps[1]->order);
    }

    public function test_user_can_update_step_order(): void
    {
        $this->actingAs($this->user);

        $step1 = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'order' => 1,
        ]);

        $step2 = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'order' => 2,
        ]);

        $response = $this->post("/scenario/{$this->scenario->id}/update-step-order", [
            'steps' => [$step2->id, $step1->id],
        ]);

        $response->assertJson(['success' => true]);

        $step1->refresh();
        $step2->refresh();

        $this->assertEquals(1, $step2->order);
        $this->assertEquals(2, $step1->order);
    }

    public function test_step_can_have_fork_condition(): void
    {
        $this->actingAs($this->user);

        $targetStep = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'multiple_choice_question',
            'multiple_choice_question' => 'Choose wisely',
            'multiple_choice_option_1' => 'Yes',
            'multiple_choice_option_2' => 'No',
            'fork_condition' => '1',
            'fork_to_step' => $targetStep->id,
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $this->assertDatabaseHas('steps', [
            'scenario_id' => $this->scenario->id,
            'fork_condition' => '1',
            'fork_to_step' => $targetStep->id,
        ]);
    }

    public function test_step_can_upload_attachment(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user);

        $file = UploadedFile::fake()->image('test.jpg');

        $stepData = [
            'scenario_id' => $this->scenario->id,
            'question_type' => 'intro',
            'description' => 'Step with attachment',
            'attachment' => $file,
        ];

        $response = $this->post(route('steps.store', $this->scenario), $stepData);

        $response->assertRedirect(route('scenarios.show', $this->scenario));

        $step = Step::where('scenario_id', $this->scenario->id)
            ->where('description', 'Step with attachment')
            ->first();

        $this->assertNotNull($step->attachment);
    }
}
