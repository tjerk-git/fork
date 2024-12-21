<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FORK' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-background font-sans antialiased">
    <div class="relative flex min-h-screen flex-col">
        <header class="supports-backdrop-blur:bg-background/60 sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container flex h-14 items-center">
                <!-- Add your header content here -->
            </div>
        </header>

        <main class="flex-1 container mx-auto py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
