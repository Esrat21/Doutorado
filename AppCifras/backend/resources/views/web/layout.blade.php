<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'App Cifras')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=exo-2:400,500,600,700|orbitron:600,700" rel="stylesheet" />
    <script>
        (function() {
            var t = localStorage.getItem('theme');
            if (t === 'light') document.documentElement.classList.remove('dark');
            else document.documentElement.classList.add('dark');
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        exo: ['"Exo 2"', 'sans-serif'],
                    },
                    colors: {
                        space: {
                            100: '#e2e8f0',
                            200: '#cbd5e1',
                            300: '#94a3b8',
                            400: '#64748b',
                            500: '#7c3aed',
                            600: '#4c1d95',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.store('theme', {
                dark: document.documentElement.classList.contains('dark'),
                toggle() {
                    this.dark = !this.dark;
                    document.documentElement.classList.toggle('dark', this.dark);
                    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                }
            });
            Alpine.store('sidebar', {
                open: false,
                toggle() { this.open = !this.open; },
                close() { this.open = false; }
            });
        });
    </script>
    <style>[x-cloak]{display:none!important}</style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-50 dark:bg-space-950 font-exo text-gray-800 dark:text-space-200 antialiased">
    {{-- Gradiente de fundo (claro e escuro) --}}
    <div class="fixed inset-0 pointer-events-none -z-10 bg-gradient-to-b from-gray-50 to-white dark:from-space-950 dark:to-space-900 dark:bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(124,58,237,0.12),transparent)]"></div>

    <div class="flex min-h-screen w-full">
        {{-- Sidebar global: 20% da tela no desktop. Na página da música não aparece no desktop (ela tem sidebar própria). --}}
        <div class="{{ request()->routeIs('web.musicas.show') ? 'lg:hidden' : 'w-0 flex-none overflow-visible lg:w-1/5 lg:min-w-0' }}">
            @include('web.components.sidebar')
        </div>

        {{-- Área de conteúdo (80% no desktop): navbar no topo ao lado da sidebar, depois o restante — navbar no fluxo, não sobressai --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-0">
            @include('web.components.navbar')

            <main class="flex-1 w-full min-h-0 flex flex-col px-3 sm:px-4 lg:px-6 py-4 lg:py-6 overflow-auto">
                <div class="flex-1 flex flex-col min-h-0 w-full {{ request()->routeIs('web.musicas.show') ? 'max-w-full' : 'max-w-7xl mx-auto' }}">
                    @if(session('success'))
                        <div class="mb-4 flex items-center p-4 rounded-xl bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700/50 text-green-800 dark:text-green-200 text-sm" role="alert">
                            <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="ml-3">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 flex items-center p-4 rounded-xl bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-700/50 text-red-800 dark:text-red-200 text-sm" role="alert">
                            <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span class="ml-3">{{ session('error') }}</span>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
