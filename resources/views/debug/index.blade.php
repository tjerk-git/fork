<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Scenarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #2c3e50;
        }

        p,
        li {
            color: #34495e;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .scenario,
        .result,
        .line,
        .component,
        .option {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ecf0f1;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .scenario {
            background-color: #e8f8f5;
        }

        .result {
            background-color: #fef9e7;
        }

        .line {
            background-color: #f9ebea;
        }

        .component {
            background-color: #eaf2f8;
        }

        .option {
            background-color: #f4ecf7;
        }
    </style>
</head>

@extends('layouts.app', ['title' => 'FORK LOGIN'])

<body>
    @foreach ($scenarios as $scenario)
        <div class="scenario">
            <h2>{{ $scenario->name }}</h2>
            <p>{{ $scenario->description }}</p>

            @foreach ($scenario->results as $result)
                <div class="result">
                    <h2>Result</h2>
                    <p>{{ $result->body }}</p>
                    @foreach ($result->lines as $line)
                        <div class="line">
                            <h2>Line</h2>
                            <p>{{ $line->value }}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <ul>
                @foreach ($scenario->steps as $step)
                    <li>
                        <div class="step">
                            <p>{{ $step->description }}</p>

                            @foreach ($step->components as $component)
                                <div class="component">
                                    <h2>Component</h2>
                                    <p>{{ $component->type }}</p>

                                    @foreach ($component->options as $option)
                                        <div class="option">
                                            <h2>Option</h2>
                                            <p>{{ $option->body }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</body>

</html>
