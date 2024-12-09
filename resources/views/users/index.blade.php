@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Gebruikers</h1>
            <a href="{{ route('users.create') }}" class="outline" role="button">
                <i class="fas fa-plus"></i> Nieuwe gebruiker
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('users.edit', $user) }}" class="outline" role="button">
                                    <i class="fas fa-edit"></i> Bewerk
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="outline secondary" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                                        <i class="fas fa-trash"></i> Verwijder
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
