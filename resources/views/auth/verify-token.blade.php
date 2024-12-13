@extends('layouts.app', ['title' => 'FORK VERIFY'])
@section('content')
    <div>
        <div>
            <h1>FORK - Voer je login token in</h1>

            <form action="{{ route('verify-token') }}" method="post">
                @csrf
                <div>
                    <label for="email">E-mailadres</label>
                    <input type="email" name="email" id="email" required />
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="token">Login Token</label>
                    <input type="text" name="token" id="token" required minlength="6" maxlength="6" pattern="[0-9]{6}" />
                    @error('token')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                
                <button>Login</button>
            </form>
        </div>
    </div>
@endsection
