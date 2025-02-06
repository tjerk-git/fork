@extends('layouts.app')

<style>
    p {
        max-width: 600px;
    }
</style>

@section('content')
<div class="space-y-6">
    <div class="space-y-2">
        <h1 class="text-3xl font-bold tracking-tight">Aanmaken nieuw scenario</h1>
        <p class="text-muted-foreground max-w-[600px]">
            Een scenario bestaat uit meerdere stappen. Elke stap kan een video, tekst of een vraag bevatten.
            Wanneer je een scenario aanmaakt zal er automatisch een introductie stap aangemaakt worden
        </p>
    </div>

    <form action="{{ route('scenarios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="space-y-4">
            <div class="space-y-2">
                <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Titel
                </label>
                <input 
                    type="text" 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 @error('name') border-destructive @enderror" 
                    id="name" 
                    name="name"
                    value="{{ old('name') }}" 
                    required 
                    placeholder="Een titel"
                >
                @error('name')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                    Beschrijving
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" 
                           id="ask_for_name" 
                           name="ask_for_name" 
                           class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                           {{ old('ask_for_name', true) ? 'checked' : '' }}>
                    <label for="ask_for_name" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        Vraag om naam
                    </label>
                </div>
                <p class="text-sm text-muted-foreground">
                    Wanneer ingeschakeld wordt er aan het begin van het scenario gevraagd om een naam, deze is vervolgens in het scenario te gebruiken met [[naam]]
                </p>
            </div>

            <div class="space-y-2" id="access_code_group" style="{{ old('is_public') ? 'display:none;' : '' }}">
                <label for="access_code" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Toegangscode
                </label>
                <div class="flex items-center gap-2">
                    <input 
                        type="text" 
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 @error('access_code') border-destructive @enderror" 
                        id="access_code" 
                        name="access_code"
                        value="{{ old('access_code') }}" 
                        placeholder="Een toegangscode die het scenario kan afsluiten, mag alles zijn, niet verplicht"
                    >
                    <button type="button" 
                            onclick="generateAccessCode()" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Genereer
                    </button>
                </div>
                @error('access_code')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="is_public" 
                           {{ old('is_public') ? 'checked' : '' }}>
    
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Publiek beschikbaar</span>
                </label>
                @error('is_public')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                Opslaan
            </button>
            <a href="{{ route('scenarios.index') }}" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                Annuleren
            </a>
        </div>
    </form>
</div>

<script>
function generateAccessCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('access_code').value = code;
}
</script>
@endsection

@push('styles')
    <style>
        video {
            max-width: 600px;
        }
    </style>
@endpush
