<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlaylistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $playlists = $request->user()
            ->playlists()
            ->withCount('musicas')
            ->orderBy('nome')
            ->get();
        return response()->json($playlists);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'is_public' => 'sometimes|boolean',
        ]);

        $playlist = $request->user()->playlists()->create([
            'nome' => $request->nome,
            'is_public' => (bool) $request->boolean('is_public', false),
            'slug' => self::generateUniqueSlug($request->user()->id, $request->nome),
        ]);
        return response()->json($playlist->loadCount('musicas'), 201);
    }

    public function show(Request $request, Playlist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        return response()->json($playlist->load(['musicas.artista']));
    }

    public function update(Request $request, Playlist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $request->validate([
            'nome' => 'required|string|max:200',
            'is_public' => 'sometimes|boolean',
        ]);
        $playlist->update([
            'nome' => $request->nome,
            'is_public' => (bool) $request->boolean('is_public', $playlist->is_public),
        ]);
        return response()->json($playlist->loadCount('musicas'));
    }

    public function destroy(Request $request, Playlist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $playlist->delete();
        return response()->json(null, 204);
    }

    public function addMusica(Request $request, Playlist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $request->validate(['musica_id' => 'required|exists:musicas,id']);
        $maxOrdem = (int) DB::table('playlist_musica')->where('playlist_id', $playlist->id)->max('ordem');
        $playlist->musicas()->syncWithoutDetaching([
            $request->musica_id => ['ordem' => $maxOrdem + 1],
        ]);
        return response()->json($playlist->load(['musicas.artista']));
    }

    public function removeMusica(Request $request, Playlist $playlist, int $musica_id): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }
        $playlist->musicas()->detach($musica_id);
        return response()->json($playlist->load(['musicas.artista']));
    }

    /**
     * Exibe playlist pública por slug (rota pública).
     */
    public function showPublic(string $slug): JsonResponse
    {
        $playlist = Playlist::where('slug', $slug)
            ->where('is_public', true)
            ->with(['musicas.artista'])
            ->first();

        if (! $playlist) {
            return response()->json(['message' => 'Playlist não encontrada.'], 404);
        }

        return response()->json($playlist);
    }

    private static function generateUniqueSlug(int $userId, string $nome): string
    {
        // baseado no id do usuário e nome, mas com sufixo aleatório para evitar colisões
        $base = substr(Str::slug($nome . '-' . $userId), 0, 40);
        $suffix = substr(hash('sha256', $userId . '|' . $nome . '|' . microtime(true)), 0, 10);
        return $base !== '' ? ($base . '-' . $suffix) : $suffix;
    }
}
