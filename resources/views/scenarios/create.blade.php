@extends('layouts.app')

<style>
    p {
        max-width: 600px;
    }
</style>

@section('content')
    <div class="container">
        <h1>Aanmaken nieuw scenario</h1>
        <p>Een scenario bestaat uit meerdere stappen. Elke stap kan een video, tekst of een vraag bevatten.
            Wanneer je een scenario aanmaakt zal er automatisch een introductie stap aangemaakt worden</p>

        <form action="{{ route('scenarios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Titel</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" required placeholder="Een titel">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group" id="access_code_group" style="{{ old('is_public') ? 'display:none;' : '' }}">
                <label for="access_code">Toegangscode</label>
                <input type="text" class="form-control @error('access_code') is-invalid @enderror" id="access_code"
                    name="access_code" value="{{ old('access_code') }}"
                    placeholder="Een toegangscode die het scenario kan afsluiten, mag alles zijn, niet verplicht">
                @error('access_code')
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
