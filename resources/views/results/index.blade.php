@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-3xl font-bold tracking-tight">Resultaten</h1>
            <p class="text-sm text-muted-foreground">Bekijk de resultaten van alle scenarios</p>
        </div>
    </div>

    <div class="rounded-md border">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 transition-colors">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Scenario</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aantal resultaten</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Laatste resultaat</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenarios as $scenario)
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <td class="p-4 align-middle">{{ $scenario->name }}</td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    {{ $scenario->results->count() }}
                                </span>
                            </td>
                            <td class="p-4 align-middle">
                                @if($scenario->results->isNotEmpty())
                                    <span class="text-muted-foreground">
                                        {{ $scenario->results->sortByDesc('created_at')->first()->created_at->format('d-m-Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground">-</span>
                                @endif
                            </td>
                            <td class="p-4 align-middle">
                                <a href="{{ route('results.show', $scenario) }}" 
                                   class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Bekijk resultaten
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($scenarios->isEmpty())
                        <tr>
                            <td colspan="4" class="p-4 text-center text-muted-foreground">
                                Geen scenarios gevonden
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
