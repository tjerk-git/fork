@extends('layouts.app', ['title' => 'FORK LOGIN'])
@section('content')
    <div class="flex min-h-[calc(100vh-10rem)] items-center justify-center">
        <div class="mx-auto w-full max-w-md space-y-6 rounded-lg border bg-card p-6 shadow-sm">
            <div class="space-y-2 text-center">
                <h1 class="text-2xl font-semibold tracking-tight">FORK - Inloggen met e-mail</h1>
                <p class="text-sm text-muted-foreground">Vul je e-mailadres in om een login token te ontvangen</p>
            </div>

            @if (!session()->has('success') && !auth()->check())
                <form action="{{ route('login') }}" method="post" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            E-mailadres
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                            required 
                        />
                        @error('email')
                            <p class="text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                        Verstuur login token
                    </button>
                </form>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-muted-foreground">Check je e-mail voor je login token</p>
                    <form action="{{ route('verify-token') }}" method="post" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label for="token" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Login token
                            </label>
                            <input 
                                type="text" 
                                name="token" 
                                id="token"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                                required 
                            />
                            @error('token')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            Login
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
