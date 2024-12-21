<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FORK' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-background font-sans antialiased">
    <header class="supports-backdrop-blur:bg-background/60 sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
        <div class="container flex h-14 items-center justify-between">
            <a href="{{ route('scenarios.index') }}" class="flex items-center space-x-2 text-lg font-semibold">
                <i class="fa-solid fa-code-branch text-primary"></i>
                <span>FORK</span>
            </a>
            <div>
                @if (Auth::check())
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-muted-foreground">{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
                        <a href="{{ route('logout') }}" class="text-primary hover:text-primary/80">Uitloggen</a>
                    </div>
                @endif
            </div>
        </div>
    </header>
   
    <div class="flex min-h-[calc(100vh-3.5rem)]">
        <aside class="w-64 border-r bg-muted/30">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('scenarios.index') }}" 
                           class="flex items-center rounded-lg px-4 py-2 text-sm {{ request()->routeIs('scenarios.*') ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted' }}">
                            Scenarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('results.index') }}" 
                           class="flex items-center rounded-lg px-4 py-2 text-sm {{ request()->routeIs('results.*') ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted' }}">
                            Resultaten
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" 
                           class="flex items-center rounded-lg px-4 py-2 text-sm {{ request()->routeIs('users.*') ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted' }}">
                            Gebruikers
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="flex-1 overflow-x-auto p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
