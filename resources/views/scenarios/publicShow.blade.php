@extends('layouts.front')

<style>
    section {
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .active {
        display: flex;
    }

    .form{
        border-radius: 10px;
        border: 1px solid var(--pico-muted-border-color);
        padding: 3rem;
        max-width:660px;
        margin: 0 auto;
    }

    footer{
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    @section('content')
    <div class="form">
        <form method="POST" action="{{ route('results.store') }}">
            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
            @csrf

            @foreach ($scenario->steps()->orderBy('order')->get() as $index => $step)
                <section data-uuid="{{ $step->order }}">
                    <h1>{{ $scenario->name }}</h1>

                    @if ($step->question_type == 'intro')
                    
                    @include('partials.attachment')

                    <p>{{ $step->description }}</p>    

                    @elseif ($step->question_type == 'open_question')
                        <div class="form-group" id="open_question_div">
                            <label for="answer_{{ $step->id }}">{{ $step->open_question }}</label>
                            <input type="text" class="form-control" id="answer_{{ $step->id }}"
                                name="answer_{{ $step->id }}" value="" placeholder="Antwoord">
                        </div>
                    @elseif ($step->question_type == 'multiple_choice_question')
                        <div class="form-group" id="multiple_c">
                            <h2>{{ $step->multiple_choice_question }}</h2>
                            
                            <fieldset>
                                <legend>Kies een antwoord</legend>
                                
                                <div class="grid">
                                    <label for="answer_{{ $step->id }}_1">
                                        <input type="radio" 
                                               id="answer_{{ $step->id }}_1" 
                                               name="answer_{{ $step->id }}"
                                               value="{{ $step->multiple_choice_option_1 }}">
                                        {{ $step->multiple_choice_option_1 }}
                                    </label>

                                    <label for="answer_{{ $step->id }}_2">
                                        <input type="radio" 
                                               id="answer_{{ $step->id }}_2" 
                                               name="answer_{{ $step->id }}"
                                               value="{{ $step->multiple_choice_option_2 }}">
                                        {{ $step->multiple_choice_option_2 }}
                                    </label>

                                    <label for="answer_{{ $step->id }}_3">
                                        <input type="radio" 
                                               id="answer_{{ $step->id }}_3" 
                                               name="answer_{{ $step->id }}"
                                               value="{{ $step->multiple_choice_option_3 }}">
                                        {{ $step->multiple_choice_option_3 }}
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                    @endif
                </section>


                @if ($loop->last)
                    <section data-uuid="{{ $step->order + 1 }}">
                        <h1>Bedankt voor het deelnemen</h1>
                        
                            <p>
                                🎉 Geweldig gedaan! Je hebt het scenario succesvol afgerond! 🎈

                              
                            </p>
               
                        <button type="submit" onClick="showConfetti()">Gegevens opsturen</button>
                    </section>
                @endif
            @endforeach

            <footer>
                <button id="prev">Vorige stap</button>
                <button id="next">Volgende stap</button>
            </footer>
        </form>
        </div>

    @endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        init();
    });

    function showConfetti() {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }

    // maybe put steps into an array so we can have multiple continuation steps.

    function skipToStep(step) {
        const finalStep = document.querySelectorAll('form section').length;

        console.log('skipping to step', step);
        // get the div with uuid step
        const stepContent = document.querySelector(`section[data-uuid="${step}"]`);

        // hide all other steps
        const sections = document.querySelectorAll('form section');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        // show the step
        stepContent.style.display = 'block';
    }

    function init() {

        // get all sections inside form element
        const sections = document.querySelectorAll('form section');
        const finalStep = document.querySelectorAll('form section').length;

        let step = 0;

        // get next and previous buttons
        const nextBtn = document.querySelector('#next');
        const prevBtn = document.querySelector('#prev');

        sections[0].style.display = 'block';

        // get the div with uuid firstStepCount
        let activeStep = document.querySelector(`section[data-uuid="${step}"]`);
        let uuid = activeStep.dataset.uuid;
        let fork_step = parseInt(activeStep.dataset.fork, 10);
        let data_condition = activeStep.dataset.condition;
        let data_input = activeStep.dataset.input;
        let data_continuation = activeStep.dataset.continuation;
        let data_input_value = '';
        let nextStep = '';

        // add click event to next button
        nextBtn.addEventListener('click', function() {
            // prevent default
            event.preventDefault();

            nextStep = document.querySelector(`section[data-uuid="${step}"]`);

            uuid = nextStep.dataset.uuid;
            fork_step = parseInt(nextStep.dataset.fork);

            data_condition = nextStep.dataset.condition;
            data_input = nextStep.dataset.input;
            data_continuation = nextStep.dataset.continuation;

            if (step === finalStep) {
                return;
            }


            if (step == nextStep.dataset.uuid && data_input !== undefined) {
                data_input_value = document.getElementById(data_input).value;
            }

            if (data_condition === data_input_value) {
                step = fork_step;
                skipToStep(fork_step);
            } else {
                step = step + 1;
                skipToStep(step);
            }
        });

        // add click event to previous button
        prevBtn.addEventListener('click', function() {
            event.preventDefault();

            activeStep = document.querySelector(`section[data-uuid="${step}"]`);

            if (activeStep.dataset.redirect !== undefined) {
                let redirectTo = parseInt(activeStep.dataset.redirect);

                if (isNaN(redirectTo)) {
                    return;
                }

                step = redirectTo;
                skipToStep(redirectTo)
            } else {
                step = step - 1;

                if (step < 0) {
                    step = 0;
                }
                skipToStep(step);
            }

        });
    }
</script>
