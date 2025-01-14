@extends('layouts.front')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.css" />
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script defer src="{{ asset('js/scenario.js') }}"></script>

@section('content')
<div class="max-w-4xl mx-auto p-6">
    @if (session('success'))
        <div class="bg-card text-card-foreground rounded-lg shadow-lg p-8 text-center">
            <h1 class="text-3xl font-bold mb-4">🎉 Bedankt voor je deelname! 🎉</h1>
            <p class="text-lg text-muted-foreground mb-8">{{ session('success') }}</p>
            <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JxYjF1M245a3QxOTRxNnBxbXE4NHF6ZDVtN2txYmF1OWx6eCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/IwAZ6dvvvaTtdI8SD5/giphy.gif" 
                 alt="Thank you" 
                 class="max-w-[300px] mx-auto rounded-lg shadow-md">
        </div>
    @else
        <form method="POST" action="{{ route('results.store') }}" class="space-y-8" id="scenario-form">
            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
            @csrf

            @foreach ($scenario->steps()->orderBy('order')->get() as $index => $step)
                <section class="slide flex-col items-center gap-6 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-8 h-[calc(100vh-120px)] overflow-y-auto mb-16" 
                         data-slide="{{ $step->id }}" 
                         @if ($step->fork_to_step) 
                             data-condition="{{ $step->{'multiple_choice_option_' . $step->fork_condition} }}" 
                             data-fork-step="{{ $step->fork_to_step }}" 
                         @endif
                         @if ($index === 0) data-active="true" @endif>
                    
                    <h1 class="text-2xl font-bold tracking-tight">{{ $scenario->name }}</h1>

                    @if ($step->question_type == 'intro')
                        @include('partials.show-attachment')
                        <p class="text-lg text-muted-foreground max-w-2xl">{{ $step->description }}</p>    

                    
                        @if($scenario->ask_for_name)
                        <div class="w-full max-w-2xl space-y-4">
                            <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Hoe heet je?
                            </label>
          
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                                   placeholder="Je naam"
                                   required>
                        </div>
                        @endif

                    @elseif ($step->question_type == 'open_question')
                        @include('partials.show-attachment')
                        <div class="w-full max-w-2xl space-y-4">
                            <label for="answer_{{ $step->id }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                <p class="text-lg text-muted-foreground">{{ $step->open_question }}</p>
                            </label>
                            <input type="text" 
                                   id="answer_{{ $step->id }}"
                                   name="answer_{{ $step->id }}" 
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                   placeholder="Antwoord"
                                   data-keywords="{{ json_encode($step->keywords->pluck('word')) }}">
                        </div>

                    @elseif ($step->question_type == 'tussenstap')
                        @include('partials.show-attachment')
                        <p class="text-lg text-muted-foreground max-w-2xl">{{ $step->description }}</p>

                
              
                        
                    @elseif ($step->question_type == 'multiple_choice_question')
                        @include('partials.show-attachment')
                        <div class="w-full max-w-2xl space-y-4">
                            <p class="text-lg text-muted-foreground">{{ $step->multiple_choice_question }}</p>
                            
                            <div class="space-y-4">
                                <h3 class="font-medium">Kies een antwoord</h3>
                                
                                <div class="space-y-4 bg-card text-card-foreground rounded-lg border shadow-sm p-4">
                                    @for ($i = 1; $i <= 3; $i++)
                                        @if ($step->{"multiple_choice_option_$i"})
                                            <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-accent hover:text-accent-foreground transition-colors cursor-pointer">
                                                <input type="radio" 
                                                       id="answer_{{ $step->id }}_{{ $i }}" 
                                                       name="answer_{{ $step->id }}"
                                                       value="{{ $step->{"multiple_choice_option_$i"} }}"
                                                       class="h-4 w-4">
                                                <span class="text-sm">
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
                            <h1 class="text-2xl font-bold tracking-tight">Gegevens insturen</h1>
                            
                            <div class="space-y-4">
                                <p class="text-muted-foreground">
                                    Je kan nog terug om je antwoorden aan te passen, of je mag de resultaten opsturen.
                                    Tot die tijd zijn de resultaten <strong>niet</strong> opgestuurd.
                                </p>
                            
                                <p class="text-muted-foreground">
                                    Mocht je de resultaten zelf willen bewaren en inzien, vul hieronder je e-mailadres in:
                                </p>
                                
                                <div class="space-y-2">
                                    <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        E-mailadres
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           name="email" 
                                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                           placeholder="Je e-mailadres">
                                </div>

                                <button type="submit" 
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                    Gegevens opsturen
                                </button>
                            </div>
                        </div>
                    </section>
                @endif
            @endforeach

            <div class="fixed bottom-0 left-0 right-0 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg p-4">
                <div class="max-w-4xl mx-auto flex justify-between items-center">
                    <button id="prev" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Vorige stap
                    </button>
                    <button id="next" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Volgende stap
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
