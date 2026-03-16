@extends('web.layout')

@section('title', $artista->nome . ' – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100 transition-colors">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.artistas.index') }}" class="hover:text-gray-900 dark:hover:text-space-100 transition-colors">Artistas</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200 truncate max-w-[200px] sm:max-w-none">{{ $artista->nome }}</span>
</nav>

<header class="mb-8 flex flex-wrap items-center gap-4">
    <span class="w-20 h-20 rounded-full bg-gray-200 dark:bg-space-700 border-2 border-gray-300 dark:border-space-600 flex items-center justify-center text-gray-700 dark:text-space-200 font-orbitron text-3xl shrink-0">{{ strtoupper(mb_substr($artista->nome, 0, 1)) }}</span>
    <div>
        <h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100">{{ $artista->nome }}</h1>
        <p class="text-gray-600 dark:text-space-400 font-exo mt-1">{{ $artista->musicas->count() }} {{ $artista->musicas->count() === 1 ? 'música' : 'músicas' }}</p>
    </div>
</header>

<h2 class="font-orbitron text-xl font-semibold text-gray-900 dark:text-space-100 mb-4">Músicas (ordem alfabética)</h2>
@if($artista->musicas->isEmpty())
    <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 text-gray-700 dark:text-space-200 p-4">
        Nenhuma música deste artista no repertório. @auth<a href="{{ route('web.musicas.create') }}" class="font-semibold text-space-500 dark:text-space-400 underline hover:text-space-600 dark:hover:text-space-100">Criar música</a>@endauth
    </div>
@else
    <ul class="space-y-2">
        @foreach($artista->musicas as $m)
        <li>
            <a href="{{ route('web.musicas.show', $m->slug) }}">
                <div class="rounded-xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/40 hover:border-space-500/50 transition-all p-4 flex flex-wrap items-center justify-between gap-2 shadow-sm dark:shadow-none hover:shadow-md dark:hover:bg-space-700/60">
                    <span class="font-exo text-gray-900 dark:text-space-100 font-medium">{{ $m->titulo }}</span>
                    @if($m->tom_original)
                    <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-space-600/50 text-gray-700 dark:text-space-200 font-orbitron text-sm">{{ $m->tom_original }}</span>
                    @endif
                </div>
            </a>
        </li>
        @endforeach
    </ul>
@endif
@endsection
