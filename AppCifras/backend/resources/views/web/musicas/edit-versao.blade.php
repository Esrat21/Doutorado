@extends('web.layout')

@section('title', 'Editar versão – ' . $musica->titulo)

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.musicas.index') }}" class="hover:text-gray-900 dark:hover:text-space-100">Músicas</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.musicas.show', $musica->slug) }}" class="hover:text-gray-900 dark:hover:text-space-100">{{ $musica->titulo }}</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Editar versão</span>
</nav>

<div class="rounded-2xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-6 shadow-sm dark:shadow-none">
    <h1 class="font-orbitron text-xl font-semibold text-gray-900 dark:text-space-100 mb-4">Editar versão</h1>
    <form method="POST" action="{{ route('web.musicas.update-versao', [$musica->slug, $versao->id]) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="titulo_versao" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Título da versão</label>
            <input id="titulo_versao" type="text" name="titulo_versao" value="{{ old('titulo_versao', $versao->titulo_versao) }}"
                placeholder="Ex: Versão original, Ao vivo"
                class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500">
        </div>
        <div>
            <label for="conteudo" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Cifra (texto)</label>
            <textarea id="conteudo" name="conteudo" rows="16" placeholder="[Intro]&#10;Am    C&#10;Letra..."
                class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 font-mono text-sm focus:ring-2 focus:ring-space-500">{{ old('conteudo', $versao->conteudo) }}</textarea>
            <p class="text-xs text-gray-500 dark:text-space-500 mt-1 font-exo">Use [Nome da seção] para títulos. Linha de acordes acima da letra.</p>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-space-500 hover:bg-space-600 text-white px-4 py-2 font-exo focus:ring-2 focus:ring-space-400">Salvar</button>
            <a href="{{ route('web.musicas.show', $musica->slug) }}" class="rounded-xl bg-gray-200 dark:bg-space-700 hover:bg-gray-300 dark:hover:bg-space-600 text-gray-800 dark:text-space-200 px-4 py-2 font-exo">Cancelar</a>
        </div>
    </form>
</div>
@endsection
