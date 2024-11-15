@extends('layouts.app')


@section('content')
    <div class="container">
        <h1>{{ $scenario->name }}</h1>
        <h5 class="card-title mt-4">Details</h5>
        <table class="table table-bordered">
            <tr>
                <th>Status</th>
                <td>{{ $scenario->is_public ? 'Publiek' : 'Prive' }}</td>
            </tr>
            @if (!$scenario->is_public)
                <tr>
                    <th>Toegangscode</th>
                    <td>{{ $scenario->access_code }}</td>
                </tr>
            @endif
            <tr>
                <th>Publieke URL</th>
                <td>{{ $scenario->slug }}</td>
            </tr>
        </table>


        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Omschrijving</h5>
                <p class="card-text">{{ $scenario->description }}</p>

                @if ($scenario->attachment)
                    @php
                        $fileExtension = pathinfo($scenario->attachment, PATHINFO_EXTENSION);
                    @endphp

                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ Storage::url($scenario->attachment) }}" alt="Attachment" class="img-fluid">
                    @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                        <video controls class="img-fluid">
                            <source src="{{ Storage::url($scenario->attachment) }}" type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                    @endif
                @endif

            </div>
        </div>

        <h2>Vragen:</h2>
        @if ($scenario->steps->count() > 0)
            <ul class="list-group mb-4">
                @foreach ($scenario->steps as $step)
                    <li class="list-group-item">
                        @if (!empty($step->open_question))
                            <a href="{{ route('steps.edit', ['step' => $step->id, 'scenario' => $scenario->id]) }}">Open
                                vraag</a>
                        @else
                            <a href="{{ route('steps.edit', ['step' => $step->id, 'scenario' => $scenario->id]) }}">Meerkeuze
                                vraag</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p>Geen vragen toegevoegd..</p>
        @endif

        <a href="{{ route('steps.create', ['scenario' => $scenario->id]) }}" class="outline">Voeg een vraag toe +</a>

        <h2>Resultaten</h2>

        <div class="mt-4">
            @can('update', $scenario)
                <a href="{{ route('scenarios.edit', $scenario) }}" class="btn btn-primary">Edit Scenario</a>
            @endcan
            @can('delete', $scenario)
                <form action="{{ route('scenarios.destroy', $scenario) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this scenario?')">Delete Scenario</button>
                </form>
            @endcan
            <a href="{{ route('scenarios.index') }}" class="btn btn-secondary">Terug naar alle scenarios</a>
        </div>
    </div>


@endsection
