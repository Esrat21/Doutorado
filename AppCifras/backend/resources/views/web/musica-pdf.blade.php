@php
    /**
     * Monta a linha de acordes a partir da letra e lista de acordes (nome + pos).
     * Mesma lógica do partial cifra-estrutura para posição correta no PDF.
     */
    if (!function_exists('pdfBuildChordLine')) {
        function pdfBuildChordLine($letra, $acordes)
        {
            if (!is_array($acordes) || count($acordes) === 0) {
                return '';
            }
            $baseLen = max(mb_strlen($letra ?: ' '), 1);
            $maxEnd = 0;
            foreach ($acordes as $a) {
                $nome = trim($a['nome'] ?? '');
                if ($nome === '') {
                    continue;
                }
                $start = max(0, (int) ($a['pos'] ?? 0));
                $end = $start + mb_strlen($nome);
                if ($end > $maxEnd) {
                    $maxEnd = $end;
                }
            }
            $len = max($baseLen, $maxEnd ?: 1);
            $arr = array_fill(0, $len, ' ');
            foreach ($acordes as $a) {
                $nome = trim($a['nome'] ?? '');
                if ($nome === '') {
                    continue;
                }
                $start = max(0, (int) ($a['pos'] ?? 0));
                for ($i = 0; $i < mb_strlen($nome); $i++) {
                    $idx = $start + $i;
                    if ($idx < $len) {
                        $arr[$idx] = mb_substr($nome, $i, 1);
                    }
                }
            }
            return implode('', $arr);
        }
    }
    /** Envolve acordes da linha em <span class="chord">
 para destaque (laranja) no PDF. */
    if (!function_exists('pdfChordLineToHtml')) {
        function pdfChordLineToHtml($line)
        {
            if ($line === '') {
                return '';
            }
            $escaped = htmlspecialchars($line, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $regex = '/[A-G][#b]?(?:m|M|maj|min|dim|aug|sus[24]?|add\d|m?\d)*(?:\/[A-G][#b]?)?(?:\([^)]*\))?/u';
            return preg_replace_callback(
                $regex,
                function ($m) {
                    return '<span class="chord">' . $m[0] . '</span>';
                },
                $escaped,
            );
        }
    }
@endphp
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.35;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 11px;
            margin: 0 0 2px 0;
        }

        .sub {
            font-size: 9px;
            color: #666666;
            margin: 0 0 6px 0;
        }

        .versao {
            font-size: 9px;
            color: #888888;
            margin: 0 0 2px 0;
        }

        .capo {
            font-size: 9px;
            color: #888888;
            margin: 0 0 10px 0;
        }

        .block {
            margin-bottom: 10px;
            font-size: 9px;
        }

        .pre {
            white-space: pre-wrap;
            font-family: DejaVu Sans Mono, monospace;
            font-size: 9px;
            margin: 0 0 1px 0;
            line-height: 1.3;
        }

        .pre.chord-line {
            color: #e85d04;
            font-weight: bold;
            font-size: 9px;
        }

        .pre .chord {
            color: #e85d04;
            font-weight: bold;
        }

        .pre.lyric {
            color: #333333;
            font-weight: normal;
            font-size: 9px;
        }

        .section {
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #555555;
            margin-bottom: 3px;
        }

        .pre.tablatura {
            font-size: 9px;
            color: #666666;
        }
    </style>
</head>

<body>
    <h1>{{ $titulo }}</h1>
    <p class="sub">{{ $artista }}</p>
    <p class="versao">{{ $versaoLabel }}</p>
    <p class="capo">{{ $capoLabel }}</p>

    <columns column-count="2" column-gap="12" />
    <div class="block">
        @if ($estrutura && is_array($estrutura['secoes'] ?? null) && count($estrutura['secoes']) > 0)
            @foreach ($estrutura['secoes'] as $sec)
                <div class="section">
                    <div class="section-title">[{{ $sec['nome'] ?? '' }}]@if (!empty($sec['tablatura']))
                            (Tablatura)
                        @endif
                    </div>
                    @if (!empty($sec['tablatura']))
                        <pre class="pre tablatura">{{ implode("\n", array_filter(array_map(fn($l) => trim($l['letra'] ?? ''), $sec['linhas'] ?? []))) }}</pre>
                    @else
                        @foreach ($sec['linhas'] ?? [] as $linha)
                            @php
                                $l1 = pdfBuildChordLine($linha['letra'] ?? ' ', $linha['acordes'] ?? []);
                                $l2 = pdfBuildChordLine($linha['letra'] ?? ' ', $linha['acordes_secundarios'] ?? []);
                                $letra = $linha['letra'] ?? '';
                            @endphp
                            @if ($l1 !== '')
                                <pre class="pre chord-line">{!! pdfChordLineToHtml($l1) !!}</pre>
                            @endif
                            @if ($l2 !== '')
                                <pre class="pre chord-line">{!! pdfChordLineToHtml($l2) !!}</pre>
                            @endif
                            <pre class="pre lyric">{{ $letra }}</pre>
                        @endforeach
                    @endif
                </div>
            @endforeach
        @else
            <pre class="pre">{!! $conteudoHtml !!}</pre>
        @endif
    </div>
</body>

</html>
