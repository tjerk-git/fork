@component('mail::message')
Inloggen kan je via deze link:

@component('mail::button', ['url' => $url])
Inloggen
@endcomponent

Als de knop niet werkt, kan je ook deze link gebruiken: <br>
{{ $url }}

Deze link is verzonden op {{ now()->format('d-m-Y') }} om {{ now()->format('H:i') }}.
@endcomponent
