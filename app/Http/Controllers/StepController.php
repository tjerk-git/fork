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
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4|max:204800',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string|max:255'
        ]);

        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/images'), $filename);
            $validatedData['attachment'] = $filename;
        }


        // Handle keywords if it's an open question
        if ($step->question_type === 'open_question') {
            // Delete existing keywords
            $step->keywords()->delete();
            
            // Add new keywords if provided
            if ($request->has('keywords')) {
                foreach ($request->keywords as $word) {
                    if (!empty(trim($word))) {
                        $step->keywords()->create(['word' => $word]);
                    }
                }
            }
        }

        $validatedData['description'] = $request->description;
        $validatedData['fork_to_step'] = $request->fork_to_step;
        $validatedData['fork_condition'] = $request->fork_condition;
        $validatedData['open_question'] = $request->open_question;
        $validatedData['question_type'] = $request->question_type;
        $validatedData['hidden'] = $request->hidden;
        $validatedData['multiple_choice_question'] = $request->multiple_choice_question;
        $validatedData['multiple_choice_option_1'] = $request->multiple_choice_option_1;
        $validatedData['multiple_choice_option_2'] = $request->multiple_choice_option_2;
        $validatedData['multiple_choice_option_3'] = $request->multiple_choice_option_3;

        // update using the validated data remove empty fields
        $step->update(array_filter($validatedData));

        // redirect back with success message
        return redirect()
            ->route('steps.edit', [$scenario->id, $step->id])
            ->with('success', 'Aangepast.')
            ->withErrors($errors ?? [])
            ->withInput();
    }

    // store the step
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4|max:204800',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string|max:255'
        ]);

        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('attachments'), $filename);
            $validatedData['attachment'] = $filename;
        }else{
            $validatedData['attachment'] = null;
        }

        // get the last step order
        $lastStepOrder = Step::where('scenario_id', $request->scenario_id)->max('order');
        $stepOrder = $lastStepOrder + 1;

        // create the step
        $step = Step::create([
            'order' => $stepOrder,
            'description' => $request->description,
            'fork_to_step' => $request->fork_to_step,
            'fork_condition' => $request->fork_condition,
            'scenario_id' => $request->scenario_id,
            'attachment' =>  $validatedData['attachment'],
            'open_question' => $request->open_question,
            'question_type' => $request->question_type,
            'hidden' => 0,
            'multiple_choice_question' => $request->multiple_choice_question,
            'multiple_choice_option_1' => $request->multiple_choice_option_1,
            'multiple_choice_option_2' => $request->multiple_choice_option_2,
            'multiple_choice_option_3' => $request->multiple_choice_option_3,
        ]);

        // Add keywords if it's an open question
        if ($request->has('keywords') && $request->question_type === 'open_question') {
            foreach ($request->keywords as $word) {
                if (!empty(trim($word))) {
                    $step->keywords()->create(['word' => $word]);
                }
            }
        }

        return redirect()->route('scenarios.show', $request->scenario_id);
    }

    public function edit(Scenario $scenario, Step $step)
    {
        return view('steps.edit', compact('scenario', 'step'));
    }

}