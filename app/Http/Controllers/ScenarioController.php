<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScenarioController extends Controller
{
    public function index()
    {
        $scenarios = Scenario::with('user')->paginate(10);
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
            'description' => 'required',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv|max:204800', // 200MB max, allow images and video files
            'is_public' => 'boolean',
            'access_code' => 'nullable|string|min:6|max:20',
        ]);



        $validatedData['user_id'] = auth()->id();
        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('scenario-attachments', 'public');
        }

        $scenario = Scenario::create($validatedData);

        return redirect()->route('scenarios.show', $scenario)->with('success', 'Scenario created successfully');
    }

    public function edit(Scenario $scenario)
    {

        return view('scenarios.edit', compact('scenario'));
    }

    public function update(Request $request, Scenario $scenario)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv|max:204800', // 200MB max, allow images and video files
            'is_public' => 'boolean',
            'access_code' => 'nullable|string|min:6|max:20',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('scenario-attachments', 'public');
        }

        $scenario->update($validatedData);

        return redirect()->route('scenarios.show', $scenario)->with('success', 'Scenario updated successfully');
    }

    public function destroy(Scenario $scenario)
    {
        $this->authorize('delete', $scenario);
        $scenario->delete();
        return redirect()->route('scenarios.index')->with('success', 'Scenario deleted successfully');
    }
}