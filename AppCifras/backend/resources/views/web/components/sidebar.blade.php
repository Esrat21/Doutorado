{{-- Sidebar: navegação, ações rápidas, playlists (auth), tema. Desktop: coluna fixa. Mobile: drawer overlay. --}}
@php
    $playlistsSidebar = auth()->user()?->playlists()->orderBy('nome')->limit(15)->get() ?? collect();
@endphp
<div id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-30 w-72 lg:w-full flex flex-col border-r border-gray-200 dark:border-space-700/50 bg-white dark:bg-space-900 transform transition-transform duration-300 ease-out lg:transform-none lg:translate-x-0 shrink-0 h-full"
    :class="{ '-translate-x-full': !$store.sidebar.open, 'translate-x-0': $store.sidebar.open }" x-data
    @click.outside="$store.sidebar.close()">
    {{-- Overlay móvel --}}
    <div class="fixed inset-0 bg-black/50 lg:hidden z-[-1]"
        :class="{ 'opacity-100': $store.sidebar.open, 'opacity-0 pointer-events-none': !$store.sidebar.open }"
        @click="$store.sidebar.close()" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <div class="flex flex-col h-full w-72 lg:w-full bg-white dark:bg-space-900 relative z-10 overflow-y-auto">
        {{-- Cabeçalho sidebar (fechar no móvel) --}}
        <div
            class="flex items-center justify-between h-14 px-4 border-b border-gray-100 dark:border-space-700/50 shrink-0">
            <span class="font-orbitron font-semibold text-gray-900 dark:text-space-100">Menu</span>
            <button type="button" @click="$store.sidebar.close()"
                class="lg:hidden p-2 rounded-lg text-gray-500 dark:text-space-400 hover:bg-gray-100 dark:hover:bg-space-800"
                aria-label="Fechar menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 py-4 px-3 space-y-1" aria-label="Navegação principal">
            <a href="{{ route('web.home') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('web.home') ? 'bg-space-500 text-white' : 'text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Início
            </a>
            <a href="{{ route('web.artistas.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('web.artistas.*') ? 'bg-space-500 text-white' : 'text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Artistas
            </a>
            <a href="{{ route('web.musicas.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('web.musicas.*') && !request()->routeIs('web.musicas.create') && !request()->routeIs('web.musicas.import-cifraclub') ? 'bg-space-500 text-white' : 'text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
                Músicas
            </a>
            @auth
                <a href="{{ route('web.playlists.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('web.playlists.*') ? 'bg-space-500 text-white' : 'text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Playlists
                </a>
            @endauth
        </nav>

        @auth
            <div class="px-3 py-2 border-t border-gray-100 dark:border-space-700/50">
                <p class="px-3 py-1.5 text-xs font-semibold text-gray-500 dark:text-space-500 uppercase tracking-wider">
                    Ações rápidas</p>
                <div class="space-y-1 mt-1">
                    <a href="{{ route('web.musicas.create') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100 transition-colors">
                        <span
                            class="w-8 h-8 rounded-lg bg-green-500/20 dark:bg-green-500/30 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                        Nova música
                    </a>
                    <a href="{{ route('web.musicas.import-cifraclub') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-100 transition-colors">
                        <span
                            class="w-8 h-8 rounded-lg bg-amber-500/20 dark:bg-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </span>
                        Importar CifraClub
                    </a>
                </div>
            </div>

            @if ($playlistsSidebar->isNotEmpty())
                <div class="px-3 py-2 border-t border-gray-100 dark:border-space-700/50 flex-1 min-h-0 flex flex-col">
                    <p class="px-3 py-1.5 text-xs font-semibold text-gray-500 dark:text-space-500 uppercase tracking-wider">
                        Minhas playlists</p>
                    <ul class="mt-1 space-y-0.5 overflow-y-auto py-1 max-h-48">
                        @foreach ($playlistsSidebar as $pl)
                            <li>
                                <a href="{{ route('web.playlists.show', $pl) }}"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-space-400 hover:bg-gray-100 dark:hover:bg-space-800 hover:text-gray-900 dark:hover:text-space-200 truncate transition-colors {{ request()->routeIs('web.playlists.show') && request()->route('playlist') && request()->route('playlist')->id === $pl->id ? 'bg-space-500/10 dark:bg-space-500/20 text-space-600 dark:text-space-300 font-medium' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-space-500 shrink-0"></span>
                                    <span class="truncate">{{ $pl->nome }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('web.playlists.index') }}"
                        class="mt-2 px-3 py-2 text-sm text-space-500 dark:text-space-400 hover:text-space-600 dark:hover:text-space-300 font-medium">Ver
                        todas →</a>
                </div>
            @endif
        @endauth

        {{-- Tema no rodapé da sidebar --}}
        <div class="p-3 border-t border-gray-100 dark:border-space-700/50 shrink-0">
            <button type="button" @click="$store.theme.toggle()"
                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-800 transition-colors">
                <span
                    class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-space-700 flex items-center justify-center shrink-0">
                    <svg x-show="$store.theme.dark" class="w-4 h-4 text-space-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </span>
                <svg x-show="!$store.theme.dark" class="w-4 h-4 text-space-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </span>
                    </span>
                    <span x-text="$store.theme.dark ? 'Modo escuro' : 'Modo claro'"></span>
            </button>
        </div>
    </div>
</div>
