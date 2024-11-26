@extends('layouts.app')



@section('content')
    <div class="container">
        <h1>{{ $scenario->name }}</h1>
        <h5 class="card-title mt-4">Details</h5>
        <table class="table table-bordered">
            <tr>
                <th>Status</th>
                <td>{{ $scenario->is_public ? 'Gepubliceerd' : 'Prive' }}</td>
            </tr>

            @if ($scenario->access_code)
                <tr>
                    <th>Toegangscode</th>
                    <td>{{ $scenario->access_code }}</td>
                </tr>
            @endif

            <tr>
                <th>Publieke URL</th>
                <td>
                    <a
                        href="{{ url('/scenarios/start/' . $scenario->slug) }}">{{ url('/scenarios/start/' . $scenario->slug) }}</a>
                </td>
            </tr>

            <tr>
                <th>Acties</th>
                <td> <a href="{{ route('scenarios.edit', $scenario) }}" role="button" class="outline">Bewerken</a>

                    <form action="{{ route('scenarios.destroy', $scenario) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger secondary outline"
                            onclick="return confirm('Weet je zeker dat je dit scenario wilt verwijderen?')">Verwijderen</button>
                    </form>
                </td>
            </tr>

        </table>


        <div class="card mb-4">
            <div class="card-body">


                @if ($scenario->attachment)
                    @php
                        $fileExtension = pathinfo($scenario->attachment, PATHINFO_EXTENSION);
                    @endphp
                    @if ($fileExtension == 'mp4')
                        <video controls class="img-fluid">
                            <source src="{{ Storage::url($scenario->attachment) }}" type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img src="{{ asset('storage/' . $scenario->attachment) }}" alt="Uploaded Image">
                    @endif
                @endif

            </div>
        </div>

        <h2>Vragen:</h2>
        @if ($scenario->steps->count() > 0)
            <table class="table table-bordered mb-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vraag</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody id="steps-sortable">
                    @foreach ($scenario->steps()->orderBy('order')->get() as $step)
                        <tr data-id="{{ $step->id }}">
                            <td class="handle" style="cursor: move;">&#9776;</td>

                            <td>
                                @if ($step->question_type == 'intro')
                                    <strong>Introductie:</strong> {{ $step->description }}
                                @elseif ($step->question_type == 'open_question')
                                    <strong>Open vraag:</strong> {{ $step->description }}
                                @elseif ($step->question_type == 'multiple_c')
                                    <strong>Meerkeuze vraag:</strong> {{ $step->description }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('steps.edit', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
                                    role="button" class="btn btn-primary btn-sm outline">Bewerken</a>

                                <form
                                    action="{{ route('steps.destroy', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
                                    method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm outline secondary"
                                        onclick="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?')">Verwijderen</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Geen vragen toegevoegd..</p>
        @endif

        <a href="{{ route('steps.create', ['scenario' => $scenario->id]) }}" class="outline" role="button">Voeg een vraag
            toe +</a>

     
        </div>

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

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('steps-sortable');
            var sortable = new Sortable(el, {
                handle: '.handle',
                animation: 150,
                onEnd: function(evt) {
                    var stepOrder = sortable.toArray();


                    fetch('{{ route('scenario.update-step-order', $scenario->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                steps: stepOrder
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Order updated successfully');
                                // reload the page
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>

@endsection
