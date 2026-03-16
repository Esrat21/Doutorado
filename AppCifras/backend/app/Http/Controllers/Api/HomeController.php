<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** Gêneros distintos das músicas do usuário (para filtro). */
    public function generos(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        $generos = Musica::when($userId, fn ($q) => $q->where('usuario_criador_id', $userId))
            ->whereNotNull('genero')
            ->where('genero', '!=', '')
            ->distinct()
            ->orderBy('genero')
            ->pluck('genero');

        $defaults = ['Rock', 'Sertanejo', 'Gospel/Religioso', 'MPB', 'Pop', 'Eletrônica'];
        $all = collect(['Todas'])->merge($generos->isEmpty() ? $defaults : $generos->values());

        return response()->json(['generos' => $all->values()->all()]);
    }

    /** 20 músicas em alta (mais recentes do usuário). */
    public function musicasEmAlta(Request $request): JsonResponse
    {
        $query = Musica::with('artista');
        if ($request->user()) {
            $query->where('usuario_criador_id', $request->user()->id);
        }
        if ($request->filled('genero') && $request->genero !== 'Todas') {
            $query->where('genero', $request->genero);
        }
        $musicas = $query->orderByDesc('created_at')->limit(20)->get();
        return response()->json($musicas);
    }

    /** Artistas populares (com mais músicas do usuário). */
    public function artistasPopulares(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        $artistas = Artista::query()
            ->when($userId, fn ($q) => $q->whereHas('musicas', fn ($m) => $m->where('usuario_criador_id', $userId)))
            ->withCount(['musicas' => fn ($q) => $userId ? $q->where('usuario_criador_id', $userId) : $q])
            ->having('musicas_count', '>', 0)
            ->orderByDesc('musicas_count')
            ->limit(15)
            ->get();

        return response()->json($artistas);
    }
}
