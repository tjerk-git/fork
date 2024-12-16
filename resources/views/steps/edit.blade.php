@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $referencingSteps = $scenario->steps->filter(function($otherStep) use ($step) {
            return $otherStep->fork_to_step == $step->id;
        });
    @endphp
    
    @if($referencingSteps->count() > 0)
        <div class="alert alert-info">
            <strong>Deze stap wordt gebruikt als doorverwijzing door:</strong>
            <ul>
                @foreach($referencingSteps as $referencingStep)
                    <li>Vraag: {{ $referencingStep->multiple_choice_question }} (wanneer optie {{ $referencingStep->fork_condition }} wordt gekozen)</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1>
        @if ($step->question_type === 'intro')
            Introductie voor {{ $scenario->name }}
        @else
            Een vraag voor {{ $scenario->name }}
        @endif
    </h1>

    <form action="{{ route('steps.update', ['step' => $step->id, 'scenario' => $scenario->id]) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Attachment Section --}}
        @include('partials.attachment')

        <div class="form-group" id="attachment">
            <label for="attachment">Video of afbeelding aanpassen</label>
            <input type="file" 
                   class="form-control-file @error('attachment') is-invalid @enderror" 
                   id="attachment"
                   name="attachment">
            @error('attachment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Introduction Content --}}
        @if ($step->question_type == 'intro')
            <div class="form-group">
                <label for="content">Omschrijving</label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                          id="description" 
                          name="description"
                          rows="3">{{ $step->description }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $description }}</div>
                @enderror
            </div>
        @endif

        {{-- Question Content --}}
        @if ($step->question_type == 'open_question')
            <div class="form-group" id="open_question_div">
                <label for="open_question">Een open vraag</label>
                <input type="text" 
                       class="form-control" 
                       id="open_question" 
                       name="open_question"
                       value="{{ $step->open_question }}">
            </div>

            @elseif ($step->question_type == 'tussenstap')
                <div class="form-group" id="tussenstap_div">
                    <label for="description">Beschrijving</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="3">{{ $step->description }}</textarea>
                </div>
        @elseif ($step->question_type == 'multiple_choice_question')
            <div class="form-group" id="multiple_c">
                <label for="multiple_choice_question">Een meerkeuze vraag:</label>
                <input type="text" 
                       class="form-control" 
                       id="multiple_choice_question" 
                       name="multiple_choice_question"
                       value="{{ $step->multiple_choice_question }}">

                <label for="multiple_choice_option_1">Optie 1</label>
                <input type="text" 
                       class="form-control" 
                       id="option_1" 
                       name="multiple_choice_option_1"
                       value="{{ $step->multiple_choice_option_1 }}">

                <label for="multiple_choice_option_2">Optie 2</label>
                <input type="text" 
                       class="form-control" 
                       id="option_2" 
                       name="multiple_choice_option_2"
                       value="{{ $step->multiple_choice_option_2 }}">

                <label for="multiple_choice_option_3">Optie 3</label>
                <input type="text" 
                       class="form-control" 
                       id="option_3" 
                       name="multiple_choice_option_3"
                       value="{{ $step->multiple_choice_option_3 }}">
            </div>

   
           

            {{-- Conditional Navigation --}}
            @if ($scenario->steps->count() > 0)
                <div class="form-group">
                    <label for="fork_condition">Conditie voor doorverwijzing</label>
                    <select class="form-control" id="fork_condition" name="fork_condition">
                        <option value="">Selecteer een optie</option>
                        <option value="1" {{ $step->fork_condition == '1' ? 'selected' : '' }}>Optie 1</option>
                        <option value="2" {{ $step->fork_condition == '2' ? 'selected' : '' }}>Optie 2</option>
                        <option value="3" {{ $step->fork_condition == '3' ? 'selected' : '' }}>Optie 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="previous_step_id">Link naar andere vraag</label>
                    <select class="form-control" id="fork_to_step" name="fork_to_step">
                        <option value="" {{ !$step->fork_to_step ? 'selected' : '' }}>Naar volgende vraag</option>
                        @foreach ($scenario->steps->filter(function($otherStep) use ($step) { 
                            return $otherStep->id !== $step->id && $otherStep->question_type !== 'intro'; 
                        }) as $otherStep)
                            <option value="{{ $otherStep->id }}" 
                                    {{ $step->fork_to_step == $otherStep->id ? 'selected' : '' }}>
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
        @endif

        {{-- Form Actions --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Vraag opslaan</button>
        </div>
    </form>

    {{-- Delete Form --}}
    <div class="mt-3">
        <form action="{{ route('steps.destroy', ['step' => $step->id, 'scenario' => $scenario->id]) }}" 
              method="POST" 
              style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-danger outline danger"
                    onclick="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?')">
                Verwijder deze vraag
            </button>
        </form>
        <a href="{{ route('scenarios.show', ['scenario' => $scenario->id]) }}" 
           class="btn btn-secondary">
            Terug naar scenario
        </a>
    </div>
</div>

@push('styles')
<style>
    video {
        max-width: 600px;
    }
</style>
@endpush
@endsection
