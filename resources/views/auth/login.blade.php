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
                    <button>Verstuur login token</button>
                </form>
            @else
                <p>Check je e-mail voor je login token</p>
                <form action="{{ route('verify-token') }}" method="post">
                    @csrf
                    <div>
                        <label for="token">Login token</label>
                        <input type="text" name="token" id="token" required />
                        @error('token')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <button>Login</button>
                </form>
            @endif
        </div>
    </div>
@endsection
