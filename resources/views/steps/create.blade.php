@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
        Nieuwe vraag voor: {{ $scenario->name }}
    </h1>

    <form action="{{ route('steps.store', ['scenario' => $scenario->id]) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
        <input type="hidden" name="question_type" id="question_type">

        {{-- Question Type Selector --}}
        <div class="space-y-2">
            <label for="question_type_selector" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                Type vraag:
            </label>
            <select class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                    id="question_type_selector" 
                    name="question_type_selector">
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
        <div class="space-y-2" id="open_question" style="display:none;">
            <label for="open_question" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                Een open vraag
            </label>
            <input type="text" 
                   class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                   id="open_question" 
                   name="open_question"
                   value="{{ old('open_question') }}">
            @include('partials.keywords-section')
        </div>

        {{-- Multiple Choice Question --}}
        <div class="space-y-6" id="multiple_c" style="display:none;">
            <div class="space-y-2">
                <label for="multiple_choice_question" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                    Een meerkeuze vraag:
                </label>
                <input type="text" 
                       class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                       id="multiple_choice_question" 
                       name="multiple_choice_question"
                       value="{{ old('multiple_choice_question') }}">
            </div>

            @for ($i = 1; $i <= 3; $i++)
                <div class="space-y-2">
                    <label for="option_{{ $i }}" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Optie {{ $i }}
                    </label>
                    <input type="text" 
                           class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                           id="option_{{ $i }}" 
                           name="multiple_choice_option_{{ $i }}"
                           value="{{ old('multiple_choice_option_' . $i) }}">
                </div>
            @endfor

            {{-- Info Box --}}
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex space-x-2">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1 text-sm text-blue-700 dark:text-blue-300">
                        <p><strong>Tip:</strong> Je kunt op basis van het antwoord doorverwijzen naar een andere vraag, maar dit is niet verplicht.
                        Als je geen doorverwijzing instelt, gaat de gebruiker gewoon door naar de volgende vraag.</p>
                    </div>
                </div>
            </div>

            {{-- Conditional Navigation --}}
            @if ($scenario->steps->count() > 0)
                <div class="space-y-2">
                    <label for="fork_condition" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Conditie voor doorverwijzing
                    </label>
                    <select class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                            id="fork_condition" 
                            name="fork_condition">
                        <option value="">Selecteer een optie</option>
                        @for ($i = 1; $i <= 3; $i++)
                            <option value="{{ $i }}">Optie {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="fork_to_step" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Link naar andere vraag
                    </label>
                    <select class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
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
            <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                Beschrijving
            </label>
            <textarea class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                      id="description" 
                      name="description" 
                      rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-between items-center pt-6">
            <button type="submit" 
                    class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                Voeg vraag toe
            </button>

            <a href="{{ route('scenarios.show', ['scenario' => $scenario->id]) }}" 
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Terug naar scenario
            </a>
        </div>
    </form>
</div>

@include('partials.keywords-scripts')
@endsection
