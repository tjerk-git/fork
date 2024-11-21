@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create New Scenario</h1>

        <form action="{{ route('scenarios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Titel</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Omschrijving</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                    rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            <div class="form-group" id="access_code_group" style="{{ old('is_public') ? 'display:none;' : '' }}">
                <label for="access_code">Toegangscode</label>
                <input type="text" class="form-control @error('access_code') is-invalid @enderror" id="access_code"
                    name="access_code" value="{{ old('access_code') }}">
                @error('access_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="attachment">Video of afbeelding</label>
                <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment"
                    name="attachment">
                @error('attachment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Aanmaken</button>
            <a href="{{ route('scenarios.index') }}" class="btn btn-secondary">Afsluiten</a>
        </form>
    </div>

    @push('styles')
        <style>
            video {
                max-width: 600px;
            }
        </style>
    @endpush
@endsection
