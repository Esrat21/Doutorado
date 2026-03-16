<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MusicaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Musica::with('artista');
        if ($request->user()) {
            $query->where('usuario_criador_id', $request->user()->id);
        }
        if ($request->filled('artista_id')) {
            $query->where('artista_id', $request->artista_id);
        }
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }
        $musicas = $query->orderBy('titulo')->paginate(20);
        return response()->json($musicas);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'artista_id' => 'nullable|exists:artistas,id',
            'artista_nome' => 'nullable|string|max:200',
            'titulo' => 'required|string|max:200',
            'tom_original' => 'nullable|string|max:20',
            'afinacao_padrao' => 'nullable|string|max:100',
            'genero' => 'nullable|string|max:100',
            'idioma' => 'nullable|string|max:50',
            'conteudo' => 'nullable|string',
            'estrutura' => 'nullable|array',
            'estrutura.tom' => 'nullable|string',
            'estrutura.capo' => 'nullable|integer',
            'estrutura.secoes' => 'nullable|array',
        ]);

        $artistaId = $request->artista_id;
        if (! $artistaId && $request->filled('artista_nome')) {
            $nome = trim($request->artista_nome);
            $slug = Str::slug($nome);
            $artista = Artista::firstOrCreate(
                ['slug' => $slug],
                ['nome' => $nome]
            );
            $artistaId = $artista->id;
        }
        if (! $artistaId) {
            return response()->json(['message' => 'Informe artista_id ou artista_nome.'], 422);
        }

        $slug = Str::slug($request->titulo);
        if (Musica::where('artista_id', $artistaId)->where('slug', $slug)->exists()) {
            $slug = $slug . '-' . uniqid();
        }

        $musica = Musica::create([
            'artista_id' => $artistaId,
            'usuario_criador_id' => $request->user()->id,
            'titulo' => $request->titulo,
            'slug' => $slug,
            'tom_original' => $request->tom_original,
            'afinacao_padrao' => $request->afinacao_padrao,
            'genero' => $request->genero,
            'idioma' => $request->idioma,
        ]);

        $estrutura = $request->input('estrutura');
        $conteudo = $request->input('conteudo');
        if (is_array($estrutura) && ! empty($estrutura['secoes'] ?? [])) {
            $conteudo = $conteudo ?? CifraEstruturaHelper::estruturaToText($estrutura);
        }
        MusicaVersao::create([
            'musica_id' => $musica->id,
            'usuario_criador_id' => $request->user()->id,
            'numero_versao' => 1,
            'titulo_versao' => 'Versão original',
            'conteudo' => $conteudo ?? '',
            'estrutura_json' => $estrutura,
            'is_principal' => true,
            'is_publica' => true,
        ]);

        return response()->json($musica->load('artista'), 201);
    }

    public function show(Musica $musica): JsonResponse
    {
        return response()->json($musica->load(['artista', 'versoes']));
    }

    public function update(Request $request, Musica $musica): JsonResponse
    {
        if ($musica->usuario_criador_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $request->validate([
            'titulo' => 'sometimes|string|max:200',
            'tom_original' => 'nullable|string|max:20',
        ]);
        $musica->update($request->only(['titulo', 'tom_original', 'afinacao_padrao', 'genero', 'idioma']));
        return response()->json($musica->load('artista'));
    }

    public function destroy(Request $request, Musica $musica): JsonResponse
    {
        if ($musica->usuario_criador_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $musica->delete();
        return response()->json(null, 204);
    }
}
