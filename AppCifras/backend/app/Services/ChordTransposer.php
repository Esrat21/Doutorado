<?php

namespace App\Services;

/**
 * Transpõe acordes em texto por N semitones.
 * Reconhece acordes no formato: nota (#|b) + sufixo (m, 7, maj7, etc.)
 */
class ChordTransposer
{
    /** Ordem cromática usando sustenido (C, C#, D, ...) */
    private const NOTES_SHARP = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];

    /** Equivalente com bemol para entrada */
    private const NOTES_FLAT = ['C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B'];

    /** Regex: acorde (A-G + opcional # ou b) + sufixo opcional */
    private const CHORD_REGEX = '/[A-G][#b]?(?:m|M|maj|min|dim|aug|sus[24]?|add\d|m?\d)*/';

    public static function transpose(string $conteudo, int $semitones): string
    {
        if ($semitones === 0) {
            return $conteudo;
        }
        $semitones = ((int) $semitones % 12 + 12) % 12;
        return preg_replace_callback(self::CHORD_REGEX, function (array $m) use ($semitones): string {
            return self::transposeChord($m[0], $semitones);
        }, $conteudo);
    }

    /** Transpõe um único acorde (ex: "Am7" -> "Bm7" para +2 semitones). */
    public static function transposeChordName(string $chord, int $semitones): string
    {
        if ($semitones === 0) {
            return $chord;
        }
        $semitones = ((int) $semitones % 12 + 12) % 12;
        return self::transposeChord($chord, $semitones);
    }

    private static function transposeChord(string $chord, int $semitones): string
    {
        $root = self::extractRoot($chord);
        if ($root === null) {
            return $chord;
        }
        $suffix = substr($chord, strlen($root));
        $index = self::rootToIndex($root);
        if ($index === null) {
            return $chord;
        }
        $newIndex = ($index + $semitones) % 12;
        $newRoot = self::NOTES_SHARP[$newIndex];
        return $newRoot . $suffix;
    }

    /**
     * Transpõe a estrutura JSON da cifra (tom, capo, secoes com acordes).
     *
     * @param array<string, mixed> $estrutura
     * @return array<string, mixed>
     */
    public static function transposeEstrutura(array $estrutura, int $semitones): array
    {
        if ($semitones === 0) {
            return $estrutura;
        }
        $out = $estrutura;
        if (isset($out['tom']) && is_string($out['tom'])) {
            $out['tom'] = self::transposeChordName($out['tom'], $semitones);
        }
        if (isset($out['secoes']) && is_array($out['secoes'])) {
            foreach ($out['secoes'] as $i => $secao) {
                if (! is_array($secao) || ! isset($secao['linhas'])) {
                    continue;
                }
                if (! empty($secao['tablatura'])) {
                    continue;
                }
                foreach ($secao['linhas'] as $j => $linha) {
                    if (! is_array($linha)) {
                        continue;
                    }
                    foreach (['acordes', 'acordes_secundarios'] as $key) {
                        if (! isset($linha[$key]) || ! is_array($linha[$key])) {
                            continue;
                        }
                        foreach ($linha[$key] as $k => $acorde) {
                            if (is_array($acorde) && isset($acorde['nome']) && is_string($acorde['nome'])) {
                                $out['secoes'][$i]['linhas'][$j][$key][$k]['nome'] = self::transposeChordName(
                                    $acorde['nome'],
                                    $semitones
                                );
                            }
                        }
                    }
                }
            }
        }
        return $out;
    }

    private static function extractRoot(string $chord): ?string
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

    private static function rootToIndex(string $root): ?int
    {
        $root = str_replace('b', 'b', $root);
        foreach (self::NOTES_SHARP as $i => $note) {
            if (strcasecmp($note, $root) === 0) {
                return $i;
            }
        }
        foreach (self::NOTES_FLAT as $i => $note) {
            if (strcasecmp($note, $root) === 0) {
                return $i;
            }
        }
        return null;
    }

    /** Converte nome do tom (ex: C, Am) para semitones a partir de C (0-11). */
    public static function keyToSemitones(string $key): int
    {
        $root = self::extractRoot(trim($key));
        if ($root === null) {
            return 0;
        }
        $idx = self::rootToIndex($root);
        return $idx ?? 0;
    }

    /** Retorna semitones para ir de uma tonalidade a outra (destino - origem). */
    public static function semitonesBetween(string $fromKey, string $toKey): int
    {
        $from = self::keyToSemitones($fromKey);
        $to = self::keyToSemitones($toKey);
        $diff = $to - $from;
        return ($diff % 12 + 12) % 12;
    }

    /** Lista de tons para dropdown (usando sustenido). */
    public static function allKeys(): array
    {
        return self::NOTES_SHARP;
    }

    /** Tons maiores e menores relativos para optgroup no select (como no React). */
    public static function tonsParaSelect(): array
    {
        $menorRelativo = ['C' => 'Am', 'C#' => 'A#m', 'D' => 'Bm', 'D#' => 'Cm', 'E' => 'C#m', 'F' => 'Dm', 'F#' => 'D#m', 'G' => 'Em', 'G#' => 'Fm', 'A' => 'F#m', 'A#' => 'Gm', 'B' => 'G#m'];
        $maiorRelativo = array_flip($menorRelativo);
        return [
            'maior' => self::NOTES_SHARP,
            'menor' => array_values(array_unique($menorRelativo)),
            'menor_de' => $menorRelativo,
            'maior_de' => $maiorRelativo,
        ];
    }

    /** Lista de todos os tons (maior + menor) para validação do select. */
    public static function allKeysIncludingMinor(): array
    {
        $data = self::tonsParaSelect();
        return array_values(array_unique(array_merge($data['maior'], $data['menor'])));
    }

    /** Retorna o tom relativo (maior <-> menor). */
    public static function tomRelativo(string $tom): string
    {
        $tom = trim($tom);
        $data = self::tonsParaSelect();
        if (isset($data['menor_de'][$tom])) {
            return $data['menor_de'][$tom];
        }
        if (isset($data['maior_de'][$tom])) {
            return $data['maior_de'][$tom];
        }
        $root = preg_replace('/m$|maj$|min$/i', '', $tom);
        if (isset($data['maior_de'][$root . 'm'])) {
            return $data['maior_de'][$root . 'm'];
        }
        return $tom;
    }
}
