@extends('web.layout')

@section('title', 'Artistas – App Cifras')

@section('content')
<nav class="mb-6 flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo">
    <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100 transition-colors">Início</a>
    <span aria-hidden class="text-gray-400 dark:text-space-600">/</span>
    <span class="text-gray-700 dark:text-space-200">Artistas</span>
</nav>

<h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100 mb-2">Artistas</h1>
<p class="text-gray-600 dark:text-space-400 font-exo text-sm mb-8">Adicione artistas e filtre músicas por eles.</p>

@auth
<div class="mb-8 rounded-2xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/50 p-4 sm:p-6 shadow-sm dark:shadow-none">
    <form method="POST" action="{{ route('web.artistas.store') }}" class="flex flex-wrap gap-3">
        @csrf
        <input type="text" name="nome" placeholder="Nome do artista" value="{{ old('nome') }}"
            class="flex-1 min-w-[200px] rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 placeholder-gray-500 dark:placeholder-space-500 focus:ring-2 focus:ring-space-500 focus:border-transparent">
        <button type="submit" class="shrink-0 bg-space-500 hover:bg-space-600 text-white font-orbitron rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-space-400">Adicionar</button>
    </form>
</div>
@endauth

@if($artistas->isEmpty())
    <p class="text-gray-600 dark:text-space-400 font-exo">Nenhum artista. @auth Adicione um acima. @else Faça login para adicionar artistas. @endauth</p>
@else
    <ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($artistas as $a)
        <li>
            <a href="{{ route('web.artistas.show', $a) }}">
                <div class="rounded-xl bg-white dark:bg-space-800/60 border border-gray-200 dark:border-space-600/40 hover:border-space-500/50 transition-all h-full p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:bg-space-700/60">
                    <span class="font-exo text-gray-900 dark:text-space-100">{{ $a->nome }}</span>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    <div class="mt-6">{{ $artistas->links() }}</div>
@endif
@endsection
