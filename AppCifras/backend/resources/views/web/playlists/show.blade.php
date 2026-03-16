@extends('web.layout')

@section('title', $playlist->nome . ' – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.playlists.index') }}" class="hover:text-gray-900 dark:hover:text-space-100">Playlists</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200 truncate max-w-[160px] sm:max-w-none">{{ $playlist->nome }}</span>
</nav>

<div class="flex flex-wrap items-center gap-3 mb-6">
    <h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100">{{ $playlist->nome }}</h1>
    <span class="px-3 py-1 rounded-full text-xs font-exo {{ $playlist->is_public ? 'bg-space-500/20 dark:bg-space-600/50 text-space-600 dark:text-space-200' : 'bg-gray-200 dark:bg-space-700 text-gray-600 dark:text-space-400' }}">{{ $playlist->is_public ? 'Pública' : 'Privada' }}</span>
</div>

<div class="mb-8 rounded-2xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/50 p-4 sm:p-6 shadow-sm dark:shadow-none">
    <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-3">Editar playlist</h2>
    <form method="POST" action="{{ route('web.playlists.update', $playlist) }}" class="flex flex-wrap gap-4 items-end">
        @csrf
        @method('PUT')
        <div class="flex-1 min-w-[200px] space-y-1.5">
            <label for="nome" class="text-gray-700 dark:text-space-200 font-exo text-sm block">Nome</label>
            <input id="nome" type="text" name="nome" value="{{ old('nome', $playlist->nome) }}" class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500">
        </div>
        <div class="flex flex-col gap-1">
            <span class="text-gray-700 dark:text-space-200 font-exo text-sm">Visibilidade</span>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_public" value="1" {{ old('is_public', $playlist->is_public) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-space-600 bg-gray-50 dark:bg-space-900 text-space-500 focus:ring-space-500">
                <span class="text-gray-600 dark:text-space-300 font-exo text-sm">Pública (compartilhável)</span>
            </label>
        </div>
        @if($playlist->is_public)
        <div class="w-full mt-2">
            <label class="text-gray-700 dark:text-space-200 font-exo text-sm block mb-1">Link público</label>
            <input type="text" readonly value="{{ url('/playlists/public/' . $playlist->slug) }}" class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-700 text-gray-900 dark:text-space-100 font-mono text-xs px-4 py-2" onclick="this.select()">
        </div>
        @endif
        <button type="submit" class="rounded-xl bg-space-500 hover:bg-space-600 text-white font-orbitron text-sm px-4 py-2 focus:ring-2 focus:ring-space-400">Salvar</button>
    </form>
</div>

<section class="mb-8">
    <h2 class="font-orbitron text-xl font-semibold text-gray-900 dark:text-space-100 mb-4">Músicas na playlist</h2>
    @if($playlist->musicas->isEmpty())
        <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 text-gray-700 dark:text-space-200 font-exo shadow-sm dark:shadow-none">Nenhuma música. Adicione abaixo.</div>
    @else
        <ul class="space-y-3">
            @foreach($playlist->musicas as $m)
            <li>
                <div class="rounded-xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/40 p-4 flex flex-wrap items-center gap-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:bg-space-700/60">
                    <a href="{{ route('web.musicas.show', $m->slug) }}" class="flex-1 min-w-0 font-exo text-gray-900 dark:text-space-100 hover:text-gray-700 dark:hover:text-space-200 truncate">{{ $m->titulo }}</a>
                    <span class="text-gray-500 dark:text-space-500 text-sm font-exo shrink-0">{{ $m->artista?->nome }}</span>
                    <form method="POST" action="{{ route('web.playlists.remove-musica', [$playlist, $m->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="shrink-0 rounded-lg px-2 py-1 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-space-700 text-sm">Remover</button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
    @endif
</section>

@php
    $idsInPlaylist = $playlist->musicas->pluck('id')->flip();
    $availableToAdd = $allMusicas->filter(fn($m) => !$idsInPlaylist->has($m->id));
@endphp
<div class="rounded-2xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/50 p-4 sm:p-6 shadow-sm dark:shadow-none">
    <h3 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-3">Adicionar música</h3>
    <form method="POST" action="{{ route('web.playlists.add-musica', $playlist) }}" class="flex flex-wrap gap-3 items-end">
        @csrf
        <div class="flex-1 min-w-[200px]">
            <label for="musica_id" class="sr-only">Escolha uma música</label>
            <select id="musica_id" name="musica_id" required class="w-full rounded-xl border border-gray-200 dark:border-space-600 bg-gray-50 dark:bg-space-900 px-4 py-2.5 text-gray-900 dark:text-space-100 font-exo text-sm focus:ring-2 focus:ring-space-500">
                <option value="">Escolha uma música</option>
                @foreach($availableToAdd as $m)
                <option value="{{ $m->id }}">{{ $m->titulo }} — {{ $m->artista?->nome }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-space-500 hover:bg-space-600 text-white px-4 py-2.5 font-exo focus:ring-2 focus:ring-space-400">Adicionar</button>
    </form>
    @if($availableToAdd->isEmpty() && $allMusicas->isNotEmpty())
        <p class="text-gray-500 dark:text-space-500 text-sm mt-2 font-exo">Todas as suas músicas já estão nesta playlist.</p>
    @endif
</div>
@endsection
