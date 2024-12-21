@extends('layouts.front')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.css" />
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script defer src="{{ asset('js/scenario.js') }}"></script>

@section('content')
<div class="max-w-4xl mx-auto p-6">
    @if (session('success'))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">🎉 Bedankt voor je deelname! 🎉</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">{{ session('success') }}</p>
            <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JxYjF1M245a3QxOTRxNnBxbXE4NHF6ZDVtN2txYmF1OWx6eCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/IwAZ6dvvvaTtdI8SD5/giphy.gif" 
                 alt="Thank you" 
                 class="max-w-[300px] mx-auto rounded-lg shadow-md">
        </div>
    @else
        <form method="POST" action="{{ route('results.store') }}" class="space-y-8">
            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
            @csrf

            @foreach ($scenario->steps()->orderBy('order')->get() as $index => $step)
                <section class="slide hidden data-[active=true]:flex flex-col items-center gap-6" 
                         data-slide="{{ $step->id }}" 
                         @if ($step->fork_to_step) 
                             data-condition="{{ $step->{'multiple_choice_option_' . $step->fork_condition} }}" 
                             data-fork-step="{{ $step->fork_to_step }}" 
                         @endif
                         @if ($index === 0) data-active="true" @endif>
                    
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $scenario->name }}</h1>

                    @if ($step->question_type == 'intro')
                        @include('partials.show-attachment')
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl">{{ $step->description }}</p>    

                    @elseif ($step->question_type == 'open_question')
                        @include('partials.show-attachment')
                        <div class="w-full max-w-2xl space-y-4">
                            <label for="answer_{{ $step->id }}" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $step->open_question }}
                            </label>
                            <input type="text" 
                                   id="answer_{{ $step->id }}"
                                   name="answer_{{ $step->id }}" 
                                   class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                   placeholder="Antwoord"
                                   data-keywords="{{ json_encode($step->keywords->pluck('word')) }}">
                        </div>

                    @elseif ($step->question_type == 'tussenstap')
                        @include('partials.show-attachment')
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl">{{ $step->description }}</p>
                    
                    @elseif ($step->question_type == 'multiple_choice_question')
                        @include('partials.show-attachment')
                        <div class="w-full max-w-2xl space-y-4">
                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $step->multiple_choice_question }}</p>
                            
                            <div class="space-y-4">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">Kies een antwoord</h3>
                                
                                <div class="space-y-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                    @for ($i = 1; $i <= 3; $i++)
                                        @if ($step->{"multiple_choice_option_$i"})
                                            <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                                <input type="radio" 
                                                       id="answer_{{ $step->id }}_{{ $i }}" 
                                                       name="answer_{{ $step->id }}"
                                                       value="{{ $step->{"multiple_choice_option_$i"} }}"
                                                       class="h-4 w-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $step->{"multiple_choice_option_$i"} }}
                                                </span>
                                            </label>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif
                </section>

                @if ($loop->last)
                    <section class="slide hidden" data-slide="{{ $step->id + 2 }}">
                        <div class="w-full max-w-2xl space-y-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Gegevens insturen</h1>
                            
                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Je kan nog terug om je antwoorden aan te passen, of je mag de resultaten opsturen.
                                    Tot die tijd zijn de resultaten <strong>niet</strong> opgestuurd.
                                </p>
                            
                                <p class="text-gray-600 dark:text-gray-400">
                                    Mocht je de resultaten zelf willen bewaren en inzien, vul hieronder je e-mailadres in:
                                </p>
                                
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                        E-mailadres
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           name="email" 
                                           class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                           placeholder="Je e-mailadres">
                                </div>

                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Gegevens opsturen
                                </button>
                            </div>
                        </div>
                    </section>
                @endif
            @endforeach

            <div class="flex justify-between mt-8">
                <button id="prev" 
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Vorige stap
                </button>
                <button id="next"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Volgende stap
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
