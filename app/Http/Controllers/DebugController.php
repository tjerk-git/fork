<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scenario;

class DebugController extends Controller
{
    //
    // index method
    public function index()
    {
        $scenarios = Scenario::all();

        // return the view
        return view('debug.index', compact('scenarios'));
    }

    public function results(){
        $scenario = Scenario::find(1);

        // return the view
        return view('debug.results', compact('scenario'));
    }
}
