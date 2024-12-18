@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nieuwe vraag voor: {{ $scenario->name }}</h1>

    <form action="{{ route('steps.store', ['scenario' => $scenario->id]) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
        <input type="hidden" name="question_type" id="question_type">

        {{-- Question Type Selector --}}
        <div class="form-group">
            <label for="question_type">Type vraag:</label>
            <select class="form-control" id="question_type_selector" name="question_type_selector">
                <option value="">Selecteer een type vraag</option>
                <option value="open_question">Open vraag</option>
                <option value="multiple_c">Meerkeuze vraag</option>
                <option value="tussenstap">Tussenstap</option>
            </select>
        </div>

        {{-- Attachment Section --}}
        @include('partials.show-attachment')
        @include('partials.add-attachment')

        {{-- Open Question --}}
        <div class="form-group" id="open_question_div" style="display:none;">
            @include('partials.keywords-section')
        </div>

        {{-- Multiple Choice Question --}}
        <div class="form-group" id="multiple_c" style="display:none;">
            <label for="multiple_choice_question">Een meerkeuze vraag:</label>
            <input type="text" 
                   class="form-control" 
                   id="multiple_choice_question" 
                   name="multiple_choice_question"
                   value="{{ old('multiple_choice_question') }}">

            <label for="multiple_choice_option_1">Optie 1</label>
            <input type="text" 
                   class="form-control" 
                   id="option_1" 
                   name="multiple_choice_option_1"
                   value="{{ old('multiple_choice_option_1') }}">

            <label for="multiple_choice_option_2">Optie 2</label>
            <input type="text" 
                   class="form-control" 
                   id="option_2" 
                   name="multiple_choice_option_2"
                   value="{{ old('multiple_choice_option_2') }}">

            <label for="multiple_choice_option_3">Optie 3</label>
            <input type="text" 
                   class="form-control" 
                   id="option_3" 
                   name="multiple_choice_option_3"
                   value="{{ old('multiple_choice_option_3') }}">

            {{-- Conditional Navigation --}}

            {{-- Info Box --}}
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle"></i>
                <strong>Tip:</strong> Je kunt op basis van het antwoord doorverwijzen naar een andere vraag, maar dit is niet verplicht.
                Als je geen doorverwijzing instelt, gaat de gebruiker gewoon door naar de volgende vraag.
            </div>

            {{-- Conditional Navigation --}}
            
            @if ($scenario->steps->count() > 0)
                <div class="form-group">
                    <label for="fork_condition">Conditie voor doorverwijzing</label>
                    <select class="form-control" id="fork_condition" name="fork_condition">
                        <option value="">Selecteer een optie</option>
                        <option value="1">Optie 1</option>
                        <option value="2">Optie 2</option>
                        <option value="3">Optie 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="previous_step_id">Link naar andere vraag</label>
                    <select class="form-control" id="fork_to_step" name="fork_to_step">
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
        <div class="form-group" id="tussenstap_div" style="display:none;">
            <label for="description">Beschrijving</label>
            <textarea class="form-control" 
                      id="description" 
                      name="description" 
                      rows="3">{{ old('description') }}</textarea>
        </div>

        {{-- Form Actions --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Voeg deze vraag toe aan het scenario</button>
            <a href="{{ route('scenarios.show', ['scenario' => $scenario->id]) }}" 
               class="btn btn-secondary">
                Terug naar scenario
            </a>
        </div>
    </form>
</div>

@include('partials.keywords-scripts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/keywords.css') }}">
@endpush

<script>
    document.getElementById('question_type_selector').addEventListener('change', function() {
        const openQuestionDiv = document.getElementById('open_question_div');
        const multipleChoiceDiv = document.getElementById('multiple_c');
        const tussenstapDiv = document.getElementById('tussenstap_div');
        const questionType = document.getElementById('question_type');

        openQuestionDiv.style.display = 'none';
        multipleChoiceDiv.style.display = 'none';
        tussenstapDiv.style.display = 'none';

        if (this.value === 'open_question') {
            openQuestionDiv.style.display = 'block';
            questionType.value = 'open_question';
        } else if (this.value === 'multiple_c') {
            multipleChoiceDiv.style.display = 'block';
            questionType.value = 'multiple_choice_question';
        }
        else if (this.value === 'tussenstap') {
            tussenstapDiv.style.display = 'block';
            questionType.value = 'tussenstap';
        }
    });

    document.getElementById('show_attachment').addEventListener('click', function() {
        const attachmentDiv = document.getElementById('attachment');
        this.style.display = 'none';
        attachmentDiv.style.display = 'block';
    });

    function addKeyword() {
        const container = document.getElementById('keywords-container');
        const div = document.createElement('div');
        div.className = 'keyword-input';
        div.innerHTML = `
            <input type="text" name="keywords[]" class="form-control" />
            <button type="button" class="btn btn-danger" onclick="removeKeyword(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function removeKeyword(button) {
        button.parentElement.remove();
    }

    // Show/hide keyword section based on question type
    document.getElementById('question_type_selector').addEventListener('change', function() {
        const keywordsSection = document.getElementById('keywords-section');
        if (this.value === 'open_question') {
            keywordsSection.style.display = 'block';
        } else {
            keywordsSection.style.display = 'none';
        }
    });
</script>
@endsection
