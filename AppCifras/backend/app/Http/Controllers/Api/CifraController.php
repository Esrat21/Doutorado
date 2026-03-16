<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChordTransposer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CifraController extends Controller
{
    /** Transpõe o conteúdo da cifra (texto) por N semitones. */
    public function transpose(Request $request): JsonResponse
    {
        $request->validate([
            'conteudo' => 'required|string',
            'semitones' => 'required|integer|min:-11|max:11',
        ]);
        $conteudo = ChordTransposer::transpose(
            $request->conteudo,
            (int) $request->semitones
        );
        return response()->json(['conteudo' => $conteudo]);
    }

    /** Transpõe a estrutura JSON da cifra (tom, capo, secoes) por N semitones. */
    public function transposeEstrutura(Request $request): JsonResponse
    {
        $request->validate([
            'estrutura' => 'required|array',
            'estrutura.secoes' => 'required|array',
            'semitones' => 'required|integer|min:-11|max:11',
        ]);
        $estrutura = ChordTransposer::transposeEstrutura(
            $request->estrutura,
            (int) $request->semitones
        );
        return response()->json(['estrutura' => $estrutura]);
    }

    /** Lista de tons disponíveis (para dropdown). */
    public function tons(Request $request): JsonResponse
    {
        return response()->json(['tons' => ChordTransposer::allKeys()]);
    }
}
