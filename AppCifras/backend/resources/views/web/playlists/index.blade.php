@extends('web.layout')

@section('title', 'Playlists – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Playlists</span>
</nav>

<h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100 mb-2">Playlists</h1>
<p class="text-gray-600 dark:text-space-400 font-exo text-sm mb-8">Organize suas músicas em listas.</p>

<div class="mb-8 rounded-2xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/50 p-4 sm:p-6 shadow-sm dark:shadow-none">
    <form method="POST" action="{{ route('web.playlists.store') }}" class="flex flex-wrap gap-3 items-end">
        @csrf
        <div class="flex-1 min-w-[200px] space-y-2">
            <label for="nome" class="text-gray-700 dark:text-space-200 font-exo text-sm block">Nome da playlist</label>
            <input id="nome" type="text" name="nome" value="{{ old('nome') }}" placeholder="Nome da playlist"
                class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500">
        </div>
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-gray-300 dark:border-space-600 bg-gray-50 dark:bg-space-900 text-space-500 focus:ring-space-500">
                <span class="text-gray-700 dark:text-space-200 font-exo text-sm">Pública (compartilhável)</span>
            </label>
            <button type="submit" class="rounded-xl bg-space-500 hover:bg-space-600 text-white font-orbitron px-4 py-2.5 focus:ring-2 focus:ring-space-400">Criar</button>
        </div>
    </form>
</div>

@if($playlists->isEmpty())
    <p class="text-gray-600 dark:text-space-400 font-exo">Nenhuma playlist. Crie uma acima.</p>
@else
    <ul class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($playlists as $p)
        <li>
            <div class="rounded-xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/40 flex flex-col h-full p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:bg-space-700/60">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('web.playlists.show', $p) }}" class="font-exo text-gray-900 dark:text-space-100 hover:text-gray-700 dark:hover:text-space-200 font-medium truncate block">{{ $p->nome }}</a>
                        <div class="mt-1 flex items-center gap-2 text-xs font-exo text-gray-500 dark:text-space-500">
                            <span class="px-2 py-0.5 rounded-full {{ $p->is_public ? 'bg-space-500/20 dark:bg-space-600/50 text-space-600 dark:text-space-200' : 'bg-gray-200 dark:bg-space-700 text-gray-600 dark:text-space-400' }}">{{ $p->is_public ? 'Pública' : 'Privada' }}</span>
                            <span>{{ $p->musicas_count ?? 0 }} música(s)</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('web.playlists.destroy', $p) }}" onsubmit="return confirm('Excluir esta playlist?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="shrink-0 rounded-lg px-2 py-1 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-space-700 text-sm">Excluir</button>
                    </form>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
@endif
@endsection
