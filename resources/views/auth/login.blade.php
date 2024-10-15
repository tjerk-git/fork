@extends('layouts.app', ['title' => 'FORK LOGIN'])
@section('content')
    <div>
        <div>
            <h1>FORK - Inloggen met e-mail</h1>

            @if (!session()->has('success') && !auth()->check())
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div>
                        <label for="email">E-mailadres</label>
                        <input type="email" name="email" id="email" required />
                        @error('email')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <button>Verstuur email om in te loggen</button>
                </form>
            @else
                <p>Gebruik de link in je e-mail om in te loggen</p>
            @endif

        </div>
    </div>
@endsection
