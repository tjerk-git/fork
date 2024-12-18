@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        @if ($step->question_type === 'intro')
            Introductie voor {{ $scenario->name }}
        @else
            Een vraag voor {{ $scenario->name }}
        @endif
    </h1>

    @if(session('success'))
        <article aria-label="Success message" style="background-color: #d1e7dd; border-color: #badbcc; color: #0f5132; margin-bottom: 1rem;">
            {{ session('success') }}
        </article>
    @endif

    @if($errors->any())
        <article aria-label="Error message" style="background-color: #f8d7da; border-color: #f5c2c7; color: #842029; margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </article>
    @endif

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

    <form action="{{ route('steps.update', ['step' => $step->id, 'scenario' => $scenario->id]) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
        <input type="hidden" name="question_type" value="{{ $step->question_type }}">

        {{-- Attachment Section --}}
        @include('partials.show-attachment')
        @include('partials.add-attachment')

        {{-- Open Question --}}
        @if($step->question_type == 'open_question')
            <div class="form-group">
                <label for="open_question">Een open vraag</label>
                <input type="text" 
                       class="form-control" 
                       id="open_question" 
                       name="open_question"
                       value="{{ old('open_question', $step->open_question) }}">
                
                @include('partials.keywords-section')
            </div>
        @endif

        {{-- Multiple Choice Question --}}
        @if($step->question_type == 'multiple_choice_question')
            <div class="form-group">
                <label for="multiple_choice_question">Meerkeuze vraag</label>
                <input type="text" 
                       class="form-control" 
                       id="multiple_choice_question" 
                       name="multiple_choice_question"
                       value="{{ old('multiple_choice_question', $step->multiple_choice_question) }}">
            </div>

            <div class="form-group">
                <label for="multiple_choice_option_1">Optie 1</label>
                <input type="text" 
                       class="form-control" 
                       id="multiple_choice_option_1" 
                       name="multiple_choice_option_1"
                       value="{{ old('multiple_choice_option_1', $step->multiple_choice_option_1) }}">
            </div>

            <div class="form-group">
                <label for="multiple_choice_option_2">Optie 2</label>
                <input type="text" 
                       class="form-control" 
                       id="multiple_choice_option_2" 
                       name="multiple_choice_option_2"
                       value="{{ old('multiple_choice_option_2', $step->multiple_choice_option_2) }}">
            </div>

            <div class="form-group">
                <label for="multiple_choice_option_3">Optie 3</label>
                <input type="text" 
                       class="form-control" 
                       id="multiple_choice_option_3" 
                       name="multiple_choice_option_3"
                       value="{{ old('multiple_choice_option_3', $step->multiple_choice_option_3) }}">
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

        {{-- Tussenstap --}}
        @if($step->question_type == 'tussenstap')
            <div class="form-group">
                <label for="description">Beschrijving</label>
                <textarea class="form-control" 
                          id="description" 
                          name="description" 
                          rows="3">{{ old('description', $step->description) }}</textarea>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Update vraag</button>
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
    </div>

    {{-- Form Actions --}}
        <div class="mt-4">
        
            <a href="{{ route('scenarios.show', ['scenario' => $scenario->id]) }}" 
               class="btn btn-secondary">
                Terug naar scenario
            </a>
        </div>
</div>



@include('partials.keywords-scripts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/keywords.css') }}">
@endpush
@endsection
