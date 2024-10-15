@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $scenario->name }}</h1>
        <p class="text-muted">Created by {{ $scenario->user->name }} | {{ $scenario->created_at->format('F d, Y') }}</p>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Description</h5>
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
                        <p>Unsupported file type.</p>
                    @endif
                @endif

                <h5 class="card-title mt-4">Details</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Status: {{ $scenario->is_public ? 'Public' : 'Private' }}</li>
                    @if (!$scenario->is_public)
                        <li class="list-group-item">Access Code: {{ $scenario->access_code }}</li>
                    @endif
                    <li class="list-group-item">Slug: {{ $scenario->slug }}</li>
                </ul>
            </div>
        </div>

        <h2>Steps</h2>
        @if ($scenario->steps->count() > 0)
            <ul class="list-group mb-4">
                @foreach ($scenario->steps as $step)
                    <li class="list-group-item">{{ $step->description }}</li>
                @endforeach
            </ul>
        @else
            <p>No steps defined for this scenario.</p>
        @endif

        <h2>Results</h2>
        @if ($scenario->results->count() > 0)
            <ul class="list-group mb-4">
                @foreach ($scenario->results as $result)
                    <li class="list-group-item">
                        Result {{ $loop->iteration }}: {{ $result->description }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No results recorded for this scenario.</p>
        @endif

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
            <a href="{{ route('scenarios.index') }}" class="btn btn-secondary">Back to All Scenarios</a>
        </div>
    </div>
@endsection
