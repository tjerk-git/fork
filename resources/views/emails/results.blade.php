<x-mail::message>
# Resultaten voor {{ $scenario->name }}

<x-mail::table>
Vraag | Antwoord |
| :- | :- | :- |
@foreach($lines as $line)
@if ($line->step->question_type === 'open_question'){{ $line->step->open_question }}@elseif ($line->step->question_type === 'multiple_choice_question'){{ $line->step->multiple_choice_question }}@else{{ $line->step->description }}@endif | {{ $line->value }} |
@endforeach
</x-mail::table>

</x-mail::message>
