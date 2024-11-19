<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StepController extends Controller
{
    public function create()
    {
        // grab the scenario from the request
        $scenario = Scenario::find(request('scenario'));

        return view('steps.create', compact('scenario'));
    }

    // delete the step
    public function destroy(Scenario $scenario, Step $step, Request $request)
    {
        // delete the step
        $step->delete();

        // redirect to the scenario
        return redirect()->route('scenarios.show', $scenario->id);
    }

    

    // update the step
    public function update(Request $request, Scenario $scenario, Step $step)
    {

        $validatedData = $request->validate([
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv|max:204800', // 200MB max, allow images and video files
        ]);
        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('scenario-attachments', 'public');
        }

        // update using the validated data remove empty fields
        $step->update(array_filter($validatedData));

        // redirect to the scenario
        return redirect()->route('steps.edit', [$scenario->id, $step->id]);
    }

    // store the step
    public function store(Request $request)
    {

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('scenario-attachments', 'public');
        }

        // create the step
        $step = Step::create([
            'order' => 0,
            'description' => $request->description,
            'fork_to_step' => $request->fork_to_step,
            'scenario_id' => $request->scenario_id,
            'attachment' => $request->attachment,
            'open_question' => $request->open_question,
            'question_type' => $request->question_type,
            'multiple_choice_question' => $request->multiple_choice_question,
            'multiple_choice_option_1' => $request->multiple_choice_option_1,
            'multiple_choice_option_2' => $request->multiple_choice_option_2,
            'multiple_choice_option_3' => $request->multiple_choice_option_3,
        ]);

        // redirect to the scenario
        return redirect()->route('scenarios.show', $request->scenario_id);
    }

    public function edit(Scenario $scenario, Step $step)
    {
        return view('steps.edit', compact('scenario', 'step'));
    }
}