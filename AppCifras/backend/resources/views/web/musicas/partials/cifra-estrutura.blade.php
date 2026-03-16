@php
if (!function_exists('buildChordLineStringPartial')) {
    function buildChordLineStringPartial($letra, $acordes) {
        if (!is_array($acordes) || count($acordes) === 0) return '';
        $baseLen = max(mb_strlen($letra ?: ' '), 1);
        $maxEnd = 0;
        foreach ($acordes as $a) {
            $nome = trim($a['nome'] ?? '');
            if ($nome === '') continue;
            $start = max(0, (int)($a['pos'] ?? 0));
            $end = $start + mb_strlen($nome);
            if ($end > $maxEnd) $maxEnd = $end;
        }
        $len = max($baseLen, $maxEnd ?: 1);
        $arr = array_fill(0, $len, ' ');
        foreach ($acordes as $a) {
            $nome = trim($a['nome'] ?? '');
            if ($nome === '') continue;
            $start = max(0, (int)($a['pos'] ?? 0));
            for ($i = 0; $i < mb_strlen($nome); $i++) {
                $idx = $start + $i;
                if ($idx < $len) $arr[$idx] = mb_substr($nome, $i, 1);
            }
        }
        return implode('', $arr);
    }
}
@endphp
<div class="columns-1 md:columns-2 [column-gap:1.5rem] space-y-4 break-inside-avoid" style="font-size: inherit; line-height: 1.5;">
@foreach($estrutura['secoes'] ?? [] as $sec)
    <div class="break-inside-avoid mb-4">
        <div class="font-orbitron text-sm font-semibold text-gray-500 dark:text-space-400 mb-1">[{{ $sec['nome'] ?? '' }}]@if(!empty($sec['tablatura'])) (Tablatura)@endif</div>
        @if(!empty($sec['tablatura']))
            <pre class="font-mono text-gray-600 dark:text-space-300 text-xs leading-tight m-0 mb-1 whitespace-pre">{{ implode("\n", array_filter(array_map(fn($l) => trim($l['letra'] ?? ''), $sec['linhas'] ?? []))) }}</pre>
        @else
            @foreach($sec['linhas'] ?? [] as $linha)
                @php
                    $l1 = buildChordLineStringPartial($linha['letra'] ?? ' ', $linha['acordes'] ?? []);
                    $l2 = buildChordLineStringPartial($linha['letra'] ?? ' ', $linha['acordes_secundarios'] ?? []);
                    $letra = $linha['letra'] ?? '';
                    $parts = [];
                    if (trim($letra) === '' && ($l1 || $l2)) {
                        $parts[] = '<span class="text-amber-400/90 font-medium">' . e(trim($l1 . "\n" . $l2)) . '</span>';
                    } else {
                        if ($l1) $parts[] = '<span class="text-amber-400/90 font-medium">' . e($l1) . '</span>';
                        if ($l2) $parts[] = '<span class="text-amber-400/90 font-medium">' . e($l2) . '</span>';
                        $parts[] = '<span class="text-gray-800 dark:text-space-200">' . e($letra) . '</span>';
                    }
                @endphp
                <pre class="font-mono text-sm leading-tight m-0 mb-0.5 whitespace-pre">{!! implode("\n", $parts) !!}</pre>
            @endforeach
        @endif
    </div>
@endforeach
</div>
