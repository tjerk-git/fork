@component('mail::message')
# Login Token for FORK

Your login token is: **{{ $token }}**

This token will expire in 7 days.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
