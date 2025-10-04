@extends('layouts.app')

@section('title', 'Scenario bewerken')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Scenario bewerken</h1>
    </div>

    <div class="space-y-6">
        <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <form action="{{ route('scenarios.update', $scenario) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Naam
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $scenario->name) }}" 
                               class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                               required>
                        @error('name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Beschrijving
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description', $scenario->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="access_code" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Toegangscode
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   id="access_code" 
                                   name="access_code" 
                                   value="{{ old('access_code', $scenario->access_code) }}" 
                                   class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
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
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_public" 
                                   id="is_public"
                                   value="1"
                                   class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" 
                                   {{ old('is_public', $scenario->is_public) ? 'checked' : '' }}>
                            <label for="is_public" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Publiek beschikbaar
                            </label>
                        </div>
                
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('scenarios.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Annuleren
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateAccessCode() {
    const length = 8;
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    document.getElementById('access_code').value = result;
}
</script>
@endsection
