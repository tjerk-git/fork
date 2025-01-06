<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Step;

class ScenarioController extends Controller

{
    public function index()
    {
        //$scenarios = Scenario::where('user_id', auth()->id())->get();
        // get all scenarios
        $scenarios = Scenario::all();
        return view('scenarios.index', compact('scenarios'));
    }

    public function show(Scenario $scenario)
    {
        $scenario->load('steps', 'results');
        return view('scenarios.show', compact('scenario'));
    }

    public function create()
    {
        return view('scenarios.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'is_public' => 'nullable|boolean',
            'access_code' => 'nullable|string|min:6|max:20',
            'ask_for_name' => 'nullable|boolean',
        ]);


        $validatedData['user_id'] = auth()->id();
   
        $validatedData['is_public'] = (bool) ($request->is_public ?? false);
        $validatedData['ask_for_name'] = (bool) ($request->ask_for_name ?? false);

        $scenario = Scenario::create($validatedData);

        // create a first step for this scenario
        $step = new Step();
        $step->question_type ='intro';
        $step->description = 'Dit is de introductie van het scenario';
        $step->order = 0;
        $step->scenario_id = $scenario->id;
        $step->save();

        return redirect()->route('scenarios.show', $scenario)->with('success', 'Scenario created successfully');
    }

    public function edit(Scenario $scenario)
    {

        return view('scenarios.edit', compact('scenario'));
        
    }

    public function updateStepOrder(Request $request, Scenario $scenario)
    {
        $request->validate([
            'steps' => 'required|array'
        ]);

        foreach ($request->input('steps') as $index => $stepId) {
            $step = Step::findOrFail($stepId);
            $step->order = $index + 1;
            $step->save();
        }

        return response()->json(['success' => true]);
    }
    
    public function update(Request $request, Scenario $scenario)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'ask_for_name' => 'nullable|boolean',
            'access_code' => 'nullable|string|min:4|max:20',
        ]);

        $validatedData['is_public'] = (bool) ($request->is_public ?? false);
        $validatedData['ask_for_name'] = (bool) ($request->ask_for_name ?? false);


        $scenario->update($validatedData);

        return redirect()->route('scenarios.show', $scenario)->with('success', 'Scenario updated successfully');
    }

    public function destroy(Scenario $scenario)
    {
        $scenario->delete();
        return redirect()->route('scenarios.index')->with('success', 'Scenario deleted successfully');
    }

    public function showBySlug($slug)
    {
        $scenario = Scenario::whereSlug($slug)->firstOrFail();

        // if not public redirect to notPublic view
        if (!$scenario->is_public) {
            return view('scenarios.notPublic');
        }

        // if this scenario has an access code redirect to access code page
        if ($scenario->access_code && !session()->has('access_code_' . $scenario->id)) {
            return view('scenarios.accessCode', compact('scenario'));
        }else{
            return view('scenarios.publicShow', compact('scenario'));
        }

    }

    public function verifyAccessCode(Request $request, $slug)
    {
        $scenario = Scenario::whereSlug($slug)->firstOrFail();

        $request->validate([
            'accessCode' => 'required|string|min:2|max:20',
        ]);

        if ($scenario->access_code !== $request->accessCode) {
            return redirect()->back()->with('error', 'Verkeerde toegangscode');
        }

        // set session to allow access to this scenario
        session()->put('access_code_' . $scenario->id, true);

        return view('scenarios.publicShow', compact('scenario'));
    }

    public function toggleVisibility(Scenario $scenario)
    {
        $scenario->update([
            'is_public' => !$scenario->is_public
        ]);

        $status = $scenario->is_public ? 'publiek' : 'privé';
        return redirect()->back()->with('success', "Scenario is nu {$status}");
    }
}