<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Step;

class ResultsController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());


    }

}