<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FORK' }}</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>

<body>
    <header>
        <div>
            @if (Auth::check())
                <div>
                    <h1>Welkom: {{ Auth::user()->email }}</h1>
                    <a href="{{ route('logout') }}">Uitloggen</a>
                </div>
            @endif
        </div>
    </header>
    <main class="container">
        @yield('content')
    </main>
</body>

</html>
