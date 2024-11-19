@extends('layouts.front')


<style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    section {
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .active {
        display: flex;
    }
</style>


@section('content')
    <form>
        @foreach ($scenario->steps()->orderBy('order')->get() as $index => $step)
            <section data-uuid="{{ $step->order }}">
                <h1>Step {{ $step->order }}:</h1>

                @if ($step->question_type === 'open')
                    <p>{{ $step->open_question }}</p>
                @else
                    <p>{{ $step->multiple_choice_question }}</p>
                @endif
            </section>
        @endforeach
        <button id="prev">Previous</button>
        <button id="next">Next</button>
    </form>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        init();
    });

    // maybe put steps into an array so we can have multiple continuation steps.

    function skipToStep(step) {
        const finalStep = document.querySelectorAll('form section').length;

        console.log('finding step', step);
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

        let step = 1;

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

                if (step < 1) {
                    step = 1;
                }
                skipToStep(step);
            }

        });
    }
</script>
