@extends('layouts.front')

@section('content')
<div class="container">
    <article class="grid">
        <div>
            <hgroup>
                <h1>Toegangscode vereist</h1>
                <h2>Voer de toegangscode in om verder te gaan</h2>
            </hgroup>

            @if ($errors->any())
                <article aria-label="Error message" class="error">
                    {{ $errors->first() }}
                </article>
            @endif

            <form action="{{ route('verifyAccessCode', ['slug' => $scenario->slug]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="accessCode">Toegangscode</label>
                    <input type="text" 
                           id="accessCode" 
                           name="accessCode" 
                           placeholder="Voer de code in"
                           required 
                           autocomplete="off">
                </div>
                <button type="submit" class="contrast">Verstuur</button>
            </form>
        </div>
    </article>
</div>
@endsection
