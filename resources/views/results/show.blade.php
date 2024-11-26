@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="headings">
            <h1>Resultaten voor {{ $scenario->name }}</h1>
            <a href="{{ route('results.index') }}" role="button" class="outline">Terug naar overzicht</a>
        </div>

        <div class="table-responsive">
            <table role="grid">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Browser</th>
                        <th>Vraag</th>
                        <th>Antwoord</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenario->results as $result)
                        @foreach ($result->lines as $line)
                            <tr>
                                <td>{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $result->browser }}</td>
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

                    @if ($scenario->results->isEmpty())
                        <tr>
                            <td colspan="4">Nog geen resultaten beschikbaar</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .headings {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection