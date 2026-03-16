<?php

namespace App\Services;

/**
 * Converte a estrutura JSON da cifra (tom, capo, secoes) em texto e vice-versa.
 */
class CifraEstruturaHelper
{
    /**
     * Regex para reconhecer acordes no texto.
     * Ex.: C, G#m7, C#m7(9), F/C, D#4, etc.
     */
    private const CHORD_REGEX = '/[A-G][#b]?(?:m|M|maj|min|dim|aug|sus[24]?|add\d|m?\d)*(?:\/[A-G][#b]?)?(?:\([^)]*\))?/u';

    /**
     * Converte texto de cifra (seções [Nome], linhas de acordes + letra) em estrutura JSON.
     *
     * @return array<string, mixed> { tom?, capo?, secoes: [ { nome, tablatura?, linhas: [ { letra, acordes: [ { nome, pos } ] } ] } ] }
     */
    public static function textToEstrutura(string $text): array
    {
        $text = trim($text);
        if ($text === '') {
            return ['secoes' => []];
        }
        $lines = preg_split('/\r\n|\r|\n/', $text);
        if ($lines === false) {
            return ['secoes' => []];
        }
        $secoes = [];
        $chordLinePendente = null;
        $chordLine2Pendente = null;

        foreach ($lines as $line) {
            if (preg_match('/^\[([^\]]*)\]\s*(?:\(Tablatura\))?\s*$/', trim($line), $m)) {
                $nome = trim($m[1]) ?: 'Seção';
                $tablatura = (bool) preg_match('/\(Tablatura\)\s*$/', $line);
                $secoes[] = [
                    'nome' => $nome,
                    'tablatura' => $tablatura ? true : null,
                    'linhas' => [],
                ];
                $chordLinePendente = null;
                $chordLine2Pendente = null;
                continue;
            }

            if (empty($secoes)) {
                $secoes[] = ['nome' => 'Versão original', 'linhas' => []];
            }
            $idx = array_key_last($secoes);
            $estaTablatura = ! empty($secoes[$idx]['tablatura']);

            if (! $estaTablatura && self::looksLikeChordLine($line)) {
                // Linha atual é de acordes
                if ($chordLinePendente === null) {
                    // primeira linha de acordes pendente
                    $chordLinePendente = $line;
                } elseif ($chordLine2Pendente === null) {
                    // segunda linha de acordes (acordes secundários)
                    $chordLine2Pendente = $line;
                } else {
                    // já temos duas linhas pendentes; fecha como bloco separado sem letra
                    $secoes[$idx]['linhas'][] = [
                        'letra' => '',
                        'acordes' => self::extractChordsWithPositions($chordLinePendente),
                        'acordes_secundarios' => self::extractChordsWithPositions($chordLine2Pendente),
                    ];
                    $chordLinePendente = $line;
                    $chordLine2Pendente = null;
                }
                continue;
            }

            // Não é linha de acordes ou estamos em tablatura: se havia acordes pendentes, eles pertencem a esta letra
            if (! $estaTablatura && $chordLinePendente !== null) {
                $linha = [
                    'letra' => $line,
                    'acordes' => self::extractChordsWithPositions($chordLinePendente),
                ];
                if ($chordLine2Pendente !== null) {
                    $linha['acordes_secundarios'] = self::extractChordsWithPositions($chordLine2Pendente);
                }
                $secoes[$idx]['linhas'][] = $linha;
                $chordLinePendente = null;
                $chordLine2Pendente = null;
                continue;
            }

            // Linha normal de texto ou tablatura
            $secoes[$idx]['linhas'][] = [
                'letra' => $line,
                'acordes' => [],
            ];
        }

        if (! empty($secoes) && empty($secoes[array_key_last($secoes)]['tablatura']) && $chordLinePendente !== null) {
            $linha = [
                'letra' => '',
                'acordes' => self::extractChordsWithPositions($chordLinePendente),
            ];
            if ($chordLine2Pendente !== null) {
                $linha['acordes_secundarios'] = self::extractChordsWithPositions($chordLine2Pendente);
            }
            $secoes[array_key_last($secoes)]['linhas'][] = $linha;
        }

        return ['secoes' => $secoes];
    }

    private static function looksLikeChordLine(string $line): bool
    {
        $acordes = self::extractChordsFromLine($line);
        if (empty($acordes)) {
            return false;
        }

        // Remove todos os acordes e verifica o que sobra
        $rest = preg_replace(self::CHORD_REGEX, '', $line);
        $rest = $rest ?? '';

        // Caso típico de linha só de cifras: sobram apenas espaços, pontuação simples, parênteses e números
        if (preg_match('/^[\s\(\)0-9\.\-+\/]*$/u', $rest)) {
            return true;
        }

        // Fallback: heurística anterior (pouco texto "não acorde")
        $restTrim = trim($rest);
        return strlen($restTrim) < strlen($line) * 2 / 3;
    }

    private static function extractChordsFromLine(string $line): array
    {
        if (preg_match_all(self::CHORD_REGEX, $line, $m)) {
            return $m[0];
        }
        return [];
    }

    /** @return list<array{nome: string, pos: int}> */
    private static function extractChordsWithPositions(string $line): array
    {
        $out = [];
        if (preg_match_all(self::CHORD_REGEX, $line, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[0] as $match) {
                $out[] = ['nome' => $match[0], 'pos' => $match[1]];
            }
        }
        return $out;
    }

    /**
     * Gera texto a partir da estrutura (uma linha de acordes acima, uma de letra).
     *
     * @param array<string, mixed> $estrutura
     */
    public static function estruturaToText(array $estrutura): string
    {
        $secoes = $estrutura['secoes'] ?? [];
        if (! is_array($secoes) || empty($secoes)) {
            return '';
        }
        $lines = [];
        foreach ($secoes as $secao) {
            if (! is_array($secao)) {
                continue;
            }
            $nome = $secao['nome'] ?? 'Seção';
            $linhas = $secao['linhas'] ?? [];
            $tablatura = ! empty($secao['tablatura']);
            $lines[] = '[' . $nome . ']' . ($tablatura ? ' (Tablatura)' : '');
            foreach ($linhas as $linha) {
                if (! is_array($linha)) {
                    continue;
                }
                $letra = $linha['letra'] ?? '';
                if (! $tablatura) {
                    $acordes = $linha['acordes'] ?? [];
                    $chordLine = self::buildChordLine($letra, $acordes);
                    if ($chordLine !== '') {
                        $lines[] = $chordLine;
                    }
                    $acordesSec = $linha['acordes_secundarios'] ?? [];
                    $chordLine2 = self::buildChordLine($letra, $acordesSec);
                    if ($chordLine2 !== '') {
                        $lines[] = $chordLine2;
                    }
                }
                $lines[] = $letra;
            }
            $lines[] = '';
        }
        return trim(implode("\n", $lines));
    }

    /**
     * Retorna o texto da cifra com acordes envolvidos em <span class="chord"> para destaque (ex.: PDF).
     * O texto é escapado para HTML; os acordes mantêm a ordem original.
     */
    public static function highlightChordsInHtml(string $text): string
    {
        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_replace_callback(self::CHORD_REGEX, static function (array $m): string {
            return '<span class="chord">' . $m[0] . '</span>';
        }, $escaped);
    }

    /**
     * Monta a linha de acordes a partir do JSON: pos é 0-based (índice onde começa o acorde).
     * Alinhado à lógica da view (buildChordLineStringPartial): comprimento = max(letra, extensão dos acordes).
     */
    private static function buildChordLine(string $letra, array $acordes): string
    {
        if (empty($acordes)) {
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
            $nomeLen = mb_strlen($nome);
            for ($i = 0; $i < $nomeLen; $i++) {
                $idx = $start + $i;
                if ($idx < $len) {
                    $arr[$idx] = mb_substr($nome, $i, 1);
                }
            }
        }
        return implode('', $arr);
    }
}
