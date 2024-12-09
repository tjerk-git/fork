<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FORK' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <header>
        <div>
            @if (Auth::check())
                <div>
                    Welkom: {{ Auth::user()->email }}
                    <a href="{{ route('logout') }}">Uitloggen</a>
                </div>
            @endif
        </div>
    </header>
   
    <div class="layout">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="{{ route('scenarios.index') }}" class="{{ request()->routeIs('scenarios.index') ? 'secondary' : '' }}">Scenarios</a></li>
                  <li><a href="{{ route('results.index') }}" class="{{ request()->routeIs('results.*') ? 'secondary' : '' }}">Resultaten</a></li>
                  <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'secondary' : '' }}">Gebruikers</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <style>
        .layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: calc(100vh - 80px);
        }
        .sidebar {
            padding: 20px;
            border-right: 1px solid var(--pico-muted-border-color);
            background: var(--pico-background-alt);
        }
        .main-content {
            padding: 20px;
            background: var(--pico-background);
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .layout {
                grid-template-columns: 1fr;
            }
            .sidebar {
                display: none;
            }
        }
    </style>
   
</body>

</html>
