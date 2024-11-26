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


        <div class="table-responsive">
            <table role="grid">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>IP</th>
                        <th>Vraag</th>
                        <th>Antwoord</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenario->results as $result)
                        @foreach ($result->lines as $line)
                            <tr>
                                <td>{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $result->ip }}</td>
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
                const results_{{ $step->id }} = @json($step->resultLines->pluck('value'));
                
                // Count occurrences of each answer
                const counts_{{ $step->id }} = results_{{ $step->id }}.reduce((acc, val) => {
                    acc[val] = (acc[val] || 0) + 1;
                    return acc;
                }, {});

                // Convert to array of objects for D3
                const data_{{ $step->id }} = Object.entries(counts_{{ $step->id }}).map(([key, value]) => ({
                    answer: key,
                    count: value
                }));

                // Set up dimensions
                const width = 400;
                const height = 400;
                const radius = Math.min(width, height) / 2;

                // Create color scale
                const color = d3.scaleOrdinal(d3.schemeCategory10);

                // Create SVG
                const svg_{{ $step->id }} = d3.select('#pie-chart-{{ $step->id }}')
                    .append('svg')
                    .attr('width', width)
                    .attr('height', height)
                    .append('g')
                    .attr('transform', `translate(${width / 2},${height / 2})`);

                // Create pie chart
                const pie = d3.pie()
                    .value(d => d.count);

                const arc = d3.arc()
                    .innerRadius(0)
                    .outerRadius(radius);

                // Add paths
                const paths = svg_{{ $step->id }}.selectAll('path')
                    .data(pie(data_{{ $step->id }}))
                    .enter()
                    .append('path')
                    .attr('d', arc)
                    .attr('fill', (d, i) => color(i))
                    .attr('stroke', 'white')
                    .style('stroke-width', '2px');

                // Add labels
                const labels = svg_{{ $step->id }}.selectAll('text')
                    .data(pie(data_{{ $step->id }}))
                    .enter()
                    .append('text')
                    .attr('transform', d => `translate(${arc.centroid(d)})`)
                    .attr('dy', '.35em')
                    .style('text-anchor', 'middle')
                    .text(d => `${d.data.answer} (${d.data.count})`);
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