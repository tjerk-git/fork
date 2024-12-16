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
        max-width:860px;
        margin: 0 auto;
    }

    video{
        padding:1rem;
    }
    
    legend{
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .attachment_container{
        display: flex;
        justify-content: center;
        margin: 1rem 0;
    }

    p{
        margin: 1rem 0;
        text-align: left;
        word-break: break-word;
        max-width: 600px;
    }

    footer{
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .radio_options{
        border: 1px solid var(--pico-muted-border-color);
        border-radius: 10px;
        padding: 1rem;
        display:flex;
        flex-direction: column;
        gap: 1rem;
    }

    .thank-you-container {
        text-align: center;
        padding: 2rem;
    }

    .thank-you-container h1 {
        margin-bottom: 1rem;
        font-size: 2rem;
    }

    .thank-you-container p {
        text-align: center;
        margin: 1rem auto;
    }
</style>



<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    @section('content')

    <div id="debug"></div>
<div id="currentArray"></div>


    


    <div class="form">
        @if (session('success'))
            <div class="thank-you-container">
                <h1>🎉 Bedankt voor je deelname! 🎉</h1>
                <p>{{ session('success') }}</p>
                <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JxYjF1M245a3QxOTRxNnBxbXE4NHF6ZDVtN2txYmF1OWx6eCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/IwAZ6dvvvaTtdI8SD5/giphy.gif" 
                     alt="Thank you" 
                     style="max-width: 300px; margin: 2rem auto; display: block;">
            </div>
        @else
            <form method="POST" action="{{ route('results.store') }}">
            <input type="hidden" name="scenario_id" value="{{ $scenario->id }}">
            @csrf

            @foreach ($scenario->steps()->orderBy('order')->get() as $index => $step)
            <section class="slide" data-slide="{{ $step->id }}" @if ($step->fork_to_step) data-condition="{{ $step->{'multiple_choice_option_' . $step->fork_condition} }}" data-forkStep="{{ $step->fork_to_step }}" @endif>
                    <h1>{{ $scenario->name }}</h1>

                    @if ($step->question_type == 'intro')
                    
                   
                    @include('partials.attachment')
                    

                    <p>{{ $step->description }}</p>    

                    @elseif ($step->question_type == 'open_question')
                        @include('partials.attachment')
                        <div class="form-group" id="open_question_div">
                            <label for="answer_{{ $step->id }}">{{ $step->open_question }}</label>
                            <input type="text" class="form-control" id="answer_{{ $step->id }}"
                                name="answer_{{ $step->id }}" value="" placeholder="Antwoord">
                        </div>

                    @elseif ($step->question_type == 'tussenstap')
                        @include('partials.attachment')
                        <div class="form-group" id="tussenstap_div">
                            <p>{{ $step->description }}</p>
                        </div>
                    
                    @elseif ($step->question_type == 'multiple_choice_question')
                        @include('partials.attachment')
                        <div class="form-group" id="multiple_c">
                            <p>{{ $step->multiple_choice_question }}</p>
                            
                            <fieldset>
                                <legend>Kies een antwoord</legend>
                                
                                <div class="radio_options">
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

                    @php
                    $lastSlideNumber = $step->id + 2;
                    @endphp
                    <section class="slide" data-slide="{{ $lastSlideNumber }}">
                        <h1>Gegevens insturen</h1>
                        
                            <p>
                                Je kan nog terug om je antwoorden aan te passen, of je mag de resultaten opsturen.
                                Tot die tijd zijn de resultaten <strong>niet</strong> opgestuurd.
                            </p>
                        
                            <p>Mocht je de resultaten zelf willen bewaren en inzien, vul hieronder je e-mailadres in:</p>
                            <div class="form-group" id="email">
                                <label for="email">E-mailadres</label>
                                <input type="email" class="form-control" id="email"
                                    name="email" value="" placeholder="Je e-mailadres">
                            </div>
               
                        <button type="submit">Gegevens opsturen</button>
                    </section>
                @endif
            @endforeach

            <footer>
                <button id="prev">Vorige stap</button>
                <button id="next">Volgende stap</button>
            </footer>
        </form>
        @endif
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


function init(){

    const debug = document.getElementById('debug');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    const currentArray = document.getElementById("currentArray")

    let index = 0;
    const stepDivs = document.querySelectorAll('.slide');
    let currentstep = '';

    // create the steps array from the stepDivs data-slide attribute
    steps = Array.from(stepDivs).map((stepDiv) => {
        return stepDiv.getAttribute('data-slide');
    });

    // //     // Initialize display
    // debug.innerHTML = steps[index];
    // currentArray.innerHTML = `Current array: ${steps.join(', ')}`;

    document.querySelector(`[data-slide="${steps[index]}"]`).style.display = "block";


  next.addEventListener('click', () => {

    // prevent default
    event.preventDefault();

    if (index < steps.length - 1) {

        index++;
        
        // if the currentstep is the last one show confetti
        if (index === steps.length - 1) {
            showConfetti();

            // hide the next button
            next.style.display = "none";
        }

        // debug.innerHTML = steps[index];
        // currentArray.innerHTML = `Current array: ${steps.join(', ')}`;

        stepDivs.forEach((stepDiv) => {
            stepDiv.style.display = "none";
        });

        currentStepDiv =  document.querySelector(`[data-slide="${steps[index]}"]`);
        document.querySelector(`[data-slide="${steps[index]}"]`).style.display = "block";

        // check if the current step has the data attribute fork
        if (currentStepDiv.getAttribute('data-forkStep')) {
          next.style.display = "none";

          let condition = currentStepDiv.getAttribute('data-condition');
          let forkStep = currentStepDiv.getAttribute('data-forkStep');
          
          currentStepDiv.querySelectorAll('input[type="radio"]').forEach((radio) => {
            radio.checked = false;
          });

          // select all radiobuttons listen for onchange event
          currentStepDiv.querySelectorAll('input[type="radio"]').forEach((radio) => {
           
            radio.addEventListener('change', () => {
              next.style.display = "block";
              
              if (radio.value !== condition) {
               
                  removeNumbers([forkStep]);

                  console.log(`removed ${forkStep}`);
                
              } else {
                addNumbers([forkStep])
                console.log(`added ${forkStep}`);
              }
            });
          });
        }     
    }
});

prev.addEventListener('click', () => {
    event.preventDefault();

    next.style.display = "block";

    if (index > 0) {
        index--;


      stepDivs.forEach((stepDiv) => {
          stepDiv.style.display = "none";
      });

      document.querySelector(`[data-slide="${steps[index]}"]`).style.display = "block";

        // debug.innerHTML = steps[index];
        // currentArray.innerHTML = `Current array: ${steps.join(', ')}`;
    }
});

    function removeNumbers(numbersToRemove) {
        steps = steps.filter(num => !numbersToRemove.includes(num));
        index = Math.min(index, steps.length - 1); // Adjust index if needed
        
        // // Update display
        // debug.innerHTML = steps[index];
        // currentArray.innerHTML = `Current array: ${steps.join(', ')}`;
    }

    function addNumbers(numbersToAdd) {

        // if steps already contains numbersToAdd, do nothing
        if (steps.some(num => numbersToAdd.includes(num))) {
            return;
        }

        // add the numbersToAdd at the current index
        steps.splice(index + 1, 0, ...numbersToAdd);

        // Update display
        // debug.innerHTML = steps[index];
        // currentArray.innerHTML = `Current array: ${steps.join(', ')}`;
    }

}
</script>
