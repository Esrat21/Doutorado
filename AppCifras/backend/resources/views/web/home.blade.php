@extends('web.layout')

@section('title', 'Início – App Cifras')

@section('content')
<section class="mb-8">
    <h2 class="sr-only">Gêneros</h2>
    <div class="flex gap-2 overflow-x-auto pb-2">
        <a href="{{ route('web.musicas.index') }}" class="shrink-0 rounded-xl px-4 py-2.5 font-exo text-sm font-medium bg-space-500 text-white">Todas</a>
        @foreach($generos->take(10) as $g)
            @if($g !== 'Todas')
            <span class="shrink-0 rounded-xl px-4 py-2.5 font-exo text-sm bg-gray-100 dark:bg-space-800/80 text-gray-600 dark:text-space-300 border border-gray-200 dark:border-space-600/50">{{ $g }}</span>
            @endif
        @endforeach
    </div>
</section>

<section class="mb-12">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <h2 class="font-orbitron text-2xl font-bold text-gray-900 dark:text-space-100">Músicas em alta</h2>
        <a href="{{ route('web.musicas.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 dark:text-space-400 hover:text-gray-900 dark:hover:text-space-100 font-exo text-sm transition-colors">Ver mais →</a>
    </div>
    @if($musicas->isEmpty())
        <p class="text-gray-600 dark:text-space-400 font-exo py-8">
            Nenhuma música ainda.
            @auth<a href="{{ route('web.musicas.create') }}" class="text-space-500 dark:text-space-400 underline hover:text-space-600 dark:hover:text-space-200">Criar música</a>@endauth
        </p>
    @else
        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-3">
            @foreach($musicas as $i => $m)
            <li>
                <a href="{{ route('web.musicas.show', $m->slug) }}" class="flex items-center gap-3 rounded-xl py-2 -mx-2 px-2 hover:bg-gray-100 dark:hover:bg-space-800/60 transition-colors group">
                    <span class="flex-shrink-0 w-8 text-right font-orbitron text-sm text-space-500 tabular-nums">{{ str_pad((string)($i + 1), 2, '0') }}</span>
                    <span class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 dark:bg-space-700 flex items-center justify-center text-gray-500 dark:text-space-400 font-orbitron text-xs group-hover:bg-space-500/30 dark:group-hover:bg-space-600 transition-colors">♪</span>
                    <div class="min-w-0 flex-1">
                        <span class="block font-exo text-gray-900 dark:text-space-100 truncate group-hover:text-gray-700 dark:group-hover:text-space-200">{{ $m->titulo }}</span>
                        <span class="block font-exo text-sm text-gray-500 dark:text-space-500 truncate">{{ $m->artista?->nome }}</span>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <h2 class="font-orbitron text-2xl font-bold text-gray-900 dark:text-space-100">Artistas populares</h2>
        <a href="{{ route('web.artistas.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 dark:text-space-400 hover:text-gray-900 dark:hover:text-space-100 font-exo text-sm transition-colors">Ver mais →</a>
    </div>
    @if($artistas->isEmpty())
        <p class="text-gray-600 dark:text-space-400 font-exo py-8">Nenhum artista ainda. <a href="{{ route('web.artistas.index') }}" class="text-space-500 dark:text-space-400 underline hover:text-space-600 dark:hover:text-space-200">Ver artistas</a></p>
    @else
        <div class="overflow-x-auto pb-4 -mx-1">
            <ul class="flex gap-6 min-w-max pr-4">
                @foreach($artistas as $a)
                <li class="shrink-0 w-28 flex flex-col items-center">
                    <a href="{{ route('web.artistas.show', $a) }}" class="flex flex-col items-center gap-2 group">
                        <span class="w-24 h-24 rounded-full bg-gray-200 dark:bg-space-700 border-2 border-gray-300 dark:border-space-600 flex items-center justify-center text-gray-600 dark:text-space-300 font-orbitron text-2xl group-hover:border-space-500 group-hover:bg-space-500/20 dark:group-hover:bg-space-600 transition-colors">{{ strtoupper(mb_substr($a->nome, 0, 1)) }}</span>
                        <span class="font-exo text-sm text-gray-700 dark:text-space-200 text-center leading-tight line-clamp-2 group-hover:text-gray-900 dark:group-hover:text-space-100 transition-colors max-w-full">{{ $a->nome }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @endif
</section>
@endsection
