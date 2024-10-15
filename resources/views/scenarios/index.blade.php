@extends('layouts.app')

@section('title', 'Scenarios')

@push('styles')
    <style>
        .scenarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            padding: 1rem 0;
        }

        .scenario-card {
            display: flex;
            flex-direction: column;
            height: 250px;
            border: 1px solid var(--primary);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }

        .scenario-card:hover {
            transform: translateY(-5px);
        }

        .scenario-card header {
            background-color: var(--primary);
            color: var(--primary-inverse);
            padding: 0.5rem;
            font-weight: bold;
        }

        .scenario-card .content {
            flex-grow: 1;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
        }

        .scenario-card .description {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .scenario-card footer {
            padding: 0.5rem;
            background-color: var(--secondary-background);
            font-size: 0.8rem;
        }

        .scenario-card .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1>Scenarios</h1>
        <a href="{{ route('scenarios.create') }}" role="button" class="secondary">Create New Scenario</a>

        <div class="scenarios-grid">
            @foreach ($scenarios as $scenario)
                <article class="scenario-card">
                    <header>{{ $scenario->name }}</header>
                    <div class="content">
                        <p class="description">{{ $scenario->description }}</p>
                        <div class="actions">
                            <a href="{{ route('scenarios.show', $scenario) }}" role="button" class="outline">View</a>
                            @can('update', $scenario)
                                <a href="{{ route('scenarios.edit', $scenario) }}" role="button" class="outline">Edit</a>
                            @endcan
                        </div>
                    </div>
                    <footer>
                        <span>{{ $scenario->is_public ? 'Public' : 'Private' }}</span>
                        <span>By {{ $scenario->user->name }}</span>
                    </footer>
                </article>
            @endforeach
        </div>

        {{ $scenarios->links() }}
    </div>
@endsection
