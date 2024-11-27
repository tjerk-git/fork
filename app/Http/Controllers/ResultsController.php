<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Step;
use App\Models\Result;
use App\Models\ResultLine;

class ResultsController extends Controller
{
    public function store(Request $request)
    {
        // Create the main result
        $result = Result::create([
            'session' => session()->getId(),
            'ip' => $request->ip(),
            'browser' => $request->userAgent(),
            'scenario_id' => $request->scenario_id,
        ]);

        // Process each answer and create result lines
        foreach ($request->all() as $key => $value) {
            // Skip non-answer fields
            if (!str_starts_with($key, 'answer_')) {
                continue;
            }

            // Get the step ID from the answer key (format: answer_X)
            $stepId = substr($key, 7);

            // only create the result line if the value is not null
            if ($value === null) {
                continue;
            }

            ResultLine::create([
                'result_id' => $result->id,
                'step_id' => $stepId,
                'value' => $value,
                'type' => 'answer'
            ]);
        }

        return redirect()->back()->with('success', 'Antwoorden opgeslagen!');
    }

    public function index()
    {
        
        $scenarios = Scenario::all();
        return view('results.index', compact('scenarios'));
    }

    public function show(Scenario $scenario)
    {
        $scenario->load('steps', 'results');
        return view('results.show', compact('scenario'));
    }

}