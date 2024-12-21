{{-- Keywords Section --}}
<div class="space-y-4">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
            Sleutelwoorden
        </label>
        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <p>Wanneer een sleutelwoord gevonden wordt in een antwoord, krijgt de gebruiker een extra pop-up met daarin positieve feedback.</p>
            <p>Voeg elk woord los toe</p>
        </div>
    </div>

    <div id="keywords-container" class="space-y-2">
        @if(isset($step) && $step->keywords)
            @foreach($step->keywords as $keyword)
                <div class="flex items-center gap-2">
                    <input type="text" 
                           name="keywords[]" 
                           value="{{ $keyword->word }}" 
                           class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                           placeholder="Sleutelwoord" />
                    <button type="button" 
                            class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-red-600 hover:bg-red-700 active:bg-red-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            onclick="removeKeyword(this)">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            @endforeach
        @elseif(old('keywords'))
            @foreach(old('keywords') as $keyword)
                <div class="flex items-center gap-2">
                    <input type="text" 
                           name="keywords[]" 
                           value="{{ $keyword }}" 
                           class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                           placeholder="Sleutelwoord" />
                    <button type="button" 
                            class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-red-600 hover:bg-red-700 active:bg-red-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            onclick="removeKeyword(this)">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <button type="button" 
            onclick="addKeyword()" 
            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
        </svg>
        Voeg sleutelwoord toe
    </button>
</div>
