@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>New Step for {{ $scenario->name }}</h1>

        <div class="form-group">
            <label for="question_type">Vraag:</label>
            <select class="form-control" id="question_type" name="question_type">
                <option value="" selected>Selecteer een type vraag</option>
                <option value="open_question">Open vraag</option>
                <option value="multiple_c">Meerkeuze vraag</option>
            </select>
        </div>

        <script>
            document.getElementById('question_type').addEventListener('change', function() {
                var openQuestionDiv = document.getElementById('open_question');
                if (this.value === 'open_question') {
                    openQuestionDiv.style.display = 'block';
                } else {
                    openQuestionDiv.style.display = 'none';
                }

                var multipleChoiceDiv = document.getElementById('multiple_c');
                if (this.value === 'multiple_c') {
                    multipleChoiceDiv.style.display = 'block';
                } else {
                    multipleChoiceDiv.style.display = 'none';
                }
            });

            // Trigger change event on page load to set the initial state
            document.getElementById('question_type').dispatchEvent(new Event('change'));
        </script>

        <form action="{{ route('steps.store', ['scenario' => $scenario->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group" id="attachment" style="display:none;">
                <label for="attachment">Video of afbeelding</label>
                <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment"
                    name="attachment">
                @error('attachment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group" id="open_question" style="display:none;">
                <label for="open_question">Een open vraag</label>
                <input type="text" class="form-control" id="open_question" name="open_question"
                    value="{{ old('open_question') }}">
            </div>

            <div class="form-group" id="multiple_c" style="display:none;">

                <label for="multiple_choice_question">Een meerkeuze vraag:</label>
                <input type="text" class="form-control" id="multiple_choice_question" name="multiple_choice_question"
                    value="{{ old('multiple_choice_question') }}">

                <label for="multiple_choice_option_1">Optie 1</label>
                <input type="text" class="form-control" id="option_1" name="multiple_choice_option_1"
                    value="{{ old('multiple_choice_option_1') }}">

                <label for="multiple_choice_option_2">Optie 2</label>
                <input type="text" class="form-control" id="option_2" name="multiple_choice_option_2"
                    value="{{ old('multiple_choice_option_2') }}">
                <label for="multiple_choice_option_3">Optie 3</label>
                <input type="text" class="form-control" id="option_3" name="multiple_choice_option_3"
                    value="{{ old('multiple_choice_option_3') }}">
            </div>

            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">


            <div class="form-group">
                <button type="button" class="btn btn-link" id="show_attachment">
                    <i class="fas fa-plus"></i> Voeg een video of afbeelding toe
                </button>
            </div>

            @if ($scenario->steps->count() > 0)
                <div class="form-group">
                    <label for="previous_step_id">Link naar andere stap</label>
                    <select class="form-control" id="fork_to_step" name="fork_to_step">
                        <option value="" selected>Geen</option>
                        @foreach ($scenario->steps as $step)
                            <option value="{{ $step->id }}">{{ $step->type }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <script>
                document.getElementById('show_attachment').addEventListener('click', function() {
                    const attachmentDiv = document.getElementById('attachment');
                    const attachmentInput = document.getElementById('show_attachment');

                    attachmentInput.style.display = 'none';

                    attachmentDiv.style.display = 'block';
                });
            </script>



            <button type="submit" class="btn btn-primary">Voeg deze vraag toe aan het scenario</button>
            <a href="{{ route('scenarios.show', ['scenario' => $scenario->id]) }}" class="btn btn-secondary">Terug naar
                scenario</a>
        </form>
    </div>

    @push('styles')
        <style>
            video {
                max-width: 600px;
            }
        </style>
    @endpush
@endsection
