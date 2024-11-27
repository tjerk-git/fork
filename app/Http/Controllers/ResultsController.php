<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Step;
use App\Models\Result;
use App\Models\ResultLine;
use League\Csv\Writer;
use SplTempFileObject;


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


public function createCSV(Scenario $scenario)
{
    // Load the scenario with its steps and results with their lines
    $scenario->load(['steps', 'results.lines.step']);
    
    // Create CSV writer
    $csv = Writer::createFromFileObject(new SplTempFileObject());
    $csv->setOutputBOM(Writer::BOM_UTF8);
    
    // Create header row
    $headerRow = ['Timestamp', 'IP Address', 'Vraag', 'Antwoord'];
    $csv->insertOne($headerRow);
    
    // Insert results
    foreach ($scenario->results as $result) {
        foreach ($result->lines as $line) {
            $row = [
                $result->created_at->format('d-m-Y H:i'),
                $result->ip,
                // Get the appropriate question text based on type
                match($line->step->question_type) {
                    'open_question' => strip_tags($line->step->open_question),
                    'multiple_choice_question' => strip_tags($line->step->multiple_choice_question),
                    default => strip_tags($line->step->description)
                },
                strip_tags($line->value)
            ];
            
            $csv->insertOne($row);
        }
    }
    
    // Set headers for download
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => sprintf('attachment; filename="%s_results.csv"', $scenario->name),
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];
    
    return response((string) $csv, 200, $headers);
}
}