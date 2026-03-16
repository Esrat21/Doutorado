@extends('web.layout')

@section('title', 'Músicas – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100 transition-colors">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Músicas</span>
</nav>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100">Músicas</h1>
        <p class="text-gray-600 dark:text-space-400 font-exo text-sm mt-1">Gerencie suas cifras</p>
    </div>
    @auth
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('web.musicas.import-cifraclub') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-200 dark:bg-space-700 hover:bg-gray-300 dark:hover:bg-space-600 text-gray-800 dark:text-space-200 font-exo text-sm transition-colors">Importar Cifra Club</a>
        <a href="{{ route('web.musicas.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-space-500 hover:bg-space-600 text-white font-orbitron font-semibold focus:ring-2 focus:ring-space-400">+ Nova música</a>
    </div>
    @endauth
</div>

@if($musicas->isEmpty())
    <div class="rounded-2xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-8 text-center shadow-sm dark:shadow-none">
        <p class="text-gray-600 dark:text-space-300 font-exo mb-4">Nenhuma música ainda.</p>
        @auth<a href="{{ route('web.musicas.create') }}" class="inline-block rounded-xl bg-space-500 hover:bg-space-600 text-white px-4 py-2 focus:ring-2 focus:ring-space-400">Criar primeira música</a>@endauth
    </div>
@else
    <ul class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($musicas as $m)
        <li>
            <a href="{{ route('web.musicas.show', $m->slug) }}">
                <div class="rounded-xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/40 hover:border-space-500/60 transition-all hover:shadow-lg dark:hover:shadow-none h-full p-4 flex flex-wrap items-start justify-between gap-2 shadow-sm dark:hover:bg-space-700/60">
                    <div class="min-w-0 flex-1">
                        <h2 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 text-lg truncate">{{ $m->titulo }}</h2>
                        <p class="text-gray-500 dark:text-space-400 font-exo text-sm mt-1">{{ $m->artista?->nome }}</p>
                    </div>
                    @if($m->tom_original)
                    <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-space-600/50 text-gray-700 dark:text-space-200 font-orbitron text-sm shrink-0">{{ $m->tom_original }}</span>
                    @endif
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    <div class="mt-6">{{ $musicas->links() }}</div>
@endif
@endsection
