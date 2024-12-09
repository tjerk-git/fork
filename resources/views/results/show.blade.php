@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="headings">
            <h1>Resultaten voor {{ $scenario->name }}</h1>
            <a href="{{ route('results.index') }}" role="button" class="outline">
                <i class="fas fa-arrow-left"></i> Terug naar overzicht
            </a>
        </div>

        @foreach ($scenario->steps()->where('question_type', 'multiple_choice_question')->get() as $step)
            <div class="chart-container">
                <h3>{{ $step->multiple_choice_question }}</h3>
                <div id="pie-chart-{{ $step->id }}" class="pie-chart"></div>
            </div>
        @endforeach

        <a href="{{ route('results.csv', ['scenario' => $scenario->id]) }}" role="button" class="outline">
    <i class="fas fa-download"></i> Download resultaten (CSV)
</a>

        <div class="table-responsive">
            <table role="grid">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>E-mail</th>
                        <th>Vraag</th>
                        <th>Antwoord</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenario->results as $result)
                        @foreach ($result->lines as $line)
                            <tr>
                                <td>{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $result->email ?? 'Geen email' }}</td>
                                <td>
                                    @if ($line->step->question_type === 'open_question')
                                        {{ $line->step->open_question }}
                                    @elseif ($line->step->question_type === 'multiple_choice_question')
                                        {{ $line->step->multiple_choice_question }}
                                    @else
                                        {{ $line->step->description }}
                                    @endif
                                </td>
                                <td>{{ $line->value }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($scenario->steps()->where('question_type', 'multiple_choice_question')->get() as $step)
                // Get all results for this step
                var results = @json($step->resultLines->pluck('value'));
                
                // Count occurrences of each answer
                var counts = results.reduce(function(acc, val) {
                    acc[val] = (acc[val] || 0) + 1;
                    return acc;
                }, {});

                // Convert to array of objects for D3
                var data = Object.entries(counts).map(function(entry) {
                    return {
                        answer: entry[0],
                        count: entry[1]
                    };
                });

                // Set up dimensions
                var width = 400;
                var height = 400;
                var radius = Math.min(width, height) / 2;

                // Create color scale
                var color = d3.scaleOrdinal(d3.schemeCategory10);

                // Create SVG
                var svg = d3.select('#pie-chart-{{ $step->id }}')
                    .append('svg')
                    .attr('width', width)
                    .attr('height', height)
                    .append('g')
                    .attr('transform', 'translate(' + width / 2 + ',' + height / 2 + ')');

                // Create pie chart
                var pie = d3.pie()
                    .value(function(d) { return d.count; });

                var arc = d3.arc()
                    .innerRadius(0)
                    .outerRadius(radius);

                // Add paths
                svg.selectAll('path')
                    .data(pie(data))
                    .enter()
                    .append('path')
                    .attr('d', arc)
                    .attr('fill', function(d, i) { return color(i); })
                    .attr('stroke', 'white')
                    .style('stroke-width', '2px');

                // Add labels
                svg.selectAll('text')
                    .data(pie(data))
                    .enter()
                    .append('text')
                    .attr('transform', function(d) { return 'translate(' + arc.centroid(d) + ')'; })
                    .attr('dy', '.35em')
                    .style('text-anchor', 'middle')
                    .text(function(d) { return d.data.answer + ' (' + d.data.count + ')'; });
            @endforeach
        });
    </script>

    <style>
        .headings {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .chart-container {
            margin: 2rem 0;
            padding: 1rem;
            border: 1px solid var(--pico-muted-border-color);
            border-radius: var(--pico-border-radius);
        }

        .pie-chart {
            display: flex;
            justify-content: center;
            margin: 1rem 0;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 2rem;
        }
    </style>
@endsection