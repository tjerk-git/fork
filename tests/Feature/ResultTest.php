<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\ResultLine;
use App\Models\Scenario;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Scenario $scenario;
    protected Step $step;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->scenario = Scenario::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
        ]);
        $this->step = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'question_type' => 'open_question',
            'open_question' => 'What is your name?',
        ]);
    }

    public function test_user_can_submit_results(): void
    {
        $resultData = [
            'scenario_id' => $this->scenario->id,
            'answer_' . $this->step->id => 'John Doe',
        ];

        $response = $this->post(route('results.store'), $resultData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('results', [
            'scenario_id' => $this->scenario->id,
        ]);

        $this->assertDatabaseHas('result_lines', [
            'step_id' => $this->step->id,
            'value' => 'John Doe',
            'type' => 'answer',
        ]);
    }

    public function test_user_can_submit_results_with_email(): void
    {
        Mail::fake();

        $resultData = [
            'scenario_id' => $this->scenario->id,
            'email' => 'test@example.com',
            'answer_' . $this->step->id => 'Jane Doe',
        ];

        $response = $this->post(route('results.store'), $resultData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Antwoorden opgeslagen en verstuurd naar je e-mailadres!');

        $this->assertDatabaseHas('results', [
            'scenario_id' => $this->scenario->id,
            'email' => 'test@example.com',
        ]);

        Mail::assertSent(\App\Mail\ResultsMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_email_validation_fails_with_invalid_email(): void
    {
        $resultData = [
            'scenario_id' => $this->scenario->id,
            'email' => 'invalid-email',
            'answer_' . $this->step->id => 'Test Answer',
        ];

        $response = $this->post(route('results.store'), $resultData);

        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_view_results_index(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('results.index'));

        $response->assertStatus(200);
        $response->assertViewIs('results.index');
    }

    public function test_authenticated_user_can_view_scenario_results(): void
    {
        $this->actingAs($this->user);

        Result::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        $response = $this->get(route('results.show', $this->scenario));

        $response->assertStatus(200);
        $response->assertViewIs('results.show');
        $response->assertViewHas('scenario');
    }

    public function test_authenticated_user_can_download_csv_results(): void
    {
        $this->actingAs($this->user);

        $result = Result::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        ResultLine::factory()->create([
            'result_id' => $result->id,
            'step_id' => $this->step->id,
            'value' => 'CSV Test Answer',
            'type' => 'answer',
        ]);

        $response = $this->get(route('results.csv', $this->scenario));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->scenario->name . '_results.csv"');
    }

    public function test_result_stores_session_and_ip(): void
    {
        $resultData = [
            'scenario_id' => $this->scenario->id,
            'answer_' . $this->step->id => 'Test Answer',
        ];

        $response = $this->post(route('results.store'), $resultData);

        $result = Result::latest()->first();

        $this->assertNotNull($result->session);
        $this->assertNotNull($result->ip);
        $this->assertNotNull($result->browser);
    }

    public function test_multiple_answers_are_stored_correctly(): void
    {
        $step2 = Step::factory()->create([
            'scenario_id' => $this->scenario->id,
            'question_type' => 'multiple_choice_question',
            'multiple_choice_question' => 'Choose one',
        ]);

        $resultData = [
            'scenario_id' => $this->scenario->id,
            'answer_' . $this->step->id => 'Answer 1',
            'answer_' . $step2->id => 'Option A',
        ];

        $response = $this->post(route('results.store'), $resultData);

        $result = Result::latest()->first();

        $this->assertEquals(2, $result->lines()->count());

        $this->assertDatabaseHas('result_lines', [
            'result_id' => $result->id,
            'step_id' => $this->step->id,
            'value' => 'Answer 1',
        ]);

        $this->assertDatabaseHas('result_lines', [
            'result_id' => $result->id,
            'step_id' => $step2->id,
            'value' => 'Option A',
        ]);
    }

    public function test_null_answers_are_not_stored(): void
    {
        $resultData = [
            'scenario_id' => $this->scenario->id,
            'answer_' . $this->step->id => null,
        ];

        $response = $this->post(route('results.store'), $resultData);

        $result = Result::latest()->first();

        $this->assertEquals(0, $result->lines()->count());
    }

    public function test_result_relationship_with_scenario(): void
    {
        $result = Result::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        $this->assertEquals($this->scenario->id, $result->scenario->id);
        $this->assertTrue($this->scenario->results->contains($result));
    }

    public function test_result_line_relationship_with_step(): void
    {
        $result = Result::factory()->create([
            'scenario_id' => $this->scenario->id,
        ]);

        $resultLine = ResultLine::factory()->create([
            'result_id' => $result->id,
            'step_id' => $this->step->id,
        ]);

        $this->assertEquals($this->step->id, $resultLine->step->id);
        $this->assertEquals($result->id, $resultLine->result->id);
    }
}
