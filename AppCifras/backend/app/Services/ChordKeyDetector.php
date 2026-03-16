<?php

namespace App\Services;

/**
 * Detecta o tom (nota base) a partir do conteúdo da cifra.
 * Extrai acordes (ex: C, Am, F#m7) e retorna o mais frequente como tom sugerido.
 */
class ChordKeyDetector
{
    /** Regex: nota (A-G) + opcional # ou b + sufixo opcional (m, 7, etc.) */
    private const CHORD_REGEX = '/[A-G][#b]?(?:m|M|maj|min|dim|aug|sus[24]?|add\d|m?\d)*/';

    public static function detectFromContent(string $conteudo): ?string
    {
        $roots = self::extractRoots($conteudo);
        if (empty($roots)) {
            return null;
        }
        $counts = array_count_values($roots);
        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * @return string[]
     */
    private static function extractRoots(string $conteudo): array
    {
        $roots = [];
        if (preg_match_all(self::CHORD_REGEX, $conteudo, $matches)) {
            foreach ($matches[0] as $chord) {
                $root = self::chordToRoot($chord);
                if ($root !== null) {
                    $roots[] = $root;
                }
            }
        }
        return $roots;
    }

    private static function chordToRoot(string $chord): ?string
    {
        $chord = trim($chord);
        if ($chord === '') {
            return null;
        }
        $letter = strtoupper($chord[0]);
        if (! in_array($letter, ['A', 'B', 'C', 'D', 'E', 'F', 'G'], true)) {
            return null;
        }
        $accidental = '';
        $i = 1;
        if (isset($chord[1]) && ($chord[1] === '#' || $chord[1] === 'b')) {
            $accidental = $chord[1];
            $i = 2;
        }
        return $letter . $accidental;
    }
}
