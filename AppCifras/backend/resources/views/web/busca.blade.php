@extends('web.layout')

@section('title', ($q ? 'Busca: ' . e($q) . ' – ' : 'Busca – ') . 'App Cifras')

@section('content')
<div class="mb-6">
    <h1 class="font-orbitron text-2xl font-bold text-gray-900 dark:text-space-100 mb-2">Buscar músicas e artistas</h1>
    <p class="text-gray-600 dark:text-space-400 font-exo text-sm">Use a barra de busca no topo da página para pesquisar.</p>
</div>

@if($q !== '')
    @if($musicas->isEmpty() && $artistas->isEmpty())
        <div class="rounded-xl bg-gray-100 dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-8 text-center">
            <p class="text-gray-600 dark:text-space-400 font-exo">Nenhum resultado para <strong class="text-gray-900 dark:text-space-200">"{{ e($q) }}"</strong>.</p>
            <p class="text-gray-500 dark:text-space-500 text-sm mt-2">Tente outros termos ou verifique se a música/artista está no seu acervo.</p>
        </div>
    @else
        @if($musicas->isNotEmpty())
        <section class="mb-10">
            <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-space-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                Músicas ({{ $musicas->count() }})
            </h2>
            <ul class="space-y-1">
                @foreach($musicas as $m)
                <li>
                    <a href="{{ route('web.musicas.show', $m->slug) }}" class="flex items-center gap-3 rounded-xl py-2.5 px-3 -mx-1 hover:bg-gray-100 dark:hover:bg-space-800/80 transition-colors group">
                        <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-space-500/20 dark:bg-space-500/30 flex items-center justify-center text-space-600 dark:text-space-400 font-orbitron text-sm group-hover:bg-space-500/30 dark:group-hover:bg-space-500/50">♪</span>
                        <div class="min-w-0 flex-1">
                            <span class="block font-exo font-medium text-gray-900 dark:text-space-100 truncate group-hover:text-space-500 dark:group-hover:text-space-400 transition-colors">{{ $m->titulo }}</span>
                            @if($m->artista)
                            <span class="block font-exo text-sm text-gray-500 dark:text-space-500 truncate">{{ $m->artista->nome }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-gray-400 dark:text-space-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                @endforeach
            </ul>
        </section>
        @endif

        @if($artistas->isNotEmpty())
        <section>
            <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-space-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Artistas ({{ $artistas->count() }})
            </h2>
            <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($artistas as $a)
                <li>
                    <a href="{{ route('web.artistas.show', $a) }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gray-100 dark:bg-space-800/60 border border-gray-200 dark:border-space-600/50 hover:border-space-500/50 hover:bg-gray-200/80 dark:hover:bg-space-700/60 transition-colors group">
                        <span class="w-14 h-14 rounded-full bg-space-500/20 dark:bg-space-500/30 flex items-center justify-center text-space-600 dark:text-space-400 font-orbitron text-xl group-hover:bg-space-500/40 dark:group-hover:bg-space-500/50 transition-colors">{{ strtoupper(mb_substr($a->nome, 0, 1)) }}</span>
                        <span class="font-exo text-sm font-medium text-gray-900 dark:text-space-200 text-center line-clamp-2 leading-tight">{{ $a->nome }}</span>
                        <span class="text-xs text-gray-500 dark:text-space-500">{{ $a->musicas_count }} música(s)</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </section>
        @endif
    @endif
@else
    <div class="rounded-xl bg-gray-100 dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-8 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-space-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <p class="text-gray-600 dark:text-space-400 font-exo">Digite na barra de busca no topo para encontrar músicas ou artistas.</p>
    </div>
@endif
@endsection
