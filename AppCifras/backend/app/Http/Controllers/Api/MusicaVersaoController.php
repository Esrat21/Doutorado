<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MusicaVersaoController extends Controller
{
    /** Garante que a versão tenha estrutura_json; se não tiver, gera a partir do texto e persiste. */
    private static function ensureEstruturaJson(MusicaVersao $versao): void
    {
        $conteudo = $versao->conteudo ?? '';
        $estrutura = $versao->estrutura_json;
        if ((empty($estrutura) || empty($estrutura['secoes'] ?? [])) && $conteudo !== '') {
            $versao->estrutura_json = CifraEstruturaHelper::textToEstrutura($conteudo);
            $versao->save();
        }
    }

    public function index(Musica $musica): JsonResponse
    {
        $versoes = $musica->versoes()->orderBy('numero_versao')->get();
        foreach ($versoes as $v) {
            self::ensureEstruturaJson($v);
        }
        return response()->json($versoes);
    }

    public function store(Request $request, Musica $musica): JsonResponse
    {
        if ($musica->usuario_criador_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $request->validate([
            'titulo_versao' => 'nullable|string|max:200',
            'conteudo' => 'nullable|string',
            'estrutura' => 'nullable|array',
            'estrutura.secoes' => 'nullable|array',
            'observacoes' => 'nullable|string',
            'is_principal' => 'boolean',
            'is_publica' => 'boolean',
        ]);
        $estrutura = $request->input('estrutura') ?? $request->input('estrutura_json');
        $conteudo = $request->input('conteudo');
        if (is_array($estrutura) && ! empty($estrutura) && ($conteudo === null || $conteudo === '')) {
            $conteudo = \App\Services\CifraEstruturaHelper::estruturaToText($estrutura);
        }
        $conteudo = $conteudo ?? '';

        $numero = $musica->versoes()->max('numero_versao') + 1;
        $versao = MusicaVersao::create([
            'musica_id' => $musica->id,
            'usuario_criador_id' => $request->user()->id,
            'numero_versao' => $numero,
            'titulo_versao' => $request->titulo_versao,
            'conteudo' => $conteudo,
            'estrutura_json' => $estrutura,
            'observacoes' => $request->observacoes,
            'is_principal' => $request->boolean('is_principal'),
            'is_publica' => $request->boolean('is_publica', true),
        ]);

        return response()->json($versao, 201);
    }

    public function show(Musica $musica, MusicaVersao $versao): JsonResponse
    {
        if ($versao->musica_id !== $musica->id) {
            return response()->json(['message' => 'Não encontrado.'], 404);
        }
        self::ensureEstruturaJson($versao);
        return response()->json($versao);
    }

    public function update(Request $request, Musica $musica, MusicaVersao $versao): JsonResponse
    {
        if ($musica->usuario_criador_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        if ($versao->musica_id !== $musica->id) {
            return response()->json(['message' => 'Não encontrado.'], 404);
        }
        $request->validate([
            'titulo_versao' => 'nullable|string|max:200',
            'conteudo' => 'nullable|string',
            'estrutura' => 'nullable|array',
            'estrutura.secoes' => 'nullable|array',
            'observacoes' => 'nullable|string',
            'is_principal' => 'boolean',
            'is_publica' => 'boolean',
        ]);
        $estrutura = $request->input('estrutura') ?? $request->input('estrutura_json');
        $conteudo = $request->input('conteudo');
        if (is_array($estrutura) && ! empty($estrutura) && ($conteudo === null || $conteudo === '')) {
            $conteudo = CifraEstruturaHelper::estruturaToText($estrutura);
        }
        if ($conteudo !== null) {
            $versao->conteudo = $conteudo;
        }
        if (is_array($estrutura) && ! empty($estrutura)) {
            $versao->estrutura_json = $estrutura;
            if ($conteudo === null && (string) ($versao->conteudo ?? '') !== '') {
                $versao->conteudo = CifraEstruturaHelper::estruturaToText($estrutura);
            }
        } else {
            $conteudoAtual = (string) ($versao->conteudo ?? '');
            if ($conteudoAtual !== '') {
                $versao->estrutura_json = CifraEstruturaHelper::textToEstrutura($conteudoAtual);
            }
        }
        if ($request->has('titulo_versao')) {
            $versao->titulo_versao = $request->titulo_versao;
        }
        if ($request->has('observacoes')) {
            $versao->observacoes = $request->observacoes;
        }
        if ($request->has('is_principal')) {
            $versao->is_principal = $request->boolean('is_principal');
        }
        if ($request->has('is_publica')) {
            $versao->is_publica = $request->boolean('is_publica');
        }
        $versao->save();
        return response()->json($versao);
    }
}
