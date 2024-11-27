@extends('layouts.app')



@section('content')
    <div class="container">
    <div class="headings">
            <h1>Resultaten voor {{ $scenario->name }}</h1>
            <a href="{{ route('scenarios.index') }}" role="button" class="outline">
                <i class="fas fa-arrow-left"></i> Terug naar overzicht
            </a>
        </div>
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
                            {{$step->question_type}}
                            <td>
                                @if ($step->question_type == 'intro')
                                    <strong>Introductie:</strong> {{ $step->description }}
                                @elseif ($step->question_type == 'open_question')
                                    <strong>Open vraag:</strong> {{ $step->open_question }}
                                @elseif ($step->question_type == 'multiple_choice_question')
                                    <strong>Meerkeuze vraag:</strong> {{ $step->multiple_choice_question }}
                                @endif
                            </td>
                            <td>
                        <a href="{{ route('steps.edit', ['step' => $step->id, 'scenario' => $scenario->id]) }}" class="outline">
                            <i class="fas fa-pencil"></i> Bewerken
                        </a>

                                <form
                                    action="{{ route('steps.destroy', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
                                    method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                             <button type="submit" class="btn btn-danger btn-sm outline secondary" onclick="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?')">
                                 <i class="fas fa-trash"></i> Verwijderen
                             </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Geen vragen toegevoegd..</p>
        @endif

        <a href="{{ route('steps.create', ['scenario' => $scenario->id]) }}" class="outline" role="button">
            <i class="fas fa-plus"></i> Voeg een vraag toe
        </a>

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
