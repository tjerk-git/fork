@extends('layouts.front')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-md">
    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-bold tracking-tight">Toegangscode vereist</h1>
            <p class="text-sm text-muted-foreground">Voer de toegangscode in om verder te gaan</p>
        </div>

        @if (session('error'))
            <div class="rounded-md bg-destructive/15 p-4 text-sm text-destructive">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-md bg-destructive/15 p-4 text-sm text-destructive">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('verifyAccessCode', ['slug' => $scenario->slug]) }}" 
              method="POST"
              class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label for="accessCode" 
                       class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Toegangscode
                </label>
                <input type="text" 
                       id="accessCode" 
                       name="accessCode" 
                       placeholder="Voer de code in"
                       required 
                       autocomplete="off"
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
            </div>

            <button type="submit" 
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full">
                Verstuur
            </button>
        </form>
    </div>
</div>
@endsection
