@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 text-sm text-muted-foreground">
                        <li>
                            <a href="{{ route('scenarios.index') }}" class="hover:text-foreground">
                                Scenarios
                            </a>
                        </li>
                        <li class="flex items-center space-x-1">
                            <span>/</span>
                            <span class="text-foreground">{{ $scenario->name }}</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold tracking-tight">{{ $scenario->name }}</h1>
                @if($scenario->description)
                    <p class="text-sm text-muted-foreground">{{ $scenario->description }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="window.location.href='{{ route('scenarios.edit', $scenario) }}'" 
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                    <i class="fas fa-edit mr-2"></i> Bewerk
                </button>
                <form action="{{ route('scenarios.destroy', $scenario) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center justify-center rounded-md bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground hover:bg-destructive/90"
                            onclick="return confirm('Weet je zeker dat je dit scenario wilt verwijderen?')">
                        <i class="fas fa-trash mr-2"></i> Verwijder
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-medium text-muted-foreground">Status</h3>
                        <p class="mt-1">{{ $scenario->is_public ? 'Gepubliceerd' : 'Prive' }}</p>
                    </div>
                    @if ($scenario->access_code)
                    <div>
                        <h3 class="text-sm font-medium text-muted-foreground">Toegangscode</h3>
                        <p class="mt-1">{{ $scenario->access_code }}</p>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-muted-foreground">Publieke URL</h3>
                        <a href="{{ url('/scenarios/start/' . $scenario->slug) }}" 
                           class="mt-1 inline-block text-primary hover:underline">
                            {{ url('/scenarios/start/' . $scenario->slug) }}
                        </a>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('scenarios.update', $scenario) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_public" value="{{ $scenario->is_public ? '0' : '1' }}">
                        <button type="submit" 
                                class="inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium {{ $scenario->is_public 
                                    ? 'bg-secondary text-secondary-foreground hover:bg-secondary/80' 
                                    : 'bg-primary text-primary-foreground hover:bg-primary/90' }}">
                            <i class="fas {{ $scenario->is_public ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $scenario->is_public ? 'Maak privé' : 'Maak publiek' }}
                        </button>
                    </form>

                    <button onclick="window.location.href='{{ route('results.show', $scenario) }}'"
                            class="inline-flex items-center justify-center rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground hover:bg-secondary/80">
                        <i class="fas fa-chart-bar mr-2"></i> Bekijk resultaten
                    </button>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <h2 class="text-2xl font-semibold tracking-tight">Vragen</h2>
                        <p class="text-sm text-muted-foreground">
                            Sleep de vragen om ze te herschikken
                        </p>
                    </div>
                    <button onclick="window.addQuestionDialog.showModal()" 
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                        <i class="fas fa-plus mr-2"></i> Voeg een vraag toe
                    </button>
                </div>

                <div class="mt-6">
                    <div class="rounded-md border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 w-12 px-4 text-left align-middle font-medium text-muted-foreground"></th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Vraag</th>
                                    <th class="h-12 w-[120px] px-4 text-left align-middle font-medium text-muted-foreground">Acties</th>
                                </tr>
                            </thead>
                            <tbody id="steps-sortable">
                                @foreach ($scenario->steps()->orderBy('order')->get() as $step)
                                    @php
                                        $isForkDestination = $scenario->steps()->where('fork_to_step', $step->id)->exists();
                                    @endphp
                                    <tr data-id="{{ $step->id }}" class="border-b transition-colors hover:bg-muted/50 {{ $isForkDestination ? 'bg-destructive/10' : '' }}">
                                        <td class="h-12 w-12 px-4 align-middle">
                                            <div class="handle cursor-move text-muted-foreground">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">
                                            <div class="space-y-1">
                                                @if ($step->question_type == 'intro')
                                                    <div class="font-medium">Introductie</div>
                                                    <p class="text-sm text-muted-foreground">{{ $step->description }}</p>
                                                @elseif ($step->question_type == 'open_question')
                                                    <div class="font-medium">Open vraag</div>
                                                    <p class="text-sm text-muted-foreground">{{ $step->open_question }}</p>
                                                @elseif ($step->question_type == 'multiple_choice_question')
                                                    <div class="font-medium">Meerkeuze vraag</div>
                                                    <p class="text-sm text-muted-foreground">{{ $step->multiple_choice_question }}</p>
                                                @elseif ($step->question_type == 'tussenstap')
                                                    <div class="font-medium">Tussenstap</div>
                                                    <p class="text-sm text-muted-foreground">{{ $step->description }}</p>
                                                @endif
                                                @if($isForkDestination)
                                                    <p class="text-sm text-muted-foreground italic">
                                                        Deze vraag is een doorverwijzingsdoel
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('steps.edit', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground">
                                                    <i class="fas fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('steps.destroy', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?')"
                                                            class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-destructive text-sm font-medium text-destructive-foreground hover:bg-destructive/90">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($scenario->steps->isEmpty())
                        <div class="rounded-lg border border-dashed p-8">
                            <div class="flex flex-col items-center justify-center space-y-2 text-center">
                                <div class="text-4xl text-muted-foreground">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3 class="text-lg font-medium">Geen stappen</h3>
                                <p class="text-sm text-muted-foreground">
                                    Dit scenario heeft nog geen stappen. Klik op "Voeg een vraag toe" om er een toe te voegen.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Dialog -->
<dialog id="addQuestionDialog" class="modal rounded-lg shadow-lg bg-background p-6 w-[800px]">
    <div class="w-full">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-semibold">Nieuwe vraag voor: {{ $scenario->name }}</h2>
        </div>

        <form action="{{ route('steps.store', ['scenario' => $scenario->id]) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="px-6 py-4 space-y-6">
            @csrf
            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
            <input type="hidden" name="question_type" id="question_type">

            {{-- Question Type Selector --}}
            <div class="space-y-2">
                <label for="question_type_selector" class="text-sm font-medium">
                    Type vraag
                </label>
                <select class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                        id="question_type_selector" 
                        name="question_type_selector">
                    <option value="">Selecteer een type vraag</option>
                    <option value="open_question">Open vraag</option>
                    <option value="multiple_c">Meerkeuze vraag</option>
                    <option value="tussenstap">Tussenstap</option>
                </select>
            </div>

            {{-- Open Question --}}
            <div class="space-y-2" id="open_question" style="display:none;">
                <label for="open_question" class="text-sm font-medium">
                    Een open vraag
                </label>
                <input type="text" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                       id="open_question" 
                       name="open_question"
                       value="{{ old('open_question') }}">
                @include('partials.keywords-section')
            </div>

            {{-- Multiple Choice Question --}}
            <div class="space-y-6" id="multiple_c" style="display:none;">
                <div class="space-y-2">
                    <label for="multiple_choice_question" class="text-sm font-medium">
                        Een meerkeuze vraag:
                    </label>
                    <input type="text" 
                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                           id="multiple_choice_question" 
                           name="multiple_choice_question"
                           value="{{ old('multiple_choice_question') }}">
                </div>

                @for ($i = 1; $i <= 3; $i++)
                    <div class="space-y-2">
                        <label for="option_{{ $i }}" class="text-sm font-medium">
                            Optie {{ $i }}
                        </label>
                        <input type="text" 
                               class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                               id="option_{{ $i }}" 
                               name="multiple_choice_option_{{ $i }}"
                               value="{{ old('multiple_choice_option_' . $i) }}">
                    </div>
                @endfor

                {{-- Conditional Navigation --}}
                @if ($scenario->steps->count() > 0)
                    <div class="space-y-2">
                        <label for="fork_condition" class="text-sm font-medium">
                            Conditie voor doorverwijzing
                        </label>
                        <select class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                                id="fork_condition" 
                                name="fork_condition">
                            <option value="">Selecteer een optie</option>
                            @for ($i = 1; $i <= 3; $i++)
                                <option value="{{ $i }}">Optie {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="fork_to_step" class="text-sm font-medium">
                            Link naar andere vraag
                        </label>
                        <select class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                                id="fork_to_step" 
                                name="fork_to_step">
                            <option value="">Naar volgende vraag</option>
                            @foreach ($scenario->steps->filter(function($otherStep) { 
                                return $otherStep->question_type !== 'intro'; 
                            }) as $otherStep)
                                <option value="{{ $otherStep->id }}">
                                    Naar vraag: 
                                    @if ($otherStep->question_type == 'open_question')
                                        {{ $otherStep->open_question }}
                                    @elseif ($otherStep->question_type == 'multiple_choice_question')
                                        {{ $otherStep->multiple_choice_question }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            {{-- Tussenstap --}}
            <div class="space-y-2" id="tussenstap_div" style="display:none;">
                <label for="description" class="text-sm font-medium">
                    Beschrijving
                </label>
                <textarea class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                          id="description" 
                          name="description" 
                          rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-between items-center border-t pt-6 mt-6">
                <button type="submit" 
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                    Voeg vraag toe
                </button>

                <button type="button" 
                        onclick="window.addQuestionDialog.close()"
                        class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground">
                    Annuleren
                </button>
            </div>
        </form>
    </div>
</dialog>

<script defer src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sortable initialization
        const el = document.getElementById('steps-sortable');
        if (el) {
            var sortable = new Sortable(el, {
                handle: '.handle',
                animation: 150,
                draggable: 'tr',
                onEnd: function(evt) {
                    var items = el.getElementsByTagName('tr');
                    var stepOrder = Array.from(items).map(item => item.dataset.id);

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
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Question type selector functionality
        const questionTypeSelector = document.getElementById('question_type_selector');
        const questionTypeInput = document.getElementById('question_type');
        const openQuestionDiv = document.getElementById('open_question');
        const multipleChoiceDiv = document.getElementById('multiple_c');
        const tussenstapDiv = document.getElementById('tussenstap_div');

        questionTypeSelector?.addEventListener('change', function() {
            const selectedValue = this.value;
            questionTypeInput.value = selectedValue;

            // Hide all divs first
            openQuestionDiv.style.display = 'none';
            multipleChoiceDiv.style.display = 'none';
            tussenstapDiv.style.display = 'none';

            // Show the selected div
            if (selectedValue === 'open_question') {
                openQuestionDiv.style.display = 'block';
            } else if (selectedValue === 'multiple_c') {
                multipleChoiceDiv.style.display = 'block';
            } else if (selectedValue === 'tussenstap') {
                tussenstapDiv.style.display = 'block';
            }
        });
    });
</script>

@include('partials.keywords-scripts')
@endsection
