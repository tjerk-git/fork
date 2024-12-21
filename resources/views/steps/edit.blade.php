@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
        @if ($step->question_type === 'intro')
            Introductie voor {{ $scenario->name }}
        @else
            Een vraag voor {{ $scenario->name }}
        @endif
    </h1>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="text-red-800 dark:text-red-200">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $referencingSteps = $scenario->steps->filter(function($otherStep) use ($step) {
            return $otherStep->fork_to_step == $step->id;
        });
    @endphp
    
    @if($referencingSteps->count() > 0)
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <strong class="block text-blue-800 dark:text-blue-200 mb-2">Deze stap wordt gebruikt als doorverwijzing door:</strong>
            <ul class="list-disc list-inside text-blue-700 dark:text-blue-300">
                @foreach($referencingSteps as $referencingStep)
                    <li>Vraag: {{ $referencingStep->multiple_choice_question }} (wanneer optie {{ $referencingStep->fork_condition }} wordt gekozen)</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('steps.update', ['step' => $step->id, 'scenario' => $scenario->id]) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
        <input type="hidden" name="question_type" value="{{ $step->question_type }}">

        @if ($step->question_type == 'intro')
            <div class="space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                    Beschrijving
                </label>
                <textarea class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                          id="description" 
                          name="description" 
                          rows="3">{{ old('description', $step->description) }}</textarea>
            </div>
        @endif

        {{-- Attachment Section --}}
        @include('partials.show-attachment')
        @include('partials.add-attachment')

        {{-- Open Question --}}
        @if($step->question_type == 'open_question')
            <div class="space-y-2">
                <label for="open_question" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                    Een open vraag
                </label>
                <input type="text" 
                       class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                       id="open_question" 
                       name="open_question"
                       value="{{ old('open_question', $step->open_question) }}">
                
                @include('partials.keywords-section')
            </div>
        @endif

        {{-- Multiple Choice Question --}}
        @if($step->question_type == 'multiple_choice_question')
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="multiple_choice_question" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Meerkeuze vraag
                    </label>
                    <input type="text" 
                           class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                           id="multiple_choice_question" 
                           name="multiple_choice_question"
                           value="{{ old('multiple_choice_question', $step->multiple_choice_question) }}">
                </div>

                @for ($i = 1; $i <= 3; $i++)
                    <div class="space-y-2">
                        <label for="multiple_choice_option_{{ $i }}" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Optie {{ $i }}
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                               id="multiple_choice_option_{{ $i }}" 
                               name="multiple_choice_option_{{ $i }}"
                               value="{{ old('multiple_choice_option_' . $i, $step->{'multiple_choice_option_' . $i}) }}">
                    </div>
                @endfor

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
                                <option value="{{ $i }}" {{ $step->fork_condition == $i ? 'selected' : '' }}>
                                    Optie {{ $i }}
                                </option>
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
                            <option value="" {{ !$step->fork_to_step ? 'selected' : '' }}>
                                Naar volgende vraag
                            </option>
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
            </div>
        @endif

        {{-- Tussenstap --}}
        @if($step->question_type == 'tussenstap')
            <div class="space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                    Beschrijving
                </label>
                <textarea class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                          id="description" 
                          name="description" 
                          rows="3">{{ old('description', $step->description) }}</textarea>
            </div>
        @endif

        <div class="flex justify-end pt-6">
            <button type="submit" 
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                Update vraag
            </button>
        </div>
    </form>

    <div class="border-t mt-8 pt-6">
        <form action="{{ route('steps.destroy', ['step' => $step->id, 'scenario' => $scenario->id]) }}"
              method="POST" 
              onsubmit="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?');"
              class="flex justify-end">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2">
                <i class="fas fa-trash-alt mr-2"></i>
                Verwijder vraag
            </button>
        </form>
    </div>
</div>

@include('partials.keywords-scripts')
@endsection
