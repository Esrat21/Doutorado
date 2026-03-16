@extends('web.layout')

@section('title', 'Nova música – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.musicas.index') }}" class="hover:text-gray-900 dark:hover:text-space-100">Músicas</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Nova música</span>
</nav>

<div class="max-w-2xl">
    <div class="bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-none">
        <h1 class="font-orbitron text-2xl font-bold text-gray-900 dark:text-space-100 mb-2">Nova música</h1>
        <p class="text-gray-600 dark:text-space-300 text-sm font-exo mb-6">Preencha os dados e opcionalmente cole a cifra.</p>

        <form method="POST" action="{{ route('web.musicas.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="artista" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Artista</label>
                <input id="artista" type="text" name="artista_nome" value="{{ old('artista_nome') }}" required
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500" placeholder="Ex: Legião Urbana">
                @error('artista_nome')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="titulo" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Nome da música</label>
                <input id="titulo" type="text" name="titulo" value="{{ old('titulo') }}" required
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500" placeholder="Ex: Tempo perdido">
                @error('titulo')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="conteudo" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Cifra (opcional)</label>
                <textarea id="conteudo" name="conteudo" rows="5" placeholder="Cole a cifra aqui."
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 font-mono text-sm focus:ring-2 focus:ring-space-500">{{ old('conteudo') }}</textarea>
            </div>
            <div>
                <label for="tom_original" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Tom original</label>
                <input id="tom_original" type="text" name="tom_original" value="{{ old('tom_original') }}"
                    class="w-24 rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500" placeholder="Ex: C, Am">
                <p class="mt-1 text-xs text-gray-500 dark:text-space-400 font-exo">Digite manualmente (ex: C, Am, F#).</p>
            </div>
            @if($errors->any())
            <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/50 text-red-700 dark:text-red-200 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
            @endif
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                <a href="{{ route('web.musicas.index') }}" class="rounded-xl bg-gray-200 dark:bg-space-700 hover:bg-gray-300 dark:hover:bg-space-600 text-gray-800 dark:text-space-200 px-4 py-2 text-center font-exo">Cancelar</a>
                <button type="submit" class="rounded-xl bg-space-500 hover:bg-space-600 text-white py-2 px-4 font-orbitron font-semibold focus:ring-2 focus:ring-space-400">Criar música</button>
            </div>
        </form>
    </div>
</div>
@endsection
