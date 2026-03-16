@extends('web.layout')

@section('title', $musica->titulo . ' – App Cifras')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 lg:gap-8 flex-1 min-h-0 w-full" x-data="{
    fontSize: 14,
    playlists: {{ Js::from($playlists->mapWithKeys(fn($p) => [$p->id => route('web.playlists.add-musica', $p)])->all()) }},
    selectedPlaylistId: ''
}">
    @include('web.components.sidebar-musica')

    {{-- Área principal: usa todo o espaço restante --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-0">
        <header class="shrink-0 mb-4 flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <h1 class="font-orbitron text-2xl lg:text-3xl font-bold text-gray-900 dark:text-space-100 truncate">{{ $musica->titulo }}</h1>
                <p class="text-gray-600 dark:text-space-400 font-exo text-sm mt-1">
                    {{ $musica->artista?->nome }}
                    @if($tom_original)<span class="text-gray-500 dark:text-space-500 ml-2">• Tom original: {{ $tom_original }}</span>@endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @auth
                @if($playlists->isNotEmpty())
                <form method="POST" :action="playlists[selectedPlaylistId] || '#'" id="form-add-playlist" class="flex flex-wrap items-center gap-2" x-on:submit="if (!selectedPlaylistId) $event.preventDefault()">
                    @csrf
                    <input type="hidden" name="musica_id" value="{{ $musica->id }}">
                    <select x-model="selectedPlaylistId" required class="rounded-full bg-gray-100 dark:bg-space-800 border border-gray-200 dark:border-space-600 text-gray-800 dark:text-space-200 hover:bg-gray-200 dark:hover:bg-space-700 font-exo text-xs px-3 py-1.5 min-w-[140px] focus:ring-2 focus:ring-space-500">
                        <option value="">Adicionar à playlist...</option>
                        @foreach($playlists as $p)
                        <option value="{{ $p->id }}">{{ $p->nome }}{{ $p->is_public ? ' — pública' : '' }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-full bg-gray-200 dark:bg-space-800 border border-gray-300 dark:border-space-600 text-gray-800 dark:text-space-200 hover:bg-gray-300 dark:hover:bg-space-700 font-exo text-xs px-3 py-1.5">Adicionar</button>
                </form>
                @endif
                @if($versao_ativa)
                <a href="{{ route('web.musicas.edit-versao', [$musica->slug, $versao_ativa->id]) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 dark:bg-space-700 hover:bg-gray-300 dark:hover:bg-space-600 text-gray-800 dark:text-space-200 font-exo text-sm px-3 py-2">Editar versão</a>
                @endif
                @endauth
            </div>
        </header>

        @if(!$versao_ativa)
            <div class="flex-1 rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-6 text-gray-600 dark:text-space-400 shadow-sm dark:shadow-none flex items-center justify-center">Selecione uma versão na barra lateral.</div>
        @else
            <div class="shrink-0 flex flex-wrap items-center gap-3 mb-3">
                <h2 class="font-orbitron text-lg font-semibold text-gray-900 dark:text-space-100">
                    {{ $versao_ativa->titulo_versao ?: 'Versão ' . $versao_ativa->numero_versao }}
                </h2>
            </div>

            @if($capo_ativo && $conteudo_com_capo !== null && $conteudo_tom_real !== null)
                {{-- Vista capotraste: duas colunas --}}
                <div class="flex-1 min-h-0 overflow-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="cifra-content" :style="'font-size: ' + fontSize + 'px'">
                    <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-5 overflow-hidden shadow-sm dark:shadow-none">
                        <h3 class="font-orbitron font-semibold text-gray-700 dark:text-space-200 mb-3">Com capotraste (casa {{ $capo_casa }})</h3>
                        @if($usa_estrutura && is_array($conteudo_com_capo))
                            @include('web.musicas.partials.cifra-estrutura', ['estrutura' => $conteudo_com_capo])
                        @else
                            <pre class="font-mono whitespace-pre-wrap text-gray-800 dark:text-space-200 overflow-x-auto p-0 m-0 leading-relaxed">{{ $conteudo_com_capo ?: '(sem conteúdo)' }}</pre>
                        @endif
                    </div>
                    <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-5 overflow-hidden shadow-sm dark:shadow-none">
                        <h3 class="font-orbitron font-semibold text-gray-700 dark:text-space-200 mb-3">Tom real</h3>
                        @if($usa_estrutura && is_array($conteudo_tom_real))
                            @include('web.musicas.partials.cifra-estrutura', ['estrutura' => $conteudo_tom_real])
                        @else
                            <pre class="font-mono whitespace-pre-wrap text-gray-800 dark:text-space-200 overflow-x-auto p-0 m-0 leading-relaxed">{{ $conteudo_tom_real ?: '(carregando...)' }}</pre>
                        @endif
                    </div>
                </div>
                </div>
            @elseif($usa_estrutura && $estrutura_exibir)
                {{-- Estrutura com linhas de acordes --}}
                <div class="flex-1 min-h-0 overflow-auto">
                <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-5 overflow-x-auto shadow-sm dark:shadow-none" id="cifra-content" :style="'font-size: ' + fontSize + 'px'">
                    @include('web.musicas.partials.cifra-estrutura', ['estrutura' => $estrutura_exibir])
                </div>
                </div>
            @else
                <div class="flex-1 min-h-0 overflow-auto">
                <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-5 overflow-x-auto shadow-sm dark:shadow-none" id="cifra-content" :style="'font-size: ' + fontSize + 'px'">
                    <pre class="font-mono whitespace-pre-wrap text-gray-800 dark:text-space-200 p-0 m-0 block leading-relaxed">{{ $conteudo_exibir ?: '(sem conteúdo)' }}</pre>
                </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
