@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="headings">
            <h1>Resultaten</h1>
        </div>

        <div class="table-container">
            <table role="grid">
                <thead>
                    <tr>
                        <th>Scenario</th>
                        <th>Aantal resultaten</th>
                        <th>Laatste resultaat</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenarios as $scenario)
                        <tr>
                            <td>{{ $scenario->name }}</td>
                            <td>{{ $scenario->results->count() }}</td>
                            <td>
                                @if($scenario->results->isNotEmpty())
                                    {{ $scenario->results->sortByDesc('created_at')->first()->created_at->format('d-m-Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('results.show', $scenario) }}" role="button" class="outline">Bekijk resultaten</a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($scenarios->isEmpty())
                        <tr>
                            <td colspan="4">Geen scenarios gevonden</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .headings {
            margin-bottom: 2rem;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
@endsection
