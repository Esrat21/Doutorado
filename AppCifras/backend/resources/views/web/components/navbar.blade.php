{{-- Navbar: logo, busca, nav links, tema, usuário. Mobile: hamburger abre sidebar. --}}
<nav class="border-b border-gray-200 dark:border-space-700/50 bg-white dark:bg-space-900/95 shrink-0"
     x-data="{ mobileOpen: false, searchOpen: false }"
     @keydown.escape.window="searchOpen = false; mobileOpen = false">
    <div class="w-full px-3 sm:px-4 lg:px-6">
        <div class="flex items-center justify-between h-14 lg:h-16 gap-3">
            {{-- Logo + menu móvel --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" @click="$store.sidebar.toggle()" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-space-400 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-700 dark:hover:text-space-200 transition-colors" aria-label="Abrir menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('web.home') }}" class="font-orbitron text-lg lg:text-xl font-bold text-gray-900 dark:text-white tracking-tight">App Cifras</a>
            </div>

            {{-- Busca (desktop: barra; mobile: ícone que expande) --}}
            <div class="flex-1 max-w-xl mx-2">
                <form action="{{ route('web.busca') }}" method="GET" class="relative" role="search">
                    <label for="navbar-search" class="sr-only">Buscar músicas ou artistas</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-space-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search"
                               id="navbar-search"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Buscar músicas ou artistas..."
                               class="w-full h-10 pl-10 pr-4 rounded-xl border border-gray-200 dark:border-space-600 bg-gray-50 dark:bg-space-800 text-gray-900 dark:text-space-100 placeholder-gray-500 dark:placeholder-space-500 focus:ring-2 focus:ring-space-500 focus:border-transparent text-sm transition-colors">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 lg:hidden p-1.5 rounded-lg text-gray-500 dark:text-space-400 hover:bg-gray-200 dark:hover:bg-space-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Desktop: nav links + tema + usuário --}}
            <div class="hidden md:flex items-center gap-1 shrink-0">
                <a href="{{ route('web.home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('web.home') ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white' }}">Início</a>
                <a href="{{ route('web.artistas.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('web.artistas.*') ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white' }}">Artistas</a>
                <a href="{{ route('web.musicas.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('web.musicas.*') ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white' }}">Músicas</a>
                @auth
                <a href="{{ route('web.playlists.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('web.playlists.*') ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white' }}">Playlists</a>
                @endauth

                {{-- Tema --}}
                <button type="button"
                        @click="$store.theme.toggle()"
                        class="p-2 rounded-lg text-gray-500 dark:text-space-400 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-700 dark:hover:text-space-200 transition-colors"
                        :aria-label="$store.theme.dark ? 'Ativar modo claro' : 'Ativar modo escuro'">
                    <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                @auth
                <div class="relative ml-1" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white text-sm font-medium transition-colors min-w-0 max-w-[180px]">
                        <span class="w-8 h-8 rounded-full bg-space-500/20 dark:bg-space-500/30 flex items-center justify-center text-space-600 dark:text-space-400 font-orbitron text-xs shrink-0"> {{ strtoupper(mb_substr(Auth::user()->name ?? Auth::user()->email ?? 'U', 0, 1)) }}</span>
                        <span class="truncate hidden sm:inline">{{ Auth::user()->name ?? Auth::user()->email }}</span>
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         class="absolute right-0 mt-1 w-56 rounded-xl border border-gray-200 dark:border-space-600 bg-white dark:bg-space-800 py-1 shadow-lg z-50">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-space-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-space-100 truncate">{{ Auth::user()->name ?? 'Usuário' }}</p>
                            <p class="text-xs text-gray-500 dark:text-space-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('web.playlists.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-space-300 hover:bg-gray-50 dark:hover:bg-space-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Minhas playlists
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="ml-1 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-white transition-colors">Entrar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
