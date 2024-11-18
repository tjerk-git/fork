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
        ]);


        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('scenario-attachments', 'public');
        }

    
        $scenario->update(array_filter($validatedData));

        return redirect()->route('scenarios.show', $scenario)->with('success', 'Scenario updated successfully');
    }

    public function destroy(Scenario $scenario)
    {
        $this->authorize('delete', $scenario);
        $scenario->delete();
        return redirect()->route('scenarios.index')->with('success', 'Scenario deleted successfully');
    }

    public function showBySlug($slug)
    {
        $scenario = Scenario::whereSlug($slug)->firstOrFail();

        // if not public redirect to 404
        if (!$scenario->is_public) {
            abort(404);
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
            'accessCode' => 'required|string|min:6|max:20',
        ]);

        if ($scenario->access_code !== $request->accessCode) {
            return redirect()->back()->with('error', 'Verkeerde toegangscode');
        }

        // set session to allow access to this scenario
        session()->put('access_code_' . $scenario->id, true);

        return view('scenarios.publicShow', compact('scenario'));
    }
}