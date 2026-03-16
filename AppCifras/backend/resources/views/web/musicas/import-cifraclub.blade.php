@extends('web.layout')

@section('title', 'Importar Cifra Club – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <a href="{{ route('web.musicas.index') }}" class="hover:text-gray-900 dark:hover:text-space-100">Músicas</a>
    <span class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Importar do Cifra Club</span>
</nav>

<div class="space-y-8">
    <header class="max-w-3xl">
        <h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100 mb-2">Importar do Cifra Club</h1>
        <p class="text-gray-600 dark:text-space-400 font-exo text-sm">Cole uma URL de cifra individual ou da página de um artista no Cifra Club.</p>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-6 shadow-sm dark:shadow-none">
            <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-2">Importar uma cifra</h2>
            <p class="text-gray-600 dark:text-space-400 font-exo text-xs mb-4">Use para importar uma música específica.</p>
            <form method="POST" action="{{ route('web.musicas.import-cifraclub.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="url" class="text-gray-700 dark:text-space-200 font-exo text-sm block mb-1">URL da cifra</label>
                    <input id="url" type="url" name="url" value="{{ old('url') }}" required
                        placeholder="https://www.cifraclub.com.br/legiao-urbana/tempo-perdido/"
                        class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 font-mono text-sm focus:ring-2 focus:ring-space-500">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="rounded-2xl bg-space-500 hover:bg-space-400 text-white font-orbitron px-5 py-2.5 text-sm focus:ring-2 focus:ring-space-400">Importar cifra</button>
                    <a href="{{ route('web.musicas.index') }}" class="rounded-2xl border border-gray-300 dark:border-space-700 bg-gray-100 dark:bg-space-900/60 hover:bg-gray-200 dark:hover:bg-space-800 text-gray-800 dark:text-space-200 px-4 py-2 text-sm font-exo">Cancelar</a>
                </div>
            </form>
        </div>

        <div class="rounded-2xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-6 shadow-sm dark:shadow-none">
            <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100 mb-2">Importar todas do artista</h2>
            <p class="text-gray-600 dark:text-space-400 font-exo text-xs mb-4">Cole a URL da página do artista no Cifra Club.</p>
            <form method="POST" action="{{ route('web.musicas.import-cifraclub-artista') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="artist_url" class="text-gray-700 dark:text-space-200 font-exo text-sm block mb-1">URL do artista</label>
                    <input id="artist_url" type="url" name="url" value="{{ old('artist_url') }}" required
                        placeholder="https://www.cifraclub.com.br/legiao-urbana/"
                        class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 font-mono text-sm focus:ring-2 focus:ring-space-500">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="rounded-2xl bg-space-500 hover:bg-space-400 text-white font-orbitron px-5 py-2.5 text-sm focus:ring-2 focus:ring-space-400">Importar músicas do artista</button>
                    <a href="{{ route('web.musicas.index') }}" class="rounded-2xl border border-gray-300 dark:border-space-700 bg-gray-100 dark:bg-space-900/60 hover:bg-gray-200 dark:hover:bg-space-800 text-gray-800 dark:text-space-200 px-4 py-2 text-sm font-exo">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
