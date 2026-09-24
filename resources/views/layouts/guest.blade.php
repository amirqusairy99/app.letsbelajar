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
    <div class="w-full max-w-[480px]">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center gap-2">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-lg shadow-primary/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                    </svg>
                </div>
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
