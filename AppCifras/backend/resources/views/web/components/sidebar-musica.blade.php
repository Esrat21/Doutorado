{{-- Sidebar da página de uma música: Tom, Versões, Capotraste, Tamanho do texto. Usado apenas em web.musicas.show. --}}
<aside class="w-full lg:w-64 xl:w-72 shrink-0 space-y-4 flex flex-col lg:max-h-full">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-space-400 font-exo shrink-0">
        <a href="{{ route('web.home') }}" class="hover:text-gray-900 dark:hover:text-space-100">Início</a>
        <span class="text-gray-400 dark:text-space-600">/</span>
        <a href="{{ route('web.musicas.index') }}" class="hover:text-gray-900 dark:hover:text-space-100">Músicas</a>
        <span class="text-gray-400 dark:text-space-600">/</span>
        <span class="text-gray-700 dark:text-space-200 truncate max-w-[140px] sm:max-w-none">{{ $musica->titulo }}</span>
    </nav>

    {{-- 1. Tom --}}
    <div
        class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 shadow-sm dark:shadow-none shrink-0">
        <h3 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 mb-3">Tom</h3>
        <form method="GET" action="{{ route('web.musicas.show', $musica->slug) }}" class="space-y-2" id="form-tom">
            @if (request('versao'))
                <input type="hidden" name="versao" value="{{ request('versao') }}">
            @endif
            <input type="hidden" name="capo_casa" value="{{ request('capo_casa', 0) }}">
            <select name="tom" @change="$el.form.submit()"
                class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2 focus:ring-2 focus:ring-space-500">
                <optgroup label="Maior">
                    @foreach ($tons_para_select['maior'] as $t)
                        <option value="{{ $t }}" {{ $tom_selecionado === $t ? 'selected' : '' }}>
                            {{ $t }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Menor (relativo)">
                    @foreach ($tons_para_select['menor'] as $t)
                        <option value="{{ $t }}" {{ $tom_selecionado === $t ? 'selected' : '' }}>
                            {{ $t }}</option>
                    @endforeach
                </optgroup>
            </select>
            <div class="flex items-center gap-2 mt-2">
                <a href="{{ route('web.musicas.show', array_merge(request()->only(['versao', 'capo_casa']), ['musica' => $musica->slug, 'tom' => $tom_relativo])) }}"
                    class="text-xs rounded-lg bg-gray-200 dark:bg-space-700 hover:bg-gray-300 dark:hover:bg-space-600 text-gray-800 dark:text-space-200 font-exo px-2 py-1.5">Tom
                    relativo ({{ $tom_relativo }})</a>
            </div>
            <p class="text-xs text-gray-500 dark:text-space-500 font-exo mt-1">Tom original: {{ $tom_original }}</p>
        </form>
    </div>

    {{-- 2. Versões --}}
    <div
        class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 shadow-sm dark:shadow-none shrink-0">
        <h3 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 mb-1">Versões</h3>
        <p class="text-gray-500 dark:text-space-500 text-xs font-exo mb-3">{{ $versoes->count() }} versão(ões)</p>
        @if ($versoes->isEmpty())
            <p class="text-gray-500 dark:text-space-500 text-sm font-exo">Nenhuma versão.</p>
        @else
            <ul class="space-y-1 max-h-48 overflow-y-auto pr-1">
                @foreach ($versoes as $v)
                    <li>
                        <a href="{{ route('web.musicas.show', ['musica' => $musica->slug, 'versao' => $v->id, 'tom' => $tom_selecionado, 'capo_casa' => $capo_casa]) }}"
                            class="block w-full text-center px-3 py-2.5 rounded-xl font-exo text-sm font-medium transition-colors {{ $versao_ativa && $versao_ativa->id === $v->id ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-300 hover:bg-gray-100 dark:hover:bg-space-700 hover:text-gray-900 dark:hover:text-space-100' }}">
                            {{ $v->titulo_versao ?: 'Versão ' . $v->numero_versao }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- 3. Capotraste --}}
    <div
        class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 shadow-sm dark:shadow-none shrink-0">
        <h3 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 mb-3">Capotraste</h3>
        <form method="GET" action="{{ route('web.musicas.show', $musica->slug) }}" id="form-capo">
            <input type="hidden" name="versao" value="{{ request('versao', $versao_ativa?->id) }}">
            <input type="hidden" name="tom" value="{{ $tom_selecionado }}">
            <label for="capo_casa" class="font-exo text-gray-600 dark:text-space-300 text-sm block mb-1">Casa</label>
            <select name="capo_casa" id="capo_casa" @change="$el.form.submit()"
                class="w-full rounded-lg bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-3 py-2 text-sm focus:ring-2 focus:ring-space-500">
                <option value="0" {{ (int) $capo_casa === 0 ? 'selected' : '' }}>Sem capotraste</option>
                @foreach (range(1, 12) as $n)
                    <option value="{{ $n }}" {{ (int) $capo_casa === $n ? 'selected' : '' }}>
                        {{ $n }}ª casa</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- 4. Tamanho do texto --}}
    <div
        class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 shadow-sm dark:shadow-none shrink-0">
        <h3 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 mb-3">Tamanho do texto</h3>
        <div class="flex items-center gap-2">
            <button type="button" @click="fontSize = Math.max(10, fontSize - 1)"
                class="px-2 py-1.5 rounded-lg bg-gray-100 dark:bg-space-900 text-gray-700 dark:text-space-200 text-sm font-exo hover:bg-gray-200 dark:hover:bg-space-700 focus:ring-2 focus:ring-space-500">−</button>
            <input type="number" x-model.number="fontSize" min="10" max="32"
                class="w-14 rounded-lg bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 text-sm px-2 py-1 text-center font-mono focus:ring-2 focus:ring-space-500">
            <span class="text-gray-500 dark:text-space-500 text-xs font-exo">px</span>
            <button type="button" @click="fontSize = Math.min(32, fontSize + 1)"
                class="px-2 py-1.5 rounded-lg bg-gray-100 dark:bg-space-900 text-gray-700 dark:text-space-200 text-sm font-exo hover:bg-gray-200 dark:hover:bg-space-700 focus:ring-2 focus:ring-space-500">+</button>
        </div>
    </div>

    {{-- 5. Download em PDF (download direto: 2 colunas, fonte 9) --}}
    @if ($versao_ativa ?? null)
        @php
            $semitonesPdf = \App\Services\ChordTransposer::semitonesBetween($tom_original, $tom_selecionado);
            $queryPdf = array_filter([
                'semitones' => $semitonesPdf ?: null,
                'capo' => $capo_casa ?: null,
            ]);
            $urlPdf = route('web.musicas.pdf', [$musica->slug, $versao_ativa->id]) . ($queryPdf ? '?' . http_build_query($queryPdf) : '');
        @endphp
        <div class="rounded-xl bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 p-4 shadow-sm dark:shadow-none shrink-0">
            <h3 class="font-orbitron font-semibold text-gray-900 dark:text-space-100 mb-3">Download</h3>
            <a href="{{ $urlPdf }}" download
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-space-500 hover:bg-space-600 text-white font-exo text-sm font-medium py-2.5 px-4 transition-colors focus:ring-2 focus:ring-space-400 focus:ring-offset-2 dark:focus:ring-offset-space-900">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Baixar PDF
            </a>
            <p class="text-xs text-gray-500 dark:text-space-500 font-exo mt-2">2 colunas, fonte 9.</p>
        </div>
    @endif
</aside>
