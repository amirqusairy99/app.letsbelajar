<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="LetsBelajar — Sign in to manage your university assignments">

    <title>@yield('title', 'LetsBelajar')</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-background text-foreground flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="LetsBelajar Logo" class="h-12 w-auto object-contain drop-shadow-md" />
            </a>
            <h1 class="mt-6 text-2xl font-bold tracking-tight">LetsBelajar</h1>
            <p class="text-sm text-muted-foreground mt-1">Collaborative study management</p>
        </div>
        
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            {{ $slot }}
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>if (window.lucide) lucide.createIcons();</script>
</body>
</html>
