@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Een vraag voor {{ $scenario->name }}</h1>


        <form action="{{ route('steps.update', ['step' => $step->id, 'scenario' => $scenario->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')


            @if (!empty($step->open_question))
                <div class="form-group
                @error('open_question') is-invalid @enderror">
                    <label for="open_question">Een open vraag</label>
                    <input type="text" class="form-control" id="open_question" name="open_question"
                        value="{{ old('open_question', $step->open_question) }}">
                    @error('open_question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="form-group
                @error('multiple_choice_question') is-invalid @enderror">
                    <label for="multiple_choice_question">Een meerkeuze vraag:</label>
                    <input type="text" class="form-control" id="multiple_choice_question" name="multiple_choice_question"
                        value="{{ old('multiple_choice_question', $step->multiple_choice_question) }}">
                    @error('multiple_choice_question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif


            <button type="submit" class="btn btn-primary">Vraag opslaan</button>
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
