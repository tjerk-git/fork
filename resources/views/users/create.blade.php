@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Nieuwe gebruiker</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid">
                <label for="email">
                    Email
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </label>

                <label for="name">
                    Naam
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </label>

            </div>

            <div style="margin-top: 20px;">
                <button type="submit">Opslaan</button>
                <a href="{{ route('users.index') }}" class="outline" role="button">Annuleren</a>
            </div>
        </form>
    </div>
@endsection