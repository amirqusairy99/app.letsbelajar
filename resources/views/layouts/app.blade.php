<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="LetsBelajar — collaborative study management for university students">

    <title>@yield('title', config('app.name', 'LetsBelajar'))</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
    <!-- Alpine JS for Navbar Dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-background text-foreground">
    <div class="flex min-h-screen">
        <!-- Sidebar overlay for mobile -->
        <div class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" id="sidebarOverlay"></div>

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.navbar')

            <main class="flex-1 p-4 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 flex items-center justify-between rounded-lg border border-green-500/50 bg-green-500/10 p-4 text-green-700 dark:text-green-400" id="flash-success">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="hover:opacity-70" onclick="document.getElementById('flash-success').remove()">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 flex items-center justify-between rounded-lg border border-red-500/50 bg-red-500/10 p-4 text-red-700 dark:text-red-400" id="flash-error">
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="hover:opacity-70" onclick="document.getElementById('flash-error').remove()">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                <div class="animate-in fade-in duration-300 w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) lucide.createIcons();

            // Sidebar toggle
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('translate-x-0');
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                });
            }

            // Auto-dismiss flash alerts
            ['flash-success', 'flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    setTimeout(() => {
                        el.remove();
                    }, 4000);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
