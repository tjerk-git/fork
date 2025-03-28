@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-3xl font-bold tracking-tight">Resultaten voor {{ $scenario->name }}</h1>
            <p class="text-sm text-muted-foreground">Bekijk de resultaten van dit scenario</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('results.csv', ['scenario' => $scenario->id]) }}" 
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <i class="fas fa-download mr-2"></i> Download resultaten (CSV)
            </a>
            <a href="{{ route('results.index') }}" 
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                <i class="fas fa-arrow-left mr-2"></i> Terug naar overzicht
            </a>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($scenario->steps()->where('question_type', 'multiple_c')->get() as $step)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-medium">{{ $step->multiple_c }}</h3>
                    <div id="pie-chart-{{ $step->id }}" class="pie-chart"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="rounded-md border">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 transition-colors">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Datum</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">E-mail</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Vraag</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Antwoord</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenario->results as $result)
                        @foreach ($result->lines as $line)
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <td class="p-4 align-middle">{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                <td class="p-4 align-middle">{{ $result->email ?? 'Geen email' }}</td>
                                <td class="p-4 align-middle">
                                    @if ($line->step->question_type === 'open_question')
                                        {{ $line->step->open_question }}
                                    @elseif ($line->step->question_type === 'multiple_c' || $line->step->question_type === 'multiple_choice_question')
                                        {{ $line->step->multiple_choice_question }}
                                    @else
                                        {{ $line->step->description }}
                                    @endif
                                </td>
                                <td class="p-4 align-middle">{{ $line->value }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($scenario->steps()->where('question_type', 'multiple_c')->get() as $step)
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
            var width = 300;
            var height = 300;
            var radius = Math.min(width, height) / 2;

            // Create color scale with shadcn-ui colors
            var colors = ['#0ea5e9', '#f97316', '#22c55e', '#ec4899', '#8b5cf6', '#14b8a6', '#f43f5e', '#06b6d4'];
            var color = d3.scaleOrdinal(colors);

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

            // Add paths with smooth transitions
            var paths = svg.selectAll('path')
                .data(pie(data))
                .enter()
                .append('path')
                .attr('d', arc)
                .attr('fill', function(d, i) { return color(i); })
                .attr('stroke', 'white')
                .style('stroke-width', '2px')
                .style('transition', 'all 0.3s ease');

            // Add hover effect
            paths.on('mouseover', function() {
                d3.select(this)
                    .style('opacity', 0.8)
                    .style('transform', 'scale(1.05)');
            })
            .on('mouseout', function() {
                d3.select(this)
                    .style('opacity', 1)
                    .style('transform', 'scale(1)');
            });

            // Add labels with better positioning and styling
            svg.selectAll('text')
                .data(pie(data))
                .enter()
                .append('text')
                .attr('transform', function(d) { 
                    // Only show label if slice is big enough
                    return d.endAngle - d.startAngle > 0.2 ? 'translate(' + arc.centroid(d) + ')' : null; 
                })
                .attr('dy', '.35em')
                .style('text-anchor', 'middle')
                .style('font-size', '12px')
                .style('fill', 'white')
                .style('font-weight', '500')
                .text(function(d) { 
                    // Truncate answer if too long
                    let answer = d.data.answer;
                    if (answer.length > 15) {
                        answer = answer.substring(0, 12) + '...';
                    }
                    return answer + ' (' + d.data.count + ')'; 
                });
        @endforeach
    });
</script>
@endsection