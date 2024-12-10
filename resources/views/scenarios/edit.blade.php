@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bewerk: {{ $scenario->name }}</h1>

        <form action="{{ route('scenarios.update', $scenario) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Titel</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $scenario->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
                <label for="access_code">Toegangscode (6 karakters)</label>
                <input type="text" class="form-control @error('access_code') is-invalid @enderror" id="access_code"
                    name="access_code" value="{{ old('access_code', $scenario->access_code) }}" maxlength="6">
                @error('access_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Update Scenario</button>
            <a href="{{ route('scenarios.show', $scenario) }}" class="btn btn-secondary">Terug</a>
        </form>
    </div>
@endsection
